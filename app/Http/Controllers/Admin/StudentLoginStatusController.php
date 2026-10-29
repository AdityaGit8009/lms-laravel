<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentLoginStatusController extends Controller
{
  public function index(Request $request)
  {
    $students = DB::table('tbl_student as s')
      ->leftJoin('tbl_centers as c', 'c.center_id', '=', 's.center_id')
      ->leftJoin('tbl_student_programme as sp', 'sp.stu_id', '=', 's.stu_id')
      ->leftJoin('tbl_programme as p', 'p.programme_id', '=', 'sp.programme_id')
      ->leftJoin('tbl_student_technology as st', 'st.stu_id', '=', 's.stu_id')
      ->leftJoin('tbl_technology as t', 't.technology_id', '=', 'st.technology_id')
      ->leftJoin('tbl_student_batch as sb', 'sb.stu_id', '=', 's.stu_id')
      ->leftJoin('tbl_batch as b', 'b.batch_id', '=', 'sb.batch_id')

      ->leftJoin('users as u', function ($join) {
        $join->on('u.email', '=', 's.stu_email')
          ->where('u.role', '=', 'student');
      })

      ->leftJoin(DB::raw('
                (
                    SELECT sl1.*
                    FROM student_login_logs sl1
                    INNER JOIN (
                        SELECT stu_id, MAX(log_id) as max_log_id
                        FROM student_login_logs
                        GROUP BY stu_id
                    ) sl2 ON sl1.log_id = sl2.max_log_id
                ) as slog
            '), 'slog.stu_id', '=', 's.stu_id')

      ->leftJoin('sessions as sess', 'sess.user_id', '=', 'u.id')

      ->where('s.del_status', 'N')
      ->where('s.is_block', 'N')

      ->when($request->filled('center_id'), function ($q) use ($request) {
        $q->where('s.center_id', $request->center_id);
      })
      ->when($request->filled('programme_id'), function ($q) use ($request) {
        $q->where('sp.programme_id', $request->programme_id);
      })
      ->when($request->filled('technology_id'), function ($q) use ($request) {
        $q->where('st.technology_id', $request->technology_id);
      })
      ->when($request->filled('batch_id'), function ($q) use ($request) {
        $q->where('sb.batch_id', $request->batch_id);
      })

      ->select(
        's.stu_id',
        's.stu_name',
        's.stu_email',
        's.stu_mobile',
        'c.center_name',
        'p.programme_name',
        't.technology_name',
        'b.batch_name',
        'u.id as user_id',
        'slog.login_at as last_login_at',
        'slog.last_seen_at',
        'slog.logout_at',
        'sess.id as active_session_id',
        'sess.last_activity'
      )
      ->groupBy(
        's.stu_id',
        's.stu_name',
        's.stu_email',
        's.stu_mobile',
        'c.center_name',
        'p.programme_name',
        't.technology_name',
        'b.batch_name',
        'u.id',
        'slog.login_at',
        'slog.last_seen_at',
        'slog.logout_at',
        'sess.id',
        'sess.last_activity'
      )
      ->orderBy('s.stu_name')
      ->paginate(20);

    foreach ($students as $student) {
      $student->status = 'offline';

      if (!empty($student->active_session_id)) {
        $student->status = 'online';
      } elseif (!empty($student->last_seen_at) && Carbon::parse($student->last_seen_at)->gte(now()->subMinutes(2))) {
        $student->status = 'online';
      }
    }

    $centers = DB::table('tbl_centers')->orderBy('center_name')->get();
    $programmes = DB::table('tbl_programme')->whereNull('deleted_at')->orderBy('programme_name')->get();
    $technologies = DB::table('tbl_technology')->orderBy('technology_name')->get();
    $batches = DB::table('tbl_batch')->orderBy('batch_name')->get();

    return view('Admin.login_status.student_index', compact(
      'students',
      'centers',
      'programmes',
      'technologies',
      'batches'
    ));
  }

  public function logs(Request $request, $stuId)
  {
    $student = DB::table('tbl_student as s')
      ->leftJoin('tbl_centers as c', 'c.center_id', '=', 's.center_id')
      ->where('s.stu_id', $stuId)
      ->select('s.*', 'c.center_name')
      ->first();

    $logs = DB::table('student_login_logs')
      ->where('stu_id', $stuId)
      ->when($request->filled('date'), function ($q) use ($request) {
        $q->whereDate('login_at', $request->date);
      })
      ->when($request->filled('from_date'), function ($q) use ($request) {
        $q->whereDate('login_at', '>=', $request->from_date);
      })
      ->when($request->filled('to_date'), function ($q) use ($request) {
        $q->whereDate('login_at', '<=', $request->to_date);
      })
      ->orderByDesc('login_at')
      ->get();

    $totalSessions = $logs->count();

    $totalMinutes = 0;
    foreach ($logs as $log) {
      if ($log->login_at) {
        $end = $log->logout_at ?? $log->last_seen_at ?? now();
        $totalMinutes += Carbon::parse($log->login_at)->diffInMinutes(Carbon::parse($end));
      }
    }

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;
    $totalActiveTime = $hours . 'h ' . $minutes . 'm';

    return view('Admin.login_status.student_logs_modal', compact(
      'student',
      'logs',
      'totalSessions',
      'totalActiveTime'
    ));
  }
}
