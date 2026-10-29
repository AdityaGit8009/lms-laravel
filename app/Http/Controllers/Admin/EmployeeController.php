<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Employee;
use App\Models\EmployeeBatch;
use App\Models\EmployeeProgramme;
use App\Models\EmployeeSession;
use App\Models\EmployeeTechnology;
use App\Models\Programme;
use App\Models\Session;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $centers = DB::table('tbl_centers')->orderBy('center_name', 'asc')->get();

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

        $totalEmployees = DB::table('tbl_employee')
            ->where('del_status', 'N')
            ->where('is_block', 'N')
            ->count();

        $internalEmployeesCount = DB::table('tbl_employee')
            ->where('del_status', 'N')
            ->where('is_block', 'N')
            ->where('emp_type', 'Internal')
            ->count();

        $externalEmployeesCount = DB::table('tbl_employee')
            ->where('del_status', 'N')
            ->where('is_block', 'N')
            ->where('emp_type', 'External')
            ->count();

        $batchSub = EmployeeBatch::query()
            ->select('emp_id', DB::raw('MIN(batch_id) as batch_id'))
            ->where('is_active', 'Y')
            ->groupBy('emp_id');

        $noBatchAssignedCount = DB::table('tbl_employee as e')
            ->leftJoinSub($batchSub, 'eb', function ($join) {
                $join->on('eb.emp_id', '=', 'e.emp_id');
            })
            ->where('e.del_status', 'N')
            ->where('e.is_block', 'N')
            ->whereNull('eb.batch_id')
            ->count();

        return view('Admin.Employees.index', compact(
            'centers',
            'sessions',
            'totalEmployees',
            'internalEmployeesCount',
            'externalEmployeesCount',
            'noBatchAssignedCount'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'emp_name' => ['required', 'string', 'max:200'],
            'emp_regid' => ['required', 'string', 'max:50', 'unique:tbl_employee,emp_regid'],
            'center_id' => ['required', 'exists:tbl_centers,center_id'],
            'emp_mobile' => ['required', 'digits:10', 'unique:tbl_employee,emp_mobile', 'unique:users,phone'],
            'emp_email' => ['required', 'email', 'max:100', 'unique:tbl_employee,emp_email', 'unique:users,email'],
            'emp_gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'emp_type' => ['required', Rule::in(['Internal', 'External'])],
            'emp_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'emp_regid.unique' => 'Employee ID already exists.',
            'emp_mobile.unique' => 'Mobile number already exists.',
            'emp_email.unique' => 'Email already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $authUser = Auth::user();
        $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

        $defaultPassword = 'Password@123';
        $hashedPassword = Hash::make($defaultPassword);

        $imageName = null;
        if ($request->hasFile('emp_pic')) {
            $image = $request->file('emp_pic');
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $image->move(public_path('uploads/employees'), $imageName);
        }

        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->emp_name,
                'email' => $request->emp_email,
                'phone' => $request->emp_mobile,
                'role' => 'employee',
                'is_block' => 'N',
                'email_verified_at' => now(),
                'password' => $hashedPassword,
                'image' => $imageName,
            ]);

            Employee::create([
                'emp_role' => 5,
                'emp_name' => $request->emp_name,
                'emp_regid' => $request->emp_regid,
                'center_id' => $request->center_id,
                'emp_mobile' => $request->emp_mobile,
                'emp_email' => $request->emp_email,
                'emp_password' => $hashedPassword,
                'emp_gender' => $request->emp_gender,
                'emp_type' => $request->emp_type,
                'emp_pic' => $imageName,
                'del_status' => 'N',
                'is_block' => 'N',
                'created_by' => $userName,
                'updated_by' => $userName,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee added successfully. Login Email: ' . $request->emp_email . ' | Password: ' . $defaultPassword,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while creating employee and login account.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'emp_name' => ['required', 'string', 'max:200'],
            'emp_regid' => ['required', 'string', 'max:50', Rule::unique('tbl_employee', 'emp_regid')->ignore($employee->emp_id, 'emp_id')],
            'center_id' => ['required', 'exists:tbl_centers,center_id'],
            'emp_mobile' => ['required', 'digits:10', Rule::unique('tbl_employee', 'emp_mobile')->ignore($employee->emp_id, 'emp_id')],
            'emp_email' => ['required', 'email', 'max:100', Rule::unique('tbl_employee', 'emp_email')->ignore($employee->emp_id, 'emp_id')],
            'emp_gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'emp_type' => ['required', Rule::in(['Internal', 'External'])],
            'emp_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingUserByEmail = User::where('email', $request->emp_email)
            ->where('email', '!=', $employee->emp_email)
            ->exists();

        if ($existingUserByEmail) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => ['emp_email' => ['Email already exists in users table.']],
            ], 422);
        }

        $existingUserByPhone = User::where('phone', $request->emp_mobile)
            ->where('email', '!=', $employee->emp_email)
            ->exists();

        if ($existingUserByPhone) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => ['emp_mobile' => ['Mobile number already exists in users table.']],
            ], 422);
        }

        $authUser = Auth::user();
        $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

        $imageName = $employee->emp_pic;
        if ($request->hasFile('emp_pic')) {
            $image = $request->file('emp_pic');
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $image->move(public_path('uploads/employees'), $imageName);
        }

        DB::beginTransaction();

        try {
            $oldEmail = $employee->emp_email;

            $employee->update([
                'emp_name' => $request->emp_name,
                'emp_regid' => $request->emp_regid,
                'center_id' => $request->center_id,
                'emp_mobile' => $request->emp_mobile,
                'emp_email' => $request->emp_email,
                'emp_gender' => $request->emp_gender,
                'emp_type' => $request->emp_type,
                'emp_pic' => $imageName,
                'updated_by' => $userName,
            ]);

            User::where('email', $oldEmail)->update([
                'name' => $request->emp_name,
                'email' => $request->emp_email,
                'phone' => $request->emp_mobile,
                'image' => $imageName,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating employee and login account.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $employee = Employee::findOrFail($id);

        $authUser = Auth::user();
        $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

        $employee->update([
            'del_status' => 'Y',
            'updated_by' => $userName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }

    public function toggleBlock($id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $authUser = Auth::user();
        $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';
        $newStatus = $employee->is_block === 'Y' ? 'N' : 'Y';

        DB::beginTransaction();
        try {
            $employee->update([
                'is_block' => $newStatus,
                'updated_by' => $userName,
            ]);

            User::where('email', $employee->emp_email)->update([
                'is_block' => $newStatus,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $newStatus === 'Y' ? 'Employee blocked successfully.' : 'Employee unblocked successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to update block status.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function assignPage($id): View
    {
        $employee = Employee::where('emp_id', $id)->where('del_status', 'N')->firstOrFail();

        $sessions = Session::orderByRaw("
                CASE
                  WHEN status = 'current' THEN 1
                  WHEN status = 'upcomming' THEN 2
                  ELSE 3
                END
            ")
            ->orderBy('session_name', 'asc')
            ->get();

        $selectedSessions = EmployeeSession::where('emp_id', $id)->pluck('session_id')->map(fn($v) => (int) $v)->toArray();
        $selectedProgrammes = EmployeeProgramme::where('emp_id', $id)->pluck('programme_id')->map(fn($v) => (int) $v)->toArray();
        $selectedTechnologies = EmployeeTechnology::where('emp_id', $id)->pluck('technology_id')->map(fn($v) => (int) $v)->toArray();
        $selectedBatches = EmployeeBatch::where('emp_id', $id)->pluck('batch_id')->map(fn($v) => (int) $v)->toArray();

        return view('Admin.Employees.assign', compact(
            'employee',
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
            'status' => true,
            'programmes' => $programmes,
            'technologies' => $technologies,
            'batches' => $batches,
        ]);
    }

    public function saveAssignment(Request $request, $id): JsonResponse
    {
        $employee = Employee::where('emp_id', $id)->where('del_status', 'N')->firstOrFail();

        $request->validate([
            'session_ids' => ['nullable', 'array'],
            'session_ids.*' => ['integer', 'exists:tbl_session,session_id'],
            'programme_ids' => ['nullable', 'array'],
            'programme_ids.*' => ['integer', 'exists:tbl_programme,programme_id'],
            'technology_ids' => ['nullable', 'array'],
            'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
            'batch_ids' => ['nullable', 'array'],
            'batch_ids.*' => ['integer', 'exists:tbl_batch,batch_id'],
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
            EmployeeSession::where('emp_id', $id)->delete();
            foreach ($sessionIds as $sessionId) {
                EmployeeSession::create([
                    'emp_id' => $id,
                    'session_id' => $sessionId,
                    'created_by' => $userName,
                    'updated_by' => $userName,
                    'is_active' => 'Y',
                ]);
            }

            EmployeeProgramme::where('emp_id', $id)->delete();
            foreach ($programmeIds as $programmeId) {
                EmployeeProgramme::create([
                    'emp_id' => $id,
                    'programme_id' => $programmeId,
                    'created_by' => $userName,
                    'updated_by' => $userName,
                    'is_active' => 'Y',
                ]);
            }

            EmployeeTechnology::where('emp_id', $id)->delete();
            foreach ($technologyIds as $technologyId) {
                EmployeeTechnology::create([
                    'emp_id' => $id,
                    'technology_id' => $technologyId,
                    'created_by' => $userName,
                    'updated_by' => $userName,
                    'is_active' => 'Y',
                ]);
            }

            EmployeeBatch::where('emp_id', $id)->delete();
            foreach ($batchIds as $batchId) {
                EmployeeBatch::create([
                    'emp_id' => $id,
                    'batch_id' => $batchId,
                    'created_by' => $userName,
                    'updated_by' => $userName,
                    'is_active' => 'Y',
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Assign successful.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while saving assignment.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function bulkAssign(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_ids' => ['required', 'array', 'min:1'],
            'session_ids.*' => ['integer', 'exists:tbl_session,session_id'],
            'programme_ids' => ['required', 'array', 'min:1'],
            'programme_ids.*' => ['integer', 'exists:tbl_programme,programme_id'],
            'technology_ids' => ['required', 'array', 'min:1'],
            'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
            'batch_ids' => ['required', 'array', 'min:1'],
            'batch_ids.*' => ['integer', 'exists:tbl_batch,batch_id'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
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
                'status' => false,
                'message' => 'CSV file is empty or invalid.',
            ], 422);
        }

        $headers = array_map(fn($header) => strtolower(trim($header)), $rows[0]);

        $mobileIndex = null;
        foreach (['mobile', 'emp_mobile', 'mobile_number', 'phone'] as $col) {
            $idx = array_search($col, $headers, true);
            if ($idx !== false) {
                $mobileIndex = $idx;
                break;
            }
        }

        if ($mobileIndex === null) {
            return response()->json([
                'status' => false,
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

        $employees = Employee::whereIn('emp_mobile', $mobiles)
            ->where('del_status', 'N')
            ->get(['emp_id']);

        if ($employees->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No matching employees found for uploaded mobile numbers.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            foreach ($employees as $employee) {
                EmployeeSession::where('emp_id', $employee->emp_id)->delete();
                foreach ($sessionIds as $sessionId) {
                    EmployeeSession::create([
                        'emp_id' => $employee->emp_id,
                        'session_id' => $sessionId,
                        'created_by' => $userName,
                        'updated_by' => $userName,
                        'is_active' => 'Y',
                    ]);
                }

                EmployeeProgramme::where('emp_id', $employee->emp_id)->delete();
                foreach ($programmeIds as $programmeId) {
                    EmployeeProgramme::create([
                        'emp_id' => $employee->emp_id,
                        'programme_id' => $programmeId,
                        'created_by' => $userName,
                        'updated_by' => $userName,
                        'is_active' => 'Y',
                    ]);
                }

                EmployeeTechnology::where('emp_id', $employee->emp_id)->delete();
                foreach ($technologyIds as $technologyId) {
                    EmployeeTechnology::create([
                        'emp_id' => $employee->emp_id,
                        'technology_id' => $technologyId,
                        'created_by' => $userName,
                        'updated_by' => $userName,
                        'is_active' => 'Y',
                    ]);
                }

                EmployeeBatch::where('emp_id', $employee->emp_id)->delete();
                foreach ($batchIds as $batchId) {
                    EmployeeBatch::create([
                        'emp_id' => $employee->emp_id,
                        'batch_id' => $batchId,
                        'created_by' => $userName,
                        'updated_by' => $userName,
                        'is_active' => 'Y',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bulk assign completed successfully for ' . $employees->count() . ' employee(s).',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while bulk assigning employees.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function bulkUpload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'center_id' => ['required', 'exists:tbl_centers,center_id'],
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $authUser = Auth::user();
        $userName = $authUser->name ?? $authUser->username ?? $authUser->email ?? 'Admin';

        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));

        if (empty($rows) || count($rows) < 2) {
            return response()->json([
                'status' => false,
                'message' => 'CSV file is empty or invalid.',
            ], 422);
        }

        $headers = array_map(fn($header) => strtolower(trim($header)), $rows[0]);
        $requiredColumns = ['emp_name', 'emp_regid', 'emp_mobile', 'emp_email', 'emp_gender', 'emp_type'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $headers, true)) {
                return response()->json([
                    'status' => false,
                    'message' => "CSV must contain column: {$column}",
                ], 422);
            }
        }

        $columnIndex = array_flip($headers);
        $defaultPassword = 'Password@123';
        $hashedPassword = Hash::make($defaultPassword);

        $insertedCount = 0;
        $skippedRows = [];

        DB::beginTransaction();

        try {
            foreach (array_slice($rows, 1) as $rowNumber => $row) {
                $excelRow = $rowNumber + 2;
                $empName = trim($row[$columnIndex['emp_name']] ?? '');
                $empRegid = trim($row[$columnIndex['emp_regid']] ?? '');
                $empMobile = preg_replace('/\D+/', '', trim($row[$columnIndex['emp_mobile']] ?? ''));
                $empEmail = trim($row[$columnIndex['emp_email']] ?? '');
                $empGender = trim($row[$columnIndex['emp_gender']] ?? '');
                $empType = trim($row[$columnIndex['emp_type']] ?? '');

                if ($empName === '' || $empRegid === '' || $empMobile === '' || $empEmail === '' || $empGender === '' || $empType === '') {
                    $skippedRows[] = "Row {$excelRow}: Missing required data.";
                    continue;
                }

                if (!filter_var($empEmail, FILTER_VALIDATE_EMAIL)) {
                    $skippedRows[] = "Row {$excelRow}: Invalid email format.";
                    continue;
                }

                if (!in_array($empGender, ['Male', 'Female', 'Other'], true)) {
                    $skippedRows[] = "Row {$excelRow}: Gender must be Male, Female or Other.";
                    continue;
                }

                if (!in_array($empType, ['Internal', 'External'], true)) {
                    $skippedRows[] = "Row {$excelRow}: Employee Type must be Internal or External.";
                    continue;
                }

                if (strlen($empMobile) !== 10) {
                    $skippedRows[] = "Row {$excelRow}: Mobile must be 10 digits.";
                    continue;
                }

                $employeeExists = Employee::where('emp_regid', $empRegid)
                    ->orWhere('emp_mobile', $empMobile)
                    ->orWhere('emp_email', $empEmail)
                    ->exists();

                $userExists = User::where('email', $empEmail)
                    ->orWhere('phone', $empMobile)
                    ->exists();

                if ($employeeExists || $userExists) {
                    $skippedRows[] = "Row {$excelRow}: Employee or login already exists.";
                    continue;
                }

                User::create([
                    'name' => $empName,
                    'email' => $empEmail,
                    'phone' => $empMobile,
                    'role' => 'employee',
                    'is_block' => 'N',
                    'email_verified_at' => now(),
                    'password' => $hashedPassword,
                    'image' => null,
                ]);

                Employee::create([
                    'emp_role' => 5,
                    'emp_name' => $empName,
                    'emp_regid' => $empRegid,
                    'center_id' => $request->center_id,
                    'emp_mobile' => $empMobile,
                    'emp_email' => $empEmail,
                    'emp_password' => $hashedPassword,
                    'emp_gender' => $empGender,
                    'emp_type' => $empType,
                    'emp_pic' => null,
                    'del_status' => 'N',
                    'is_block' => 'N',
                    'created_by' => $userName,
                    'updated_by' => $userName,
                ]);

                $insertedCount++;
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "{$insertedCount} employee(s) uploaded successfully.",
                'inserted_count' => $insertedCount,
                'skipped_rows' => $skippedRows,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while uploading employees.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function bulkUpdateRegId(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));

        if (empty($rows) || count($rows) < 2) {
            return response()->json([
                'status' => false,
                'message' => 'CSV file is empty or invalid.',
            ], 422);
        }

        $headers = array_map(fn($header) => strtolower(trim($header)), $rows[0]);
        $requiredColumns = ['emp_mobile', 'emp_regid'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $headers, true)) {
                return response()->json([
                    'status' => false,
                    'message' => "CSV must contain column: {$column}",
                ], 422);
            }
        }

        $columnIndex = array_flip($headers);
        $updatedCount = 0;
        $skippedRows = [];

        DB::beginTransaction();

        try {
            foreach (array_slice($rows, 1) as $rowNumber => $row) {
                $excelRow = $rowNumber + 2;
                $empMobile = preg_replace('/\D+/', '', trim($row[$columnIndex['emp_mobile']] ?? ''));
                $empRegid = trim($row[$columnIndex['emp_regid']] ?? '');

                if ($empMobile === '' || $empRegid === '') {
                    $skippedRows[] = "Row {$excelRow}: Missing required data.";
                    continue;
                }

                if (strlen($empMobile) !== 10) {
                    $skippedRows[] = "Row {$excelRow}: Mobile must be 10 digits.";
                    continue;
                }

                $employee = DB::table('tbl_employee')
                    ->where('emp_mobile', $empMobile)
                    ->where('del_status', 'N')
                    ->first();

                if (!$employee) {
                    $skippedRows[] = "Row {$excelRow}: Employee not found for mobile {$empMobile}.";
                    continue;
                }

                $regIdExists = DB::table('tbl_employee')
                    ->where('emp_regid', $empRegid)
                    ->where('emp_id', '!=', $employee->emp_id)
                    ->exists();

                if ($regIdExists) {
                    $skippedRows[] = "Row {$excelRow}: Employee ID already exists.";
                    continue;
                }

                DB::table('tbl_employee')
                    ->where('emp_id', $employee->emp_id)
                    ->update([
                        'emp_regid' => $empRegid,
                        'updated_at' => now(),
                    ]);

                $updatedCount++;
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "{$updatedCount} employee(s) ID updated successfully.",
                'updated_count' => $updatedCount,
                'skipped_rows' => $skippedRows,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating employee ID.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim($request->input('search.value', ''));
        $noBatchOnly = (int) $request->input('no_batch_only', 0);
        $empType = trim($request->input('emp_type', 'Internal'));

        $batchSub = EmployeeBatch::query()
            ->select('emp_id', DB::raw('MIN(batch_id) as batch_id'))
            ->where('is_active', 'Y')
            ->groupBy('emp_id');

        $baseQuery = DB::table('tbl_employee as e')
            ->leftJoin('tbl_centers as ct', 'ct.center_id', '=', 'e.center_id')
            ->leftJoinSub($batchSub, 'eb', function ($join) {
                $join->on('eb.emp_id', '=', 'e.emp_id');
            })
            ->leftJoin('tbl_batch as bt', 'bt.batch_id', '=', 'eb.batch_id')
            ->select(
                'e.emp_id',
                'e.emp_name',
                'e.emp_regid',
                'e.center_id',
                'e.emp_mobile',
                'e.emp_email',
                'e.emp_gender',
                'e.emp_type',
                'e.emp_pic',
                'e.is_block',
                'ct.center_name',
                'bt.batch_name as current_batch_name'
            )
            ->where('e.del_status', 'N')
            ->where('e.emp_type', $empType);

        if ($noBatchOnly === 1) {
            $baseQuery->where('e.is_block', 'N')->whereNull('eb.batch_id');
        }

        $recordsTotal = (clone $baseQuery)->count('e.emp_id');

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('e.emp_name', 'like', "%{$search}%")
                    ->orWhere('e.emp_mobile', 'like', "%{$search}%")
                    ->orWhere('e.emp_email', 'like', "%{$search}%")
                    ->orWhere('e.emp_regid', 'like', "%{$search}%")
                    ->orWhere('ct.center_name', 'like', "%{$search}%")
                    ->orWhere('e.emp_gender', 'like', "%{$search}%")
                    ->orWhere('e.emp_type', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count('e.emp_id');

        $employees = $baseQuery
            ->orderByRaw("CASE WHEN e.is_block = 'Y' THEN 1 ELSE 0 END ASC")
            ->orderByDesc('e.emp_id')
            ->offset($start)
            ->limit($length)
            ->get();

        $data = [];
        $srNo = $start + 1;

        foreach ($employees as $employee) {
            $img = !empty($employee->emp_pic) && file_exists(public_path('uploads/employees/' . $employee->emp_pic))
                ? asset('uploads/employees/' . $employee->emp_pic)
                : asset('assets/download.png');

            $actionHtml = '
                <div class="dropdown action-dropdown">
                    <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item editEmployeeBtn"
                                data-id="' . e($employee->emp_id) . '"
                                data-name="' . e($employee->emp_name) . '"
                                data-regid="' . e($employee->emp_regid) . '"
                                data-center="' . e($employee->center_id) . '"
                                data-email="' . e($employee->emp_email) . '"
                                data-mobile="' . e($employee->emp_mobile) . '"
                                data-gender="' . e($employee->emp_gender) . '"
                                data-type="' . e($employee->emp_type) . '">
                                <i class="bi bi-pencil-square me-2"></i> Update
                            </a>
                        </li>
                        <li>
                            <a href="' . route('employees.assign.page', $employee->emp_id) . '" class="dropdown-item">
                                <i class="bi bi-person-check me-2"></i> Assign
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item blockEmployeeBtn" data-id="' . e($employee->emp_id) . '">
                                <i class="bi bi-slash-circle me-2"></i>
                                ' . ($employee->is_block === 'Y' ? 'Unblock' : 'Block') . '
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a href="javascript:void(0)" class="dropdown-item text-danger deleteEmployeeBtn" data-id="' . e($employee->emp_id) . '">
                                <i class="bi bi-trash me-2"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>';

            $data[] = [
                'DT_RowClass' => $employee->is_block === 'Y' ? 'blocked-row' : '',
                0 => $srNo++,
                1 => '<img src="' . $img . '" width="45" height="45" style="object-fit:cover;border-radius:50%;">',
                2 => '<div class="employee-inline-info"><strong>' . e($employee->emp_name) . '</strong><br><span>' . e($employee->emp_email) . '</span><br><span>' . e($employee->emp_mobile) . '</span></div>',
                3 => e($employee->emp_regid),
                4 => e($employee->center_name ?? '-'),
                5 => e($employee->emp_type),
                6 => e($employee->emp_gender),
                7 => '
                <button type="button"
                    class="btn btn-sm btn-info text-white viewAssignedBatchesBtn"
                    data-id="' . e($employee->emp_id) . '"
                    data-name="' . e($employee->emp_name) . '"
                    data-regid="' . e($employee->emp_regid) . '">
                    <i class="bi bi-eye me-1"></i> View Assigned Batches
                </button>
            ',
                8 => $actionHtml,
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
            'Content-Disposition' => 'attachment; filename=bulk_assign_employee_mobile_template.csv',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['mobile']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadBulkUploadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=bulk_employee_upload_template.csv',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['emp_name', 'emp_regid', 'emp_mobile', 'emp_email', 'emp_gender', 'emp_type']);
            fputcsv($file, ['John Doe', 'EMP-1001', '9876543210', 'john@example.com', 'Male', 'Internal']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadRegIdTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=bulk_employee_regid_update_template.csv',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['emp_mobile', 'emp_regid']);
            fputcsv($file, ['9876543210', 'EMP-1001']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function assignedBatches($id): JsonResponse
    {
        $employee = Employee::where('emp_id', $id)
            ->where('del_status', 'N')
            ->firstOrFail();

        $rows = DB::table('tbl_employee_batch as eb')
            ->join('tbl_batch as b', 'b.batch_id', '=', 'eb.batch_id')
            ->leftJoin('tbl_technology as t', 't.technology_id', '=', 'b.technology_id')
            ->leftJoin('tbl_programme as p', 'p.programme_id', '=', 'b.programme_id')
            ->leftJoin('tbl_session as s', 's.session_id', '=', 'b.session_id')
            ->where('eb.emp_id', $id)
            ->where('eb.is_active', 'Y')
            ->select(
                's.session_name',
                'p.programme_name',
                't.technology_name',
                'b.batch_name'
            )
            ->orderBy('s.session_name', 'asc')
            ->orderBy('p.programme_name', 'asc')
            ->orderBy('t.technology_name', 'asc')
            ->orderBy('b.batch_name', 'asc')
            ->get();

        return response()->json([
            'status'   => true,
            'employee' => [
                'emp_id'   => $employee->emp_id,
                'emp_name' => $employee->emp_name,
                'emp_regid' => $employee->emp_regid,
            ],
            'data' => $rows,
        ]);
    }
}
