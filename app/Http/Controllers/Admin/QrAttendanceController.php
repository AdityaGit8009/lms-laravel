<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QrAttendanceController extends Controller
{
    public function showForm(Request $request): View
    {
        $batches = $this->studentBatches($request->user()->email)->get();

        return view('Admin.Attendance.qr-form', compact('batches'));
    }

    public function submitForm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mobile' => ['required', 'regex:/^[0-9]{10}$/'],
            'batch_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();
        $student = Student::where('stu_email', $user->email)
            ->where('del_status', 'N')->where('is_block', 'N')->first();
        if (!$student || substr(preg_replace('/\D/', '', $student->stu_mobile), -10) !== $validated['mobile']) {
            return back()->withInput()->withErrors(['mobile' => 'Enter the mobile number on your student record.']);
        }

        $batches = $this->studentBatches($user->email)->get();
        $batch = isset($validated['batch_id'])
            ? $batches->firstWhere('batch_id', $validated['batch_id'])
            : ($batches->count() === 1 ? $batches->first() : null);
        if (!$batch) {
            return back()->withInput()->withErrors(['batch_id' => 'Select an active batch assigned to you.']);
        }

        $now = now();
        $date = $now->toDateString();
        try {
            return DB::transaction(function () use ($request, $student, $batch, $user, $now, $date) {
                $attendance = DB::table('tbl_attendance')
                    ->where('attend_date', $date)->where('attend_student', $student->stu_id)
                    ->where('attend_batch', $batch->batch_id)->lockForUpdate()->first();
                if ($attendance && $attendance->attendance === 'P') {
                    return back()->with('error', 'Your attendance is already marked.');
                }

                DB::table('tbl_qr_attendance_device_logs')->insert([
                    'stu_id' => $student->stu_id,
                    'mobile' => $request->input('mobile'),
                    'ip_address' => $request->ip(),
                    'attendance_date' => $date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $mark = [
                    'attendance' => 'P',
                    'attend_by' => (string) $user->getKey(),
                    'added_date' => $date,
                    'added_time' => $now->format('H:i:s'),
                ];
                if ($attendance) {
                    DB::table('tbl_attendance')->where('attend_id', $attendance->attend_id)->update($mark);
                } else {
                    DB::table('tbl_attendance')->insert($mark + [
                        'attend_date' => $date,
                        'attend_student' => (string) $student->stu_id,
                        'attend_center' => (string) $student->center_id,
                        'attend_session' => (string) $batch->session_id,
                        'attend_programme' => (string) $batch->programme_id,
                        'attend_technology' => (string) $batch->technology_id,
                        'attend_batch' => (string) $batch->batch_id,
                    ]);
                }

                return back()->with('success', 'Attendance marked successfully.');
            });
        } catch (UniqueConstraintViolationException $exception) {
            return back()->with('error', 'Attendance has already been marked from this device today.');
        }
    }

    private function studentBatches(string $email)
    {
        return DB::table('tbl_student as s')
            ->join('tbl_student_batch as sb', 'sb.stu_id', '=', 's.stu_id')
            ->join('tbl_batch as b', 'b.batch_id', '=', 'sb.batch_id')
            ->where('s.stu_email', $email)->where('s.del_status', 'N')->where('s.is_block', 'N')
            ->where('sb.is_active', 'Y')
            ->orderBy('b.batch_name')
            ->select('b.batch_id', 'b.batch_name', 'b.session_id', 'b.programme_id', 'b.technology_id');
    }
}
