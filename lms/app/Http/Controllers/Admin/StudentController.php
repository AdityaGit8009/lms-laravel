<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Session;
use App\Models\Programme;
use App\Models\Technology;
use App\Models\Batch;
use App\Models\StudentSession;
use App\Models\StudentProgramme;
use App\Models\StudentTechnology;
use App\Models\StudentBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\User;

class StudentController extends Controller
{
  public function students(): View
  {
    $courses = DB::table('tbl_courses')
      ->orderBy('course_name', 'asc')
      ->get();

    $programmes = DB::table('tbl_programme')
      ->orderBy('programme_name', 'asc')
      ->get();

    $centers = DB::table('tbl_centers')
      ->orderBy('center_name', 'asc')
      ->get();

    $sessions = DB::table('tbl_session')
      ->orderByRaw("
                CASE
                    WHEN status = 'current' THEN 1
                    WHEN status = 'upcomming' THEN 2
                    ELSE 3
                END
            ")
      ->orderBy('session_name', 'asc')
      ->get();

    return view('Admin.Students.index', compact('courses', 'programmes', 'centers', 'sessions'));
  }

  public function store(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'stu_name'        => ['required', 'string', 'max:200'],
      'stu_regid'       => ['required', 'string', 'max:50', 'unique:tbl_student,stu_regid'],
      'stu_college'     => ['required', 'string', 'max:255'],
      'center_id'       => ['required', 'exists:tbl_centers,center_id'],
      'stu_course'      => ['required', 'exists:tbl_courses,course_id'],
      'stu_course_year' => ['required', 'string', 'max:30'],
      'stu_branch'      => ['required', 'exists:tbl_branches,branch_id'],
      'stu_mobile'      => ['required', 'digits:10', 'unique:tbl_student,stu_mobile', 'unique:users,phone'],
      'stu_email'       => ['required', 'email', 'max:100', 'unique:tbl_student,stu_email', 'unique:users,email'],
      'stu_gender'      => ['required', Rule::in(['Male', 'Female', 'Other'])],
      'stu_pro'         => ['required', 'exists:tbl_programme,programme_id'],
      'stu_pic'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ], [
      'stu_regid.unique'  => 'Registration ID already exists.',
      'stu_mobile.unique' => 'Mobile number already exists.',
      'stu_email.unique'  => 'Email already exists.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => $validator->errors(),
      ], 422);
    }

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $currentSessionId = DB::table('tbl_session')
      ->where('status', 'current')
      ->value('session_id');

    if (!$currentSessionId) {
      return response()->json([
        'status'  => false,
        'message' => 'Current session not found.',
      ], 422);
    }

    $defaultPassword = 'Password@123';
    $hashedPassword  = Hash::make($defaultPassword);

    $imageName = null;
    if ($request->hasFile('stu_pic')) {
      $image = $request->file('stu_pic');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
      $image->move(public_path('uploads/students'), $imageName);
    }

    DB::beginTransaction();

    try {
      // users table entry
      $user = User::create([
        'name'              => $request->stu_name,
        'email'             => $request->stu_email,
        'phone'             => $request->stu_mobile,
        'role'              => 'student',
        'is_block'          => 'N',
        'email_verified_at' => now(),
        'password'          => $hashedPassword,
        'image'             => $imageName,
      ]);

      // student table entry
      Student::create([
        'stu_role'        => 4,
        'stu_name'        => $request->stu_name,
        'stu_regid'       => $request->stu_regid,
        'stu_college'     => $request->stu_college,
        'center_id'       => $request->center_id,
        'stu_course'      => $request->stu_course,
        'stu_course_year' => $request->stu_course_year,
        'stu_branch'      => $request->stu_branch,
        'stu_mobile'      => $request->stu_mobile,
        'stu_email'       => $request->stu_email,
        'stu_password'    => $hashedPassword,
        'stu_gender'      => $request->stu_gender,
        'stu_pic'         => $imageName,
        'stu_pro'         => $request->stu_pro,
        'stu_session'     => $currentSessionId,
        'del_status'      => 'N',
        'is_block'        => 'N',
        'created_by'      => $userName,
        'updated_by'      => $userName,
        // agar tbl_student me user_id column ho to ye bhi add kar do:
        // 'user_id'       => $user->id,
      ]);

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'Student added successfully. Login Email: ' . $request->stu_email . ' | Password: ' . $defaultPassword,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while creating student and login account.',
        'error'   => config('app.debug') ? $e->getMessage() : null,
      ], 500);
    }
  }

  public function update(Request $request, $id): JsonResponse
  {
    $student = Student::findOrFail($id);

    $validator = Validator::make($request->all(), [
      'stu_name'        => ['required', 'string', 'max:200'],
      'stu_regid'       => ['required', 'string', 'max:50', Rule::unique('tbl_student', 'stu_regid')->ignore($student->stu_id, 'stu_id')],
      'stu_college'     => ['required', 'string', 'max:255'],
      'center_id'       => ['required', 'exists:tbl_centers,center_id'],
      'stu_course'      => ['required', 'exists:tbl_courses,course_id'],
      'stu_course_year' => ['required', 'string', 'max:30'],
      'stu_branch'      => ['required', 'exists:tbl_branches,branch_id'],
      'stu_mobile'      => ['required', 'digits:10', Rule::unique('tbl_student', 'stu_mobile')->ignore($student->stu_id, 'stu_id')],
      'stu_email'       => ['required', 'email', 'max:100', Rule::unique('tbl_student', 'stu_email')->ignore($student->stu_id, 'stu_id')],
      'stu_gender'      => ['required', Rule::in(['Male', 'Female', 'Other'])],
      'stu_pro'         => ['required', 'exists:tbl_programme,programme_id'],
      'stu_pic'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ], [
      'stu_regid.unique'  => 'Registration ID already exists.',
      'stu_mobile.unique' => 'Mobile number already exists.',
      'stu_email.unique'  => 'Email already exists.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => $validator->errors(),
      ], 422);
    }

    $existingUserByEmail = User::where('email', $request->stu_email)
      ->where('email', '!=', $student->stu_email)
      ->exists();

    if ($existingUserByEmail) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => [
          'stu_email' => ['Email already exists in users table.']
        ],
      ], 422);
    }

    $existingUserByPhone = User::where('phone', $request->stu_mobile)
      ->where('email', '!=', $student->stu_email)
      ->exists();

    if ($existingUserByPhone) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => [
          'stu_mobile' => ['Mobile number already exists in users table.']
        ],
      ], 422);
    }

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $imageName = $student->stu_pic;
    if ($request->hasFile('stu_pic')) {
      $image = $request->file('stu_pic');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
      $image->move(public_path('uploads/students'), $imageName);
    }

    DB::beginTransaction();

    try {
      $oldEmail = $student->stu_email;

      $student->update([
        'stu_name'        => $request->stu_name,
        'stu_regid'       => $request->stu_regid,
        'stu_college'     => $request->stu_college,
        'center_id'       => $request->center_id,
        'stu_course'      => $request->stu_course,
        'stu_course_year' => $request->stu_course_year,
        'stu_branch'      => $request->stu_branch,
        'stu_mobile'      => $request->stu_mobile,
        'stu_email'       => $request->stu_email,
        'stu_gender'      => $request->stu_gender,
        'stu_pic'         => $imageName,
        'stu_pro'         => $request->stu_pro,
        'updated_by'      => $userName,
      ]);

      User::where('email', $oldEmail)->update([
        'name'       => $request->stu_name,
        'email'      => $request->stu_email,
        'phone'      => $request->stu_mobile,
        'image'      => $imageName,
        'updated_at' => now(),
      ]);

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'Student updated successfully.',
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while updating student and login account.',
        'error'   => config('app.debug') ? $e->getMessage() : null,
      ], 500);
    }
  }

  public function destroy($id): JsonResponse
  {
    $student = Student::findOrFail($id);

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $student->update([
      'del_status' => 'Y',
      'updated_by' => $userName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Student deleted successfully.',
    ]);
  }

  public function toggleBlock($id): JsonResponse
  {
    $student = Student::findOrFail($id);

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $newStatus = $student->is_block === 'Y' ? 'N' : 'Y';

    DB::beginTransaction();

    try {
      $student->update([
        'is_block'   => $newStatus,
        'updated_by' => $userName,
      ]);

      User::where('email', $student->stu_email)->update([
        'is_block'   => $newStatus,
        'updated_at' => now(),
      ]);

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => $newStatus === 'Y'
          ? 'Student blocked successfully.'
          : 'Student unblocked successfully.',
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Failed to update block status.',
        'error'   => config('app.debug') ? $e->getMessage() : null,
      ], 500);
    }
  }

  public function assignPage($id): View
  {
    $student = Student::where('stu_id', $id)
      ->where('del_status', 'N')
      ->firstOrFail();

    $sessions = Session::orderByRaw("
                CASE 
                  WHEN status = 'current' THEN 1
                  WHEN status = 'upcomming' THEN 2
                  ELSE 3
                END
            ")
      ->orderBy('session_name', 'asc')
      ->get();

    $selectedSessions = StudentSession::where('stu_id', $id)->pluck('session_id')->map(fn($v) => (int) $v)->toArray();
    $selectedProgrammes = StudentProgramme::where('stu_id', $id)->pluck('programme_id')->map(fn($v) => (int) $v)->toArray();
    $selectedTechnologies = StudentTechnology::where('stu_id', $id)->pluck('technology_id')->map(fn($v) => (int) $v)->toArray();
    $selectedBatches = StudentBatch::where('stu_id', $id)->pluck('batch_id')->map(fn($v) => (int) $v)->toArray();

    return view('Admin.Students.assign', compact(
      'student',
      'sessions',
      'selectedSessions',
      'selectedProgrammes',
      'selectedTechnologies',
      'selectedBatches'
    ));
  }

  public function getAssignFilterData(Request $request): JsonResponse
  {
    $sessionIds = array_values(array_unique(array_map('intval', $request->session_ids ?? [])));
    $programmeIds = array_values(array_unique(array_map('intval', $request->programme_ids ?? [])));
    $technologyIds = array_values(array_unique(array_map('intval', $request->technology_ids ?? [])));

    $programmes = [];
    $technologies = [];
    $batches = [];

    if (!empty($sessionIds)) {
      $programmes = Programme::whereIn('session_id', $sessionIds)
        ->orderBy('programme_name', 'asc')
        ->get(['programme_id', 'session_id', 'programme_name', 'duration']);
    }

    if (!empty($programmeIds)) {
      $technologies = Technology::whereIn('programme_id', $programmeIds)
        ->orderBy('technology_name', 'asc')
        ->get(['technology_id', 'session_id', 'programme_id', 'technology_name']);
    }

    if (!empty($technologyIds)) {
      $batches = Batch::whereIn('technology_id', $technologyIds)
        ->orderBy('batch_name', 'asc')
        ->get(['batch_id', 'session_id', 'programme_id', 'technology_id', 'batch_name']);
    }

    return response()->json([
      'status'       => true,
      'programmes'   => $programmes,
      'technologies' => $technologies,
      'batches'      => $batches,
    ]);
  }

  public function saveAssignment(Request $request, $id): JsonResponse
  {
    $student = Student::where('stu_id', $id)
      ->where('del_status', 'N')
      ->firstOrFail();

    $request->validate([
      'session_ids'      => ['nullable', 'array'],
      'session_ids.*'    => ['integer', 'exists:tbl_session,session_id'],
      'programme_ids'    => ['nullable', 'array'],
      'programme_ids.*'  => ['integer', 'exists:tbl_programme,programme_id'],
      'technology_ids'   => ['nullable', 'array'],
      'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
      'batch_ids'        => ['nullable', 'array'],
      'batch_ids.*'      => ['integer', 'exists:tbl_batch,batch_id'],
    ]);

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $sessionIds = array_values(array_unique(array_map('intval', $request->session_ids ?? [])));
    $programmeIds = array_values(array_unique(array_map('intval', $request->programme_ids ?? [])));
    $technologyIds = array_values(array_unique(array_map('intval', $request->technology_ids ?? [])));
    $batchIds = array_values(array_unique(array_map('intval', $request->batch_ids ?? [])));

    if (!empty($programmeIds)) {
      $programmeIds = Programme::whereIn('programme_id', $programmeIds)
        ->whereIn('session_id', $sessionIds)
        ->pluck('programme_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    if (!empty($technologyIds)) {
      $technologyIds = Technology::whereIn('technology_id', $technologyIds)
        ->whereIn('programme_id', $programmeIds)
        ->pluck('technology_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    if (!empty($batchIds)) {
      $batchIds = Batch::whereIn('batch_id', $batchIds)
        ->whereIn('technology_id', $technologyIds)
        ->pluck('batch_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    DB::beginTransaction();

    try {
      StudentSession::where('stu_id', $id)->delete();
      foreach ($sessionIds as $sessionId) {
        StudentSession::create([
          'stu_id'     => $id,
          'session_id' => $sessionId,
          'created_by' => $userName,
          'updated_by' => $userName,
        ]);
      }

      StudentProgramme::where('stu_id', $id)->delete();
      foreach ($programmeIds as $programmeId) {
        StudentProgramme::create([
          'stu_id'       => $id,
          'programme_id' => $programmeId,
          'created_by'   => $userName,
          'updated_by'   => $userName,
        ]);
      }

      StudentTechnology::where('stu_id', $id)->delete();
      foreach ($technologyIds as $technologyId) {
        StudentTechnology::create([
          'stu_id'        => $id,
          'technology_id' => $technologyId,
          'created_by'    => $userName,
          'updated_by'    => $userName,
        ]);
      }

      StudentBatch::where('stu_id', $id)->delete();
      foreach ($batchIds as $batchId) {
        StudentBatch::create([
          'stu_id'     => $id,
          'batch_id'   => $batchId,
          'created_by' => $userName,
          'updated_by' => $userName,
        ]);
      }

      $student->update([
        'updated_by' => $userName,
      ]);

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'Assign successful.',
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while saving assignment.',
      ], 500);
    }
  }

  public function bulkAssign(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'session_ids'      => ['required', 'array', 'min:1'],
      'session_ids.*'    => ['integer', 'exists:tbl_session,session_id'],
      'programme_ids'    => ['required', 'array', 'min:1'],
      'programme_ids.*'  => ['integer', 'exists:tbl_programme,programme_id'],
      'technology_ids'   => ['required', 'array', 'min:1'],
      'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
      'batch_ids'        => ['required', 'array', 'min:1'],
      'batch_ids.*'      => ['integer', 'exists:tbl_batch,batch_id'],
      'csv_file'         => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
    ], [
      'csv_file.required' => 'CSV file is required.',
      'csv_file.mimes'    => 'Only CSV file is allowed.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => $validator->errors(),
      ], 422);
    }

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $sessionIds = array_values(array_unique(array_map('intval', $request->session_ids ?? [])));
    $programmeIds = array_values(array_unique(array_map('intval', $request->programme_ids ?? [])));
    $technologyIds = array_values(array_unique(array_map('intval', $request->technology_ids ?? [])));
    $batchIds = array_values(array_unique(array_map('intval', $request->batch_ids ?? [])));

    if (!empty($programmeIds)) {
      $programmeIds = Programme::whereIn('programme_id', $programmeIds)
        ->whereIn('session_id', $sessionIds)
        ->pluck('programme_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    if (!empty($technologyIds)) {
      $technologyIds = Technology::whereIn('technology_id', $technologyIds)
        ->whereIn('programme_id', $programmeIds)
        ->pluck('technology_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    if (!empty($batchIds)) {
      $batchIds = Batch::whereIn('batch_id', $batchIds)
        ->whereIn('technology_id', $technologyIds)
        ->pluck('batch_id')
        ->map(fn($v) => (int) $v)
        ->toArray();
    }

    $file = $request->file('csv_file');
    $rows = array_map('str_getcsv', file($file->getRealPath()));

    if (empty($rows) || count($rows) < 2) {
      return response()->json([
        'status'  => false,
        'message' => 'CSV file is empty or invalid.',
      ], 422);
    }

    $headers = array_map(function ($header) {
      return strtolower(trim($header));
    }, $rows[0]);

    $mobileIndex = null;
    foreach (['mobile', 'stu_mobile', 'mobile_number', 'phone'] as $col) {
      $idx = array_search($col, $headers, true);
      if ($idx !== false) {
        $mobileIndex = $idx;
        break;
      }
    }

    if ($mobileIndex === null) {
      return response()->json([
        'status'  => false,
        'message' => 'CSV must contain mobile column.',
      ], 422);
    }

    $mobiles = [];
    foreach (array_slice($rows, 1) as $row) {
      if (!isset($row[$mobileIndex])) {
        continue;
      }

      $value = preg_replace('/\D+/', '', trim($row[$mobileIndex]));
      if ($value !== '') {
        $mobiles[] = $value;
      }
    }

    $mobiles = array_values(array_unique($mobiles));

    if (empty($mobiles)) {
      return response()->json([
        'status'  => false,
        'message' => 'No mobile numbers found in CSV.',
      ], 422);
    }

    $students = Student::whereIn('stu_mobile', $mobiles)
      ->where('del_status', 'N')
      ->get(['stu_id', 'stu_mobile']);

    if ($students->isEmpty()) {
      return response()->json([
        'status'  => false,
        'message' => 'No matching students found for uploaded mobile numbers.',
      ], 422);
    }

    DB::beginTransaction();

    try {
      foreach ($students as $student) {
        StudentSession::where('stu_id', $student->stu_id)->delete();
        foreach ($sessionIds as $sessionId) {
          StudentSession::create([
            'stu_id'     => $student->stu_id,
            'session_id' => $sessionId,
            'created_by' => $userName,
            'updated_by' => $userName,
          ]);
        }

        StudentProgramme::where('stu_id', $student->stu_id)->delete();
        foreach ($programmeIds as $programmeId) {
          StudentProgramme::create([
            'stu_id'       => $student->stu_id,
            'programme_id' => $programmeId,
            'created_by'   => $userName,
            'updated_by'   => $userName,
          ]);
        }

        StudentTechnology::where('stu_id', $student->stu_id)->delete();
        foreach ($technologyIds as $technologyId) {
          StudentTechnology::create([
            'stu_id'        => $student->stu_id,
            'technology_id' => $technologyId,
            'created_by'    => $userName,
            'updated_by'    => $userName,
          ]);
        }

        StudentBatch::where('stu_id', $student->stu_id)->delete();
        foreach ($batchIds as $batchId) {
          StudentBatch::create([
            'stu_id'     => $student->stu_id,
            'batch_id'   => $batchId,
            'created_by' => $userName,
            'updated_by' => $userName,
          ]);
        }

        Student::where('stu_id', $student->stu_id)->update([
          'updated_by' => $userName,
        ]);
      }

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'Bulk assign completed successfully for ' . $students->count() . ' student(s).',
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while bulk assigning students.',
      ], 500);
    }
  }

  public function getBranchesByCourse($courseId): JsonResponse
  {
    $branches = DB::table('tbl_branches')
      ->where('course_id', $courseId)
      ->orderBy('branch_name', 'asc')
      ->get(['branch_id', 'branch_name']);

    return response()->json([
      'status'   => true,
      'branches' => $branches,
    ]);
  }

  public function getYearsByCourse($id): JsonResponse
  {
    $course = Course::where('course_id', $id)->first();

    if (!$course) {
      return response()->json([
        'status'  => false,
        'message' => 'Course not found.',
        'years'   => [],
      ], 404);
    }

    $duration = (int) $course->duration;
    $years = [];

    for ($i = 1; $i <= $duration; $i++) {
      $years[] = $this->formatYear($i);
    }

    return response()->json([
      'status' => true,
      'years'  => $years,
    ]);
  }

  private function formatYear(int $year): string
  {
    if ($year === 1) return '1st Year';
    if ($year === 2) return '2nd Year';
    if ($year === 3) return '3rd Year';
    return $year . 'th Year';
  }

  public function data(Request $request): JsonResponse
  {
    $draw   = (int) $request->input('draw', 1);
    $start  = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);
    $search = trim($request->input('search.value', ''));

    $baseQuery = DB::table('tbl_student as s')
      ->leftJoin('tbl_courses as c', 'c.course_id', '=', 's.stu_course')
      ->leftJoin('tbl_branches as b', 'b.branch_id', '=', 's.stu_branch')
      ->leftJoin('tbl_programme as p', 'p.programme_id', '=', 's.stu_pro')
      ->leftJoin('tbl_centers as ct', 'ct.center_id', '=', 's.center_id')
      ->select(
        's.stu_id',
        's.stu_name',
        's.stu_regid',
        's.stu_college',
        's.stu_course',
        's.stu_course_year',
        's.stu_branch',
        's.stu_mobile',
        's.stu_email',
        's.stu_gender',
        's.stu_pic',
        's.stu_pro',
        's.center_id',
        's.is_block',
        'c.course_name',
        'b.branch_name',
        'p.programme_name',
        'ct.center_name'
      )
      ->where('s.del_status', 'N');

    $recordsTotal = (clone $baseQuery)->count('s.stu_id');

    if ($search !== '') {
      $baseQuery->where(function ($q) use ($search) {
        $q->where('s.stu_name', 'like', "%{$search}%")
          ->orWhere('s.stu_mobile', 'like', "%{$search}%")
          ->orWhere('s.stu_email', 'like', "%{$search}%")
          ->orWhere('s.stu_regid', 'like', "%{$search}%")
          ->orWhere('s.stu_college', 'like', "%{$search}%")
          ->orWhere('ct.center_name', 'like', "%{$search}%")
          ->orWhere('c.course_name', 'like', "%{$search}%")
          ->orWhere('b.branch_name', 'like', "%{$search}%")
          ->orWhere('p.programme_name', 'like', "%{$search}%")
          ->orWhere('s.stu_course_year', 'like', "%{$search}%")
          ->orWhere('s.stu_gender', 'like', "%{$search}%");
      });
    }

    $recordsFiltered = (clone $baseQuery)->count('s.stu_id');

    $students = $baseQuery
      ->orderByRaw("CASE WHEN s.is_block = 'Y' THEN 1 ELSE 0 END ASC")
      ->orderByDesc('s.stu_id')
      ->offset($start)
      ->limit($length)
      ->get();

    $data = [];
    $srNo = $start + 1;

    foreach ($students as $student) {
      $img = !empty($student->stu_pic)
        ? asset('uploads/students/' . $student->stu_pic)
        : asset('images/default-user.png');

      $actionHtml = '
                <div class="dropdown action-dropdown">
                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item editStudentBtn"
                                data-id="' . e($student->stu_id) . '"
                                data-name="' . e($student->stu_name) . '"
                                data-regid="' . e($student->stu_regid) . '"
                                data-college="' . e($student->stu_college) . '"
                                data-center="' . e($student->center_id) . '"
                                data-course="' . e($student->stu_course) . '"
                                data-year="' . e($student->stu_course_year) . '"
                                data-branch="' . e($student->stu_branch) . '"
                                data-email="' . e($student->stu_email) . '"
                                data-mobile="' . e($student->stu_mobile) . '"
                                data-gender="' . e($student->stu_gender) . '"
                                data-programme="' . e($student->stu_pro) . '">
                                <i class="bi bi-pencil-square me-2"></i> Update
                            </a>
                        </li>
                        <li>
                            <a href="' . route('students.assign.page', $student->stu_id) . '" class="dropdown-item">
                                <i class="bi bi-person-check me-2"></i> Assign
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item blockStudentBtn" data-id="' . e($student->stu_id) . '">
                                <i class="bi bi-slash-circle me-2"></i>
                                ' . ($student->is_block === 'Y' ? 'Unblock' : 'Block') . '
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item text-danger deleteStudentBtn" data-id="' . e($student->stu_id) . '">
                                <i class="bi bi-trash me-2"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>';

      $data[] = [
        'DT_RowClass' => $student->is_block === 'Y' ? 'blocked-row' : '',
        0 => $srNo++,
        1 => '<img src="' . $img . '" width="45" height="45" style="object-fit:cover;border-radius:50%;">',
        2 => '<div class="student-inline-info">
            <strong>' . e($student->stu_name) . '</strong><br>
            <span>' . e($student->stu_email) . '</span><br>
            <span>' . e($student->stu_mobile) . '</span>
          </div>',
        3 => e($student->stu_regid),
        4 => e($student->stu_college),
        5 => e($student->center_name ?? '-'),
        6 => e($student->course_name ?? '-'),
        7 => e($student->stu_course_year),
        8 => e($student->branch_name ?? '-'),
        9 => e($student->stu_gender),
        10 => e($student->programme_name ?? '-'),
        11 => $actionHtml,
      ];
    }

    return response()->json([
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data,
    ]);
  }


  public function downloadBulkAssignTemplate()
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=bulk_assign_mobile_template.csv',
    ];

    $callback = function () {
      $file = fopen('php://output', 'w');
      fputcsv($file, ['mobile']);
      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  public function bulkUpload(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'center_id'        => ['required', 'exists:tbl_centers,center_id'],
      'stu_course'       => ['required', 'exists:tbl_courses,course_id'],
      'stu_course_year'  => ['required', 'string', 'max:30'],
      'csv_file'         => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
    ], [
      'csv_file.required' => 'CSV file is required.',
      'csv_file.mimes'    => 'Only CSV file is allowed.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => $validator->errors(),
      ], 422);
    }

    $authUser = Auth::user();
    $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

    $currentSessionId = DB::table('tbl_session')
      ->where('status', 'current')
      ->value('session_id');

    if (!$currentSessionId) {
      return response()->json([
        'status'  => false,
        'message' => 'Current session not found.',
      ], 422);
    }

    $file = $request->file('csv_file');
    $rows = array_map('str_getcsv', file($file->getRealPath()));

    if (empty($rows) || count($rows) < 2) {
      return response()->json([
        'status'  => false,
        'message' => 'CSV file is empty or invalid.',
      ], 422);
    }

    $headers = array_map(fn($header) => strtolower(trim($header)), $rows[0]);

    $requiredColumns = [
      'stu_name',
      'stu_regid',
      'stu_college',
      'branch_name',
      'stu_mobile',
      'stu_email',
      'stu_gender',
    ];

    foreach ($requiredColumns as $column) {
      if (!in_array($column, $headers, true)) {
        return response()->json([
          'status'  => false,
          'message' => "CSV must contain column: {$column}",
        ], 422);
      }
    }

    $columnIndex = array_flip($headers);
    $defaultPassword = 'Password@123';
    $hashedPassword  = Hash::make($defaultPassword);

    $insertedCount = 0;
    $skippedRows = [];

    DB::beginTransaction();

    try {
      foreach (array_slice($rows, 1) as $rowNumber => $row) {
        $excelRow = $rowNumber + 2;

        $stuName       = trim($row[$columnIndex['stu_name']] ?? '');
        $stuRegid      = trim($row[$columnIndex['stu_regid']] ?? '');
        $stuCollege    = trim($row[$columnIndex['stu_college']] ?? '');
        $branchName    = trim($row[$columnIndex['branch_name']] ?? '');
        $stuMobile     = preg_replace('/\D+/', '', trim($row[$columnIndex['stu_mobile']] ?? ''));
        $stuEmail      = trim($row[$columnIndex['stu_email']] ?? '');
        $stuGender     = trim($row[$columnIndex['stu_gender']] ?? '');

        if (
          $stuName === '' || $stuRegid === '' || $stuCollege === '' ||
          $branchName === '' || $stuMobile === '' || $stuEmail === '' ||
          $stuGender === ''
        ) {
          $skippedRows[] = "Row {$excelRow}: Missing required data.";
          continue;
        }

        if (!filter_var($stuEmail, FILTER_VALIDATE_EMAIL)) {
          $skippedRows[] = "Row {$excelRow}: Invalid email format.";
          continue;
        }

        if (!in_array($stuGender, ['Male', 'Female', 'Other'], true)) {
          $skippedRows[] = "Row {$excelRow}: Gender must be Male, Female or Other.";
          continue;
        }

        if (strlen($stuMobile) !== 10) {
          $skippedRows[] = "Row {$excelRow}: Mobile must be 10 digits.";
          continue;
        }

        $studentExists = Student::where('stu_regid', $stuRegid)
          ->orWhere('stu_mobile', $stuMobile)
          ->orWhere('stu_email', $stuEmail)
          ->exists();

        $userExists = User::where('email', $stuEmail)
          ->orWhere('phone', $stuMobile)
          ->exists();

        if ($studentExists || $userExists) {
          $skippedRows[] = "Row {$excelRow}: Student already exists.";
          continue;
        }

        $user = User::create([
          'name'              => $stuName,
          'email'             => $stuEmail,
          'phone'             => $stuMobile,
          'role'              => 'student',
          'is_block'          => 'N',
          'email_verified_at' => now(),
          'password'          => $hashedPassword,
          'image'             => null,
        ]);

        Student::create([
          'stu_role'        => 4,
          'stu_name'        => $stuName,
          'stu_regid'       => $stuRegid,
          'stu_college'     => $stuCollege,
          'center_id'       => $request->center_id,
          'stu_course'      => $request->stu_course,
          'stu_course_year' => $request->stu_course_year,
          'stu_branch'      => $branch->branch_id,
          'stu_mobile'      => $stuMobile,
          'stu_email'       => $stuEmail,
          'stu_password'    => $hashedPassword,
          'stu_gender'      => $stuGender,
          'stu_pic'         => null,
          'stu_pro'         => $programme->programme_id,
          'stu_session'     => $currentSessionId,
          'del_status'      => 'N',
          'is_block'        => 'N',
          'created_by'      => $userName,
          'updated_by'      => $userName,
          // 'user_id'      => $user->id,
        ]);

        $insertedCount++;
      }

      DB::commit();

      $message = "{$insertedCount} student(s) uploaded successfully.";
      if (!empty($skippedRows)) {
        $message .= ' Some rows were skipped: ' . implode(' | ', array_slice($skippedRows, 0, 10));
      }

      return response()->json([
        'status'        => true,
        'message'       => $message,
        'inserted_count' => $insertedCount,
        'skipped_rows'  => $skippedRows,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while uploading students.',
        'error'   => config('app.debug') ? $e->getMessage() : null,
      ], 500);
    }
  }
  public function downloadBulkUploadTemplate()
  {
    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=bulk_student_upload_template.csv',
    ];

    $callback = function () {
      $file = fopen('php://output', 'w');

      fputcsv($file, [
        'stu_name',
        'stu_regid',
        'stu_college',
        'branch_name',
        'stu_mobile',
        'stu_email',
        'stu_gender',
      ]);

      fputcsv($file, [
        'Aditya Pratap Vishwakarma',
        'aditya-7395',
        'CSJM Government Polytechnic Ambedkarnagar',
        'Computer Science',
        '7390916224',
        'aditya@example.com',
        'Male',
      ]);

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
