<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlockedDeletedStudentController extends Controller
{
  private string $studentTable = 'tbl_student';

  public function index(): View
  {
    return view('Admin.ViewStudents.block-del');
  }

  public function data(Request $request): JsonResponse
  {
    $type = $request->input('type', 'blocked');

    if (!in_array($type, ['blocked', 'deleted'], true)) {
      $type = 'blocked';
    }

    try {
      $draw   = (int) $request->input('draw', 1);
      $start  = (int) $request->input('start', 0);
      $length = (int) $request->input('length', 10);
      $search = trim($request->input('search.value', ''));

      $regidColumn   = $this->getStudentColumn(['regid', 'stu_regid']);
      $nameColumn    = $this->getStudentColumn(['stu_name', 'student_name', 'name']);
      $collegeColumn = $this->getStudentColumn(['college_name', 'stu_college']);
      $mobileColumn  = $this->getStudentColumn(['mobile', 'stu_mobile']);
      $emailColumn   = $this->getStudentColumn(['email', 'stu_email']);

      $baseQuery = DB::table($this->studentTable . ' as s');

      if ($type === 'blocked') {
        $baseQuery->where('s.is_block', 'Y');
      } else {
        $baseQuery->where('s.del_status', 'Y');
      }

      $recordsTotal = (clone $baseQuery)->count('s.stu_id');

      if ($search !== '') {
        $searchColumns = array_filter([
          $regidColumn,
          $nameColumn,
          $collegeColumn,
          $mobileColumn,
          $emailColumn,
        ]);

        if (!empty($searchColumns)) {
          $baseQuery->where(function ($q) use ($searchColumns, $search) {
            foreach ($searchColumns as $index => $column) {
              if ($index === 0) {
                $q->where("s.$column", 'like', "%{$search}%");
              } else {
                $q->orWhere("s.$column", 'like', "%{$search}%");
              }
            }
          });
        }
      }

      $recordsFiltered = (clone $baseQuery)->count('s.stu_id');

      $students = (clone $baseQuery)
        ->select(
          's.stu_id',
          's.del_status',
          's.is_block',
          $regidColumn   ? DB::raw("s.$regidColumn as regid") : DB::raw("'' as regid"),
          $nameColumn    ? DB::raw("s.$nameColumn as stu_name") : DB::raw("'' as stu_name"),
          $collegeColumn ? DB::raw("s.$collegeColumn as college_name") : DB::raw("'' as college_name"),
          $mobileColumn  ? DB::raw("s.$mobileColumn as mobile") : DB::raw("'' as mobile"),
          $emailColumn   ? DB::raw("s.$emailColumn as email") : DB::raw("'' as email")
        )
        ->orderByDesc('s.stu_id')
        ->offset($start)
        ->limit($length)
        ->get();

      $data = [];
      $srNo = $start + 1;

      foreach ($students as $student) {
        $action = '<span class="text-muted">N/A</span>';

        if ($type === 'blocked' && ($student->is_block ?? 'N') === 'Y') {
          $action = '<button type="button" class="btn btn-sm btn-warning unblock-btn" data-id="' . $student->stu_id . '">
                                  <i class="bi bi-unlock-fill me-1"></i>Unblock
                               </button>';
        }

        if ($type === 'deleted' && ($student->del_status ?? 'N') === 'Y') {
          $action = '<button type="button" class="btn btn-sm btn-success restore-btn" data-id="' . $student->stu_id . '">
                                  <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                               </button>';
        }

        $data[] = [
          'sr_no'        => $srNo++,
          'regid'        => e($student->regid ?: '-'),
          'stu_name'     => e($student->stu_name ?: '-'),
          'college_name' => e($student->college_name ?: '-'),
          'mobile'       => e($student->mobile ?: '-'),
          'email'        => e($student->email ?: '-'),
          'action'       => $action,
        ];
      }

      return response()->json([
        'draw'            => $draw,
        'recordsTotal'    => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data'            => $data,
      ]);
    } catch (\Throwable $e) {
      report($e);

      return response()->json([
        'draw'            => (int) $request->input('draw', 1),
        'recordsTotal'    => 0,
        'recordsFiltered' => 0,
        'data'            => [],
        'error'           => 'Unable to load students.',
      ], 200);
    }
  }

  public function unblock($id): JsonResponse
  {
    try {
      $updated = DB::table($this->studentTable)
        ->where('stu_id', $id)
        ->where('is_block', 'Y')
        ->update([
          'is_block'   => 'N',
          'updated_by' => auth()->user()->name ?? 'Admin',
          'updated_at' => now(),
        ]);

      if (!$updated) {
        return response()->json([
          'status'  => false,
          'message' => 'Student not found or already unblocked.',
        ], 200);
      }

      return response()->json([
        'status'  => true,
        'message' => 'Student unblocked successfully.',
      ]);
    } catch (\Throwable $e) {
      report($e);

      return response()->json([
        'status'  => false,
        'message' => 'Unable to unblock student.',
      ], 200);
    }
  }

  public function restore($id): JsonResponse
  {
    try {
      $updated = DB::table($this->studentTable)
        ->where('stu_id', $id)
        ->where('del_status', 'Y')
        ->update([
          'del_status' => 'N',
          'updated_by' => auth()->user()->name ?? 'Admin',
          'updated_at' => now(),
        ]);

      if (!$updated) {
        return response()->json([
          'status'  => false,
          'message' => 'Student not found or already restored.',
        ], 200);
      }

      return response()->json([
        'status'  => true,
        'message' => 'Student restored successfully.',
      ]);
    } catch (\Throwable $e) {
      report($e);

      return response()->json([
        'status'  => false,
        'message' => 'Unable to restore student.',
      ], 200);
    }
  }

  private function getStudentColumn(array $candidates): ?string
  {
    foreach ($candidates as $column) {
      if (Schema::hasColumn($this->studentTable, $column)) {
        return $column;
      }
    }

    return null;
  }
}
