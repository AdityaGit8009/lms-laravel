<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QrAttendanceController extends Controller
{
  public function showForm()
  {
    return view('attendance.qr-form');
  }

  public function submitForm(Request $request)
  {
    $request->validate(
      [
        'mobile' => ['required', 'regex:/^[0-9]{10}$/'],
      ],
      [
        'mobile.required' => 'Mobile number is required.',
        'mobile.regex'    => 'Please enter a valid 10 digit mobile number.',
      ]
    );

    $mobile = preg_replace('/\D+/', '', $request->mobile);
    $mobile = substr($mobile, -10);

    $today = now()->toDateString();
    $ip    = $this->getClientIp($request);

    // Student find by mobile (last 10 digits match)
    $studentCandidates = DB::table('tbl_student')
      ->where('del_status', 0)
      ->where('stu_mobile', 'like', '%' . $mobile . '%')
      ->select(
        'stu_id',
        'stu_name',
        'stu_regid',
        'center_id',
        'stu_course',
        'stu_course_year',
        'stu_branch',
        'stu_session',
        'stu_mobile'
      )
      ->get();

    $student = null;
    foreach ($studentCandidates as $row) {
      $cleanDbMobile = preg_replace('/\D+/', '', (string) $row->stu_mobile);
      $last10        = substr($cleanDbMobile, -10);
      if ($last10 === $mobile) {
        $student = $row;
        break;
      }
    }

    if (!$student) {
      return back()->withInput()->with('error', 'Student not found with this mobile number.');
    }

    // Check student's today attendance first
    $attendance = DB::table('tbl_attendance')
      ->where('stu_id', $student->stu_id)
      ->whereDate('attendance_date', $today)
      ->first();

    if ($attendance && isset($attendance->attendance_status) && strtolower($attendance->attendance_status) === 'present') {
      return back()->withInput()->with('error', 'Already attendance is marked.');
    }

    // One IP/device once per day
    $deviceAlreadyUsed = DB::table('tbl_qr_attendance_device_logs')
      ->where('ip_address', $ip)
      ->whereDate('attendance_date', $today)
      ->exists();

    if ($deviceAlreadyUsed) {
      return back()->withInput()->with('error', 'This device has already marked attendance today.');
    }

    DB::beginTransaction();

    try {
      if ($attendance) {
        // If absent row already exists, update it to present
        DB::table('tbl_attendance')
          ->where('id', $attendance->id) // apne attendance table ki PK id alag ho to isko change kar dena
          ->update([
            'attendance_status' => 'Present',
            'ip_address'        => $ip,
            'marked_via'        => 'QR',
            'updated_at'        => now(),
          ]);
      } else {
        // Fresh insert
        DB::table('tbl_attendance')->insert([
          'stu_id'             => $student->stu_id,
          'stu_regid'          => $student->stu_regid ?? null,
          'student_name'       => $student->stu_name ?? null,
          'center_id'          => $student->center_id ?? null,
          'session_id'         => $student->stu_session ?? null,
          'course'             => $student->stu_course ?? null,
          'course_year'        => $student->stu_course_year ?? null,
          'branch'             => $student->stu_branch ?? null,
          'stu_mobile'         => $student->stu_mobile ?? $mobile,
          'attendance_date'    => $today,
          'attendance_status'  => 'Present',
          'ip_address'         => $ip,
          'marked_via'         => 'QR',
          'created_at'         => now(),
          'updated_at'         => now(),
        ]);
      }

      DB::table('tbl_qr_attendance_device_logs')->insert([
        'stu_id'           => $student->stu_id,
        'mobile'           => $mobile,
        'ip_address'       => $ip,
        'attendance_date'  => $today,
        'created_at'       => now(),
        'updated_at'       => now(),
      ]);

      DB::commit();

      return back()->with('success', 'Attendance marked successfully for ' . $student->stu_name . '.');
    } catch (\Throwable $e) {
      DB::rollBack();
      return back()->withInput()->with('error', 'Something went wrong. ' . $e->getMessage());
    }
  }

  private function getClientIp(Request $request)
  {
    $ip = $request->header('X-Forwarded-For');

    if (!empty($ip)) {
      $parts = explode(',', $ip);
      return trim($parts[0]);
    }

    return $request->ip();
  }
}
