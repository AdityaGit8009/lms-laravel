<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $sessions = DB::table('tbl_session')
            ->select('session_id', 'session_name')
            ->orderByDesc('session_id')
            ->get();

        return view('Admin.Attendance.add_attendance', compact('sessions'));
    }

    private function arr($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value, fn($v) => $v !== null && $v !== ''));
        }

        if ($value === null || $value === '') {
            return [];
        }

        return [$value];
    }

    private function attendanceAlreadyMarked(
        string $attendanceDate,
        array $sessionIds = [],
        array $centerIds = [],
        array $programmeIds = [],
        array $technologyIds = [],
        array $batchIds = []
    ): bool {
        $query = DB::table('tbl_attendance')
            ->where('attend_date', $attendanceDate);

        if (!empty($sessionIds)) {
            $query->whereIn('attend_session', array_map('strval', $sessionIds));
        }

        if (!empty($centerIds)) {
            $query->whereIn('attend_center', array_map('strval', $centerIds));
        }

        if (!empty($programmeIds)) {
            $query->whereIn('attend_programme', array_map('strval', $programmeIds));
        }

        if (!empty($technologyIds)) {
            $query->whereIn('attend_technology', array_map('strval', $technologyIds));
        }

        if (!empty($batchIds)) {
            $query->whereIn('attend_batch', array_map('strval', $batchIds));
        }

        return $query->exists();
    }

    public function getCenters(Request $request)
    {
        $sessionIds = $this->arr($request->session_ids);

        if (empty($sessionIds)) {
            return response()->json([]);
        }

        $centers = DB::table('tbl_programme_centers as pc')
            ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
            ->select('c.center_id as id', 'c.center_name as text')
            ->whereIn('pc.session_id', $sessionIds)
            ->distinct()
            ->orderBy('c.center_name')
            ->get();

        return response()->json($centers);
    }

    public function getProgrammes(Request $request)
    {
        $sessionIds = $this->arr($request->session_ids);
        $centerIds  = $this->arr($request->center_ids);

        if (empty($sessionIds) || empty($centerIds)) {
            return response()->json([]);
        }

        $programmes = DB::table('tbl_programme_centers as pc')
            ->join('tbl_programme as p', 'p.programme_id', '=', 'pc.programme_id')
            ->whereNull('p.deleted_at')
            ->select('p.programme_id as id', 'p.programme_name as text')
            ->whereIn('pc.session_id', $sessionIds)
            ->whereIn('pc.center_id', $centerIds)
            ->distinct()
            ->orderBy('p.programme_name')
            ->get();

        return response()->json($programmes);
    }

    public function getTechnologies(Request $request)
    {
        $sessionIds   = $this->arr($request->session_ids);
        $programmeIds = $this->arr($request->programme_ids);

        if (empty($sessionIds) || empty($programmeIds)) {
            return response()->json([]);
        }

        $technologies = DB::table('tbl_technology')
            ->select('technology_id as id', 'technology_name as text')
            ->whereIn('session_id', $sessionIds)
            ->whereIn('programme_id', $programmeIds)
            ->distinct()
            ->orderBy('technology_name')
            ->get();

        return response()->json($technologies);
    }

    public function getBatches(Request $request)
    {
        $sessionIds    = $this->arr($request->session_ids);
        $programmeIds  = $this->arr($request->programme_ids);
        $technologyIds = $this->arr($request->technology_ids);

        if (empty($sessionIds) || empty($programmeIds) || empty($technologyIds)) {
            return response()->json([]);
        }

        $batches = DB::table('tbl_batch')
            ->select('batch_id as id', 'batch_name as text')
            ->whereIn('session_id', $sessionIds)
            ->whereIn('programme_id', $programmeIds)
            ->whereIn('technology_id', $technologyIds)
            ->distinct()
            ->orderBy('batch_name')
            ->get();

        return response()->json($batches);
    }

    public function fetchStudents(Request $request)
    {
        $attendanceDate = $request->filled('attendance_date')
            ? $request->attendance_date
            : now()->format('Y-m-d');

        $sessionIds    = $this->arr($request->session_ids);
        $centerIds     = $this->arr($request->center_ids);
        $programmeIds  = $this->arr($request->programme_ids);
        $technologyIds = $this->arr($request->technology_ids);
        $batchIds      = $this->arr($request->batch_ids);

        if (empty($sessionIds) || empty($centerIds) || empty($programmeIds) || empty($technologyIds) || empty($batchIds)) {
            return response()->json([
                'status'  => false,
                'message' => 'Please select Session, Center, Programme, Technology and Batch.'
            ]);
        }

        if ($this->attendanceAlreadyMarked(
            $attendanceDate,
            $sessionIds,
            $centerIds,
            $programmeIds,
            $technologyIds,
            $batchIds
        )) {
            return response()->json([
                'status'  => false,
                'message' => 'Attendance already marked for selected date and filters.'
            ]);
        }

        $students = DB::table('tbl_student_batch as sb')
            ->join('tbl_student as s', 's.stu_id', '=', 'sb.stu_id')
            ->join('tbl_batch as b', 'b.batch_id', '=', 'sb.batch_id')
            ->where('sb.is_active', 'Y')
            ->where('s.del_status', 'N')
            ->where('s.is_block', 'N')
            ->whereIn('s.center_id', $centerIds)
            ->whereIn('b.session_id', $sessionIds)
            ->whereIn('b.programme_id', $programmeIds)
            ->whereIn('b.technology_id', $technologyIds)
            ->whereIn('b.batch_id', $batchIds)
            ->select(
                's.stu_id',
                's.stu_regid',
                's.stu_name',
                's.stu_college',
                's.stu_mobile',
                's.center_id',
                'b.session_id',
                'b.programme_id',
                'b.batch_id',
                'b.technology_id'
            )
            ->distinct()
            ->orderBy('s.stu_name')
            ->get()
            ->map(function ($student) {
                $student->attendance_status = 'A';
                return $student;
            });

        return response()->json([
            'status' => true,
            'data'   => $students
        ]);
    }

    public function store(Request $request)
    {
        $attendanceDate = $request->filled('attendance_date')
            ? $request->attendance_date
            : now()->format('Y-m-d');

        $students = $request->input('students', []);

        if (empty($students) || !is_array($students)) {
            return response()->json([
                'status' => false,
                'message' => 'No students available to save.'
            ]);
        }

        $firstRow = $students[0] ?? [];

        $sessionIds    = isset($firstRow['session_id']) ? [(string) $firstRow['session_id']] : [];
        $centerIds     = isset($firstRow['center_id']) ? [(string) $firstRow['center_id']] : [];
        $programmeIds  = isset($firstRow['programme_id']) ? [(string) $firstRow['programme_id']] : [];
        $technologyIds = isset($firstRow['technology_id']) ? [(string) $firstRow['technology_id']] : [];
        $batchIds      = isset($firstRow['batch_id']) ? [(string) $firstRow['batch_id']] : [];

        if ($this->attendanceAlreadyMarked(
            $attendanceDate,
            $sessionIds,
            $centerIds,
            $programmeIds,
            $technologyIds,
            $batchIds
        )) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance already marked for selected date and filters.'
            ]);
        }

        $attendBy = (string) (auth()->id() ?? '48');
        $addedDate = now()->format('Y-m-d');
        $addedTime = now()->format('H:i:s');

        $savedCount = 0;

        foreach ($students as $student) {
            $stuId        = $student['stu_id'] ?? null;
            $sessionId    = $student['session_id'] ?? null;
            $centerId     = $student['center_id'] ?? null;
            $programmeId  = $student['programme_id'] ?? null;
            $technologyId = $student['technology_id'] ?? null;
            $batchId      = $student['batch_id'] ?? null;
            $attendance   = $student['attendance'] ?? 'A';

            if (!$stuId || !$technologyId || !$batchId) {
                continue;
            }

            if (!in_array($attendance, ['P', 'A'])) {
                $attendance = 'A';
            }

            DB::table('tbl_attendance')->insert([
                'attend_date'       => $attendanceDate,
                'attend_session'    => (string) ($sessionId ?? ''),
                'attend_center'     => (string) ($centerId ?? ''),
                'attend_programme'  => (string) ($programmeId ?? ''),
                'attend_technology' => (string) $technologyId,
                'attend_batch'      => (string) $batchId,
                'attend_student'    => (string) $stuId,
                'attendance'        => $attendance,
                'attend_by'         => $attendBy,
                'added_date'        => $addedDate,
                'added_time'        => $addedTime,
            ]);

            $savedCount++;
        }

        if ($savedCount === 0) {
            return response()->json([
                'status' => false,
                'message' => 'No valid attendance rows found.'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Attendance saved successfully.'
        ]);
    }

    public function downloadBulkTemplate()
    {
        $fileName = 'bulk_attendance_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['attendance_date', 'mobile']);
            fputcsv($file, ['2026-04-08', '7390916222']);
            fputcsv($file, ['2026-04-08', '8800000045']);
            fputcsv($file, ['2026-04-09', '7390916222']);
            fputcsv($file, ['2026-04-09', '8800000088']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkStore(Request $request)
    {
        $sessionIds    = is_array($request->session_ids) ? array_values(array_filter($request->session_ids)) : [];
        $centerIds     = is_array($request->center_ids) ? array_values(array_filter($request->center_ids)) : [];
        $programmeIds  = is_array($request->programme_ids) ? array_values(array_filter($request->programme_ids)) : [];
        $technologyIds = is_array($request->technology_ids) ? array_values(array_filter($request->technology_ids)) : [];
        $batchIds      = is_array($request->batch_ids) ? array_values(array_filter($request->batch_ids)) : [];

        if (empty($sessionIds) || empty($centerIds) || empty($programmeIds) || empty($technologyIds) || empty($batchIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select Session, Center, Programme, Technology and Batch.'
            ], 422);
        }

        if (!$request->hasFile('csv_file')) {
            return response()->json([
                'status' => false,
                'message' => 'CSV file is missing.'
            ], 422);
        }

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['csv', 'txt'])) {
            return response()->json([
                'status' => false,
                'message' => 'Only CSV file is allowed.'
            ], 422);
        }

        $dateWiseMobiles = [];
        $headerMap = [];
        $rowIndex = 0;

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle, 2000, ',')) !== false) {
                $rowIndex++;

                if ($rowIndex === 1) {
                    foreach ($row as $index => $heading) {
                        $key = strtolower(trim((string) $heading));
                        $headerMap[$key] = $index;
                    }
                    continue;
                }

                if (
                    !isset($headerMap['attendance_date']) &&
                    !isset($headerMap['date'])
                ) {
                    fclose($handle);
                    return response()->json([
                        'status' => false,
                        'message' => 'CSV must contain attendance_date column.'
                    ], 422);
                }

                if (!isset($headerMap['mobile'])) {
                    fclose($handle);
                    return response()->json([
                        'status' => false,
                        'message' => 'CSV must contain mobile column.'
                    ], 422);
                }

                $dateIndex = $headerMap['attendance_date'] ?? $headerMap['date'];
                $mobileIndex = $headerMap['mobile'];

                $attendanceDateRaw = isset($row[$dateIndex]) ? trim((string) $row[$dateIndex]) : '';
                $mobileRaw = isset($row[$mobileIndex]) ? trim((string) $row[$mobileIndex]) : '';

                if ($attendanceDateRaw === '' || $mobileRaw === '') {
                    continue;
                }

                try {
                    $attendanceDate = \Carbon\Carbon::parse($attendanceDateRaw)->format('Y-m-d');
                } catch (\Throwable $e) {
                    fclose($handle);
                    return response()->json([
                        'status' => false,
                        'message' => "Invalid attendance_date found in CSV at row {$rowIndex}."
                    ], 422);
                }

                $mobile = preg_replace('/\D+/', '', $mobileRaw);

                if ($mobile === '') {
                    continue;
                }

                if (!isset($dateWiseMobiles[$attendanceDate])) {
                    $dateWiseMobiles[$attendanceDate] = [];
                }

                $dateWiseMobiles[$attendanceDate][$mobile] = true;
            }

            fclose($handle);
        }

        if (empty($dateWiseMobiles)) {
            return response()->json([
                'status' => false,
                'message' => 'No valid attendance_date and mobile rows found in CSV.'
            ], 422);
        }

        $students = DB::table('tbl_student_batch as sb')
            ->join('tbl_student as s', 's.stu_id', '=', 'sb.stu_id')
            ->join('tbl_batch as b', 'b.batch_id', '=', 'sb.batch_id')
            ->where('sb.is_active', 'Y')
            ->where('s.del_status', 'N')
            ->where('s.is_block', 'N')
            ->whereIn('s.center_id', $centerIds)
            ->whereIn('b.session_id', $sessionIds)
            ->whereIn('b.programme_id', $programmeIds)
            ->whereIn('b.technology_id', $technologyIds)
            ->whereIn('b.batch_id', $batchIds)
            ->select(
                's.stu_id',
                's.stu_mobile',
                's.center_id',
                'b.session_id',
                'b.programme_id',
                'b.technology_id',
                'b.batch_id'
            )
            ->distinct()
            ->get();

        if ($students->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No students found for selected filters.'
            ], 404);
        }

        $attendBy  = (string) (auth()->id() ?? '48');
        $addedDate = now()->format('Y-m-d');
        $addedTime = now()->format('H:i:s');

        $presentCount = 0;
        $absentCount  = 0;
        $savedRows    = 0;

        foreach ($dateWiseMobiles as $attendanceDate => $mobileMap) {
            foreach ($students as $student) {
                $studentMobile = preg_replace('/\D+/', '', (string) $student->stu_mobile);
                $status = isset($mobileMap[$studentMobile]) ? 'P' : 'A';

                DB::table('tbl_attendance')->updateOrInsert(
                    [
                        'attend_date'       => $attendanceDate,
                        'attend_technology' => (string) $student->technology_id,
                        'attend_batch'      => (string) $student->batch_id,
                        'attend_student'    => (string) $student->stu_id,
                    ],
                    [
                        'attend_session'    => (string) $student->session_id,
                        'attend_center'     => (string) $student->center_id,
                        'attend_programme'  => (string) $student->programme_id,
                        'attendance'        => $status,
                        'attend_by'         => $attendBy,
                        'added_date'        => $addedDate,
                        'added_time'        => $addedTime,
                    ]
                );

                $savedRows++;

                if ($status === 'P') {
                    $presentCount++;
                } else {
                    $absentCount++;
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Bulk attendance saved successfully. Dates: ' . count($dateWiseMobiles) .
                ', Rows: ' . $savedRows .
                ', Present: ' . $presentCount .
                ', Absent: ' . $absentCount
        ]);
    }

    private function getCenterList(array $sessionIds = [])
    {
        if (empty($sessionIds)) {
            return collect([]);
        }

        return DB::table('tbl_programme_centers as pc')
            ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
            ->select('c.center_id', 'c.center_name')
            ->whereIn('pc.session_id', $sessionIds)
            ->distinct()
            ->orderBy('c.center_name')
            ->get();
    }

    private function getProgrammeList(array $sessionIds = [], array $centerIds = [])
    {
        if (empty($sessionIds) || empty($centerIds)) {
            return collect([]);
        }

        return DB::table('tbl_programme_centers as pc')
            ->join('tbl_programme as p', 'p.programme_id', '=', 'pc.programme_id')
            ->whereNull('p.deleted_at')
            ->select('p.programme_id', 'p.programme_name')
            ->whereIn('pc.session_id', $sessionIds)
            ->whereIn('pc.center_id', $centerIds)
            ->distinct()
            ->orderBy('p.programme_name')
            ->get();
    }

    private function getTechnologyList(array $sessionIds = [], array $programmeIds = [])
    {
        if (empty($sessionIds) || empty($programmeIds)) {
            return collect([]);
        }

        return DB::table('tbl_technology')
            ->select('technology_id', 'technology_name')
            ->whereIn('session_id', $sessionIds)
            ->whereIn('programme_id', $programmeIds)
            ->distinct()
            ->orderBy('technology_name')
            ->get();
    }

    private function getBatchList(array $sessionIds = [], array $programmeIds = [], array $technologyIds = [])
    {
        if (empty($sessionIds) || empty($programmeIds) || empty($technologyIds)) {
            return collect([]);
        }

        return DB::table('tbl_batch')
            ->select('batch_id', 'batch_name')
            ->whereIn('session_id', $sessionIds)
            ->whereIn('programme_id', $programmeIds)
            ->whereIn('technology_id', $technologyIds)
            ->distinct()
            ->orderBy('batch_name')
            ->get();
    }

    private function applyViewFilters(
        $query,
        ?string $fromDate,
        ?string $toDate,
        array $sessionIds = [],
        array $centerIds = [],
        array $programmeIds = [],
        array $technologyIds = [],
        array $batchIds = [],
        string $searchStudent = ''
    ) {
        if (!empty($fromDate)) {
            $query->whereDate('a.attend_date', '>=', $fromDate);
        }

        if (!empty($toDate)) {
            $query->whereDate('a.attend_date', '<=', $toDate);
        }

        if (!empty($sessionIds)) {
            $query->whereIn('a.attend_session', $sessionIds);
        }

        if (!empty($centerIds)) {
            $query->whereIn('a.attend_center', $centerIds);
        }

        if (!empty($programmeIds)) {
            $query->whereIn('a.attend_programme', $programmeIds);
        }

        if (!empty($technologyIds)) {
            $query->whereIn('a.attend_technology', $technologyIds);
        }

        if (!empty($batchIds)) {
            $query->whereIn('a.attend_batch', $batchIds);
        }

        if ($searchStudent !== '') {
            $query->where(function ($q) use ($searchStudent) {
                $q->where('s.stu_regid', 'like', "%{$searchStudent}%")
                    ->orWhere('s.stu_name', 'like', "%{$searchStudent}%")
                    ->orWhere('s.stu_college', 'like', "%{$searchStudent}%")
                    ->orWhere('s.stu_mobile', 'like', "%{$searchStudent}%");
            });
        }

        return $query;
    }

    public function viewAttendance(Request $request)
    {
        $defaultToDate = now()->format('Y-m-d');
        $defaultFromDate = now()->subDays(12)->format('Y-m-d');

        $fromDate = $request->input('from_date', $defaultFromDate);
        $toDate = $request->input('to_date', $defaultToDate);

        if (!empty($fromDate) && !empty($toDate) && $fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $sessionIds    = $this->arr($request->input('session_ids'));
        $centerIds     = $this->arr($request->input('center_ids'));
        $programmeIds  = $this->arr($request->input('programme_ids'));
        $technologyIds = $this->arr($request->input('technology_ids'));
        $batchIds      = $this->arr($request->input('batch_ids'));
        $searchStudent = trim((string) $request->input('search_student', ''));

        $sessions = DB::table('tbl_session')
            ->select('session_id', 'session_name')
            ->orderByDesc('session_id')
            ->get();

        $centers = $this->getCenterList($sessionIds);
        $programmes = $this->getProgrammeList($sessionIds, $centerIds);
        $technologies = $this->getTechnologyList($sessionIds, $programmeIds);
        $batches = $this->getBatchList($sessionIds, $programmeIds, $technologyIds);

        $baseAttendanceQuery = DB::table('tbl_attendance as a')
            ->join('tbl_student as s', 's.stu_id', '=', 'a.attend_student')
            ->select(
                'a.attend_id',
                'a.attend_date',
                'a.attendance',
                'a.attend_student',
                's.stu_id',
                's.stu_regid',
                's.stu_name',
                's.stu_college',
                's.stu_mobile'
            );

        $this->applyViewFilters(
            $baseAttendanceQuery,
            $fromDate,
            $toDate,
            $sessionIds,
            $centerIds,
            $programmeIds,
            $technologyIds,
            $batchIds,
            $searchStudent
        );

        $dateColumns = (clone $baseAttendanceQuery)
            ->select('a.attend_date')
            ->distinct()
            ->orderByDesc('a.attend_date')
            ->pluck('a.attend_date')
            ->map(function ($date) {
                return date('Y-m-d', strtotime($date));
            })
            ->values()
            ->toArray();

        $attendanceDays = count($dateColumns);

        $studentRows = (clone $baseAttendanceQuery)
            ->select(
                's.stu_id',
                's.stu_regid',
                's.stu_name',
                's.stu_college',
                's.stu_mobile'
            )
            ->distinct()
            ->orderBy('s.stu_name')
            ->get();

        $attendanceRows = (clone $baseAttendanceQuery)
            ->select(
                'a.attend_id',
                'a.attend_date',
                'a.attendance',
                'a.attend_student'
            )
            ->orderByDesc('a.attend_date')
            ->get();

        $attendanceMap = [];
        $dateSummary = [];
        $overallPresent = 0;
        $overallAbsent = 0;

        foreach ($dateColumns as $dateKey) {
            $dateSummary[$dateKey] = [
                'present' => 0,
                'absent'  => 0,
            ];
        }

        foreach ($attendanceRows as $row) {
            $dateKey = date('Y-m-d', strtotime($row->attend_date));

            $attendanceMap[$row->attend_student][$dateKey] = [
                'attend_id'  => $row->attend_id,
                'attendance' => $row->attendance,
            ];

            if (!isset($dateSummary[$dateKey])) {
                $dateSummary[$dateKey] = ['present' => 0, 'absent' => 0];
            }

            if ($row->attendance === 'P') {
                $dateSummary[$dateKey]['present']++;
                $overallPresent++;
            } else {
                $dateSummary[$dateKey]['absent']++;
                $overallAbsent++;
            }
        }

        foreach ($studentRows as $student) {
            $presentCount = 0;

            foreach ($dateColumns as $dateKey) {
                $cell = $attendanceMap[$student->stu_id][$dateKey] ?? null;
                if ($cell && $cell['attendance'] === 'P') {
                    $presentCount++;
                }
            }

            $student->present_count = $presentCount;
            $student->average_present = $attendanceDays > 0
                ? round(($presentCount / $attendanceDays) * 100, 2)
                : 0;
        }

        $totalStudents = $studentRows->count();

        return view('Admin.Attendance.view_attendance', compact(
            'sessions',
            'centers',
            'programmes',
            'technologies',
            'batches',
            'fromDate',
            'toDate',
            'sessionIds',
            'centerIds',
            'programmeIds',
            'technologyIds',
            'batchIds',
            'searchStudent',
            'dateColumns',
            'dateSummary',
            'studentRows',
            'attendanceMap',
            'attendanceDays',
            'overallPresent',
            'overallAbsent',
            'totalStudents'
        ));
    }

    public function toggleAttendanceCell(Request $request)
    {
        $request->validate([
            'attend_id' => 'required|integer'
        ]);

        $attendance = DB::table('tbl_attendance')
            ->where('attend_id', $request->attend_id)
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance record not found.'
            ], 404);
        }

        $newStatus = $attendance->attendance === 'P' ? 'A' : 'P';

        DB::table('tbl_attendance')
            ->where('attend_id', $attendance->attend_id)
            ->update([
                'attendance' => $newStatus,
                'attend_by'  => (string) (auth()->id() ?? $attendance->attend_by),
                'added_date' => now()->format('Y-m-d'),
                'added_time' => now()->format('H:i:s'),
            ]);

        return response()->json([
            'status'     => true,
            'message'    => 'Attendance updated successfully.',
            'new_status' => $newStatus,
            'attend_id'  => $attendance->attend_id,
        ]);
    }
}
