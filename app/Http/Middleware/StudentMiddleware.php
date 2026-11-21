<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
  public function handle(Request $request, Closure $next): Response
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }

    $student = $request->user();
    if (!$student->isStudent()) {
      abort(403, 'Unauthorized access.');
    }

    $message = null;
    if ($student->approval_status !== 'Approved') {
      $message = 'Your account is not approved yet.';
    } elseif ($student->is_block === 'Y') {
      $message = 'Your account has been blocked.';
    }

    if ($message !== null) {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect()->route('login')->with('error', $message);
    }

    return $next($request);
  }
}
