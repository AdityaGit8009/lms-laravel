<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentLoginLog;

class UpdateStudentLastSeen
{
  public function handle(Request $request, Closure $next)
  {
    if (Auth::check() && (Auth::user()->role ?? null) === 'student') {
      $log = StudentLoginLog::where('user_id', Auth::id())
        ->where('status', 'online')
        ->latest('log_id')
        ->first();

      if ($log) {
        $log->update([
          'last_seen_at' => now(),
        ]);
      }
    }

    return $next($request);
  }
}
