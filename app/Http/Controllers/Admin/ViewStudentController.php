<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ViewStudentController extends Controller
{
  private string $studentTable = 'tbl_student';
  private string $centerTable = 'tbl_centers';
  private string $programmeTable = 'tbl_programme';
  private string $technologyTable = 'tbl_technology';
  private string $batchTable = 'tbl_batch';
  private string $programmeCenterTable = 'tbl_programme_centers';

  public function index(): View
  {
    $sessions = Session::orderByRaw("
                CASE
                    WHEN status = 'current' THEN 1
                    WHEN status = 'upcomming' THEN 2
                    ELSE 3
                END
            ")
      ->orderBy('session_name', 'asc')
      ->get(['session_id', 'session_name']);

    return view('Admin.ViewStudents.index', compact('sessions'));
  }

  public function getFilterData(Request $request): JsonResponse
  {
    try {
      $filters = $this->getFilters($request);

      $sessions = Session::orderByRaw("
                    CASE
                        WHEN status = 'current' THEN 1
                        WHEN status = 'upcomming' THEN 2
                        ELSE 3
                    END
                ")
        ->orderBy('session_name', 'asc')
        ->get(['session_id', 'session_name']);

      /*
            |--------------------------------------------------------------------------
            | Centers -> selected session ke basis par
            |--------------------------------------------------------------------------
            */
      $centers = collect();

      if (!empty($filters['session_ids'])) {
        $centers = DB::table($this->programmeCenterTable . ' as pc')
          ->join($this->centerTable . ' as c', 'c.center_id', '=', 'pc.center_id')
          ->select('c.center_id', 'c.center_name')
          ->whereIn('pc.session_id', $filters['session_ids'])
          ->distinct()
          ->orderBy('c.center_name', 'asc')
          ->get();
      }

      /*
            |--------------------------------------------------------------------------
            | Programmes -> selected session + center ke basis par
            |--------------------------------------------------------------------------
            */
      $programmes = collect();

      if (!empty($filters['session_ids']) && !empty($filters['center_ids'])) {
        $programmes = DB::table($this->programmeCenterTable . ' as pc')
          ->join($this->programmeTable . ' as p', 'p.programme_id', '=', 'pc.programme_id')
          ->select('p.programme_id', 'p.programme_name')
          ->whereIn('pc.session_id', $filters['session_ids'])
          ->whereIn('pc.center_id', $filters['center_ids'])
          ->distinct()
          ->orderBy('p.programme_name', 'asc')
          ->get();
      }

      /*
            |--------------------------------------------------------------------------
            | Technologies -> selected session + programme ke basis par
            |--------------------------------------------------------------------------
            */
      $technologies = collect();

      if (!empty($filters['session_ids']) && !empty($filters['programme_ids'])) {
        $technologyQuery = DB::table($this->technologyTable)
          ->select('technology_id', 'technology_name')
          ->distinct()
          ->orderBy('technology_name', 'asc');

        if (Schema::hasColumn($this->technologyTable, 'session_id')) {
          $technologyQuery->whereIn('session_id', $filters['session_ids']);
        }

        if (Schema::hasColumn($this->technologyTable, 'programme_id')) {
          $technologyQuery->whereIn('programme_id', $filters['programme_ids']);
        }

        $technologies = $technologyQuery->get();
      }

      /*
            |--------------------------------------------------------------------------
            | Batches -> selected session + programme + technology ke basis par
            |--------------------------------------------------------------------------
            */
      $batches = collect();

      if (
        !empty($filters['session_ids']) &&
        !empty($filters['programme_ids']) &&
        !empty($filters['technology_ids'])
      ) {
        $batchQuery = DB::table($this->batchTable)
          ->select('batch_id', 'batch_name')
          ->distinct()
          ->orderBy('batch_name', 'asc');

        if (Schema::hasColumn($this->batchTable, 'session_id')) {
          $batchQuery->whereIn('session_id', $filters['session_ids']);
        }

        if (Schema::hasColumn($this->batchTable, 'programme_id')) {
          $batchQuery->whereIn('programme_id', $filters['programme_ids']);
        }

        if (Schema::hasColumn($this->batchTable, 'technology_id')) {
          $batchQuery->whereIn('technology_id', $filters['technology_ids']);
        }

        $batches = $batchQuery->get();
      }

      return response()->json([
        'status'       => true,
        'sessions'     => $sessions,
        'centers'      => $centers,
        'programmes'   => $programmes,
        'technologies' => $technologies,
        'batches'      => $batches,
      ]);
    } catch (\Throwable $e) {
      report($e);

      return response()->json([
        'status'       => false,
        'message'      => 'Unable to load filter data.',
        'sessions'     => [],
        'centers'      => [],
        'programmes'   => [],
        'technologies' => [],
        'batches'      => [],
      ], 200);
    }
  }

  public function data(Request $request): JsonResponse
  {
    try {
      $draw   = (int) $request->input('draw', 1);
      $start  = (int) $request->input('start', 0);
      $length = (int) $request->input('length', 10);
      $search = trim($request->input('search.value', ''));
      $fetch  = (int) $request->input('fetch', 0);

      if ($fetch !== 1) {
        return response()->json([
          'draw'            => $draw,
          'recordsTotal'    => 0,
          'recordsFiltered' => 0,
          'data'            => [],
        ]);
      }

      $filters = $this->getFilters($request);

      $regidColumn   = $this->getStudentColumn(['regid', 'stu_regid']);
      $nameColumn    = $this->getStudentColumn(['stu_name', 'student_name', 'name']);
      $collegeColumn = $this->getStudentColumn(['college_name', 'stu_college']);
      $mobileColumn  = $this->getStudentColumn(['mobile', 'stu_mobile']);
      $emailColumn   = $this->getStudentColumn(['email', 'stu_email']);

      $baseQuery = $this->buildStudentQuery($filters);

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
        $data[] = [
          'sr_no'        => $srNo++,
          'regid'        => $student->regid ?: '-',
          'stu_name'     => $student->stu_name ?: '-',
          'college_name' => $student->college_name ?: '-',
          'mobile'       => $student->mobile ?: '-',
          'email'        => $student->email ?: '-',
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
        'error'           => 'Unable to load student data.',
      ], 200);
    }
  }

  private function getFilters(Request $request): array
  {
    return [
      'session_ids'    => $this->normalizeIds($request->input('session_ids', [])),
      'center_ids'     => $this->normalizeIds($request->input('center_ids', [])),
      'programme_ids'  => $this->normalizeIds($request->input('programme_ids', [])),
      'technology_ids' => $this->normalizeIds($request->input('technology_ids', [])),
      'batch_ids'      => $this->normalizeIds($request->input('batch_ids', [])),
    ];
  }

  private function normalizeIds($value): array
  {
    if (is_string($value) && str_contains($value, ',')) {
      $value = explode(',', $value);
    }

    if (!is_array($value)) {
      $value = [$value];
    }

    return array_values(array_unique(array_filter(array_map('intval', $value))));
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

  private function baseStudentQuery()
  {
    $query = DB::table($this->studentTable . ' as s');

    if (Schema::hasColumn($this->studentTable, 'del_status')) {
      $query->where(function ($q) {
        $q->whereNull('s.del_status')
          ->orWhere('s.del_status', 'N')
          ->orWhere('s.del_status', 0)
          ->orWhere('s.del_status', '0');
      });
    }

    if (Schema::hasColumn($this->studentTable, 'is_block')) {
      $query->where(function ($q) {
        $q->whereNull('s.is_block')
          ->orWhere('s.is_block', 'N')
          ->orWhere('s.is_block', 0)
          ->orWhere('s.is_block', '0');
      });
    }

    return $query;
  }

  private function buildStudentQuery(array $filters)
  {
    $query = $this->baseStudentQuery();

    $sessionColumn    = $this->getStudentColumn(['session_id', 'stu_session']);
    $centerColumn     = $this->getStudentColumn(['center_id']);
    $programmeColumn  = $this->getStudentColumn(['programme_id']);
    $technologyColumn = $this->getStudentColumn(['technology_id']);
    $batchColumn      = $this->getStudentColumn(['batch_id']);

    if ($sessionColumn && !empty($filters['session_ids'])) {
      $query->whereIn("s.$sessionColumn", $filters['session_ids']);
    }

    if ($centerColumn && !empty($filters['center_ids'])) {
      $query->whereIn("s.$centerColumn", $filters['center_ids']);
    }

    if ($programmeColumn && !empty($filters['programme_ids'])) {
      $query->whereIn("s.$programmeColumn", $filters['programme_ids']);
    }

    if ($technologyColumn && !empty($filters['technology_ids'])) {
      $query->whereIn("s.$technologyColumn", $filters['technology_ids']);
    }

    if ($batchColumn && !empty($filters['batch_ids'])) {
      $query->whereIn("s.$batchColumn", $filters['batch_ids']);
    }

    return $query;
  }
}
