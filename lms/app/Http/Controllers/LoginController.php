<?php

namespace App\Http\Controllers;

use App\Models\RegisterStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
  public function show(): View
  {
    return view('login');
  }

  public function login(Request $request)
  {
    $validated = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required', 'string'],
    ]);

    /*
        |--------------------------------------------------------------------------
        | 1) First check student table (tbl_register)
        |--------------------------------------------------------------------------
        */
    $student = RegisterStudent::where('email', $validated['email'])->first();

    if ($student) {
      if (!Hash::check($validated['password'], $student->password)) {
        return response()->json([
          'status' => false,
          'message' => 'Invalid email or password.'
        ], 401);
      }

      if (($student->approval_status ?? 'Pending') !== 'Approved') {
        return response()->json([
          'status' => false,
          'message' => 'Your account is not approved yet. Please contact admin.'
        ], 403);
      }

      session([
        'logged_in_user_id' => $student->id,
        'logged_in_user_name' => $student->name,
        'logged_in_user_email' => $student->email,
        'logged_in_user_role' => 'student',
      ]);

      return response()->json([
        'status' => true,
        'message' => 'Student login successful.',
        'role' => 'student',
        'redirect' => route('student.dashboard')
      ], 200);
    }

    /*
        |--------------------------------------------------------------------------
        | 2) Then check normal auth users table for admin / employee
        |--------------------------------------------------------------------------
        */

    $user = User::where('email', $request->email)->first();

    if (!$user) {
      return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    if ($user->role === 'student' && $user->is_block === 'Y') {
      return back()->withErrors(['email' => 'Your account has been blocked. Please contact admin.']);
    }

    if (!Hash::check($request->password, $user->password)) {
      return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    Auth::login($user);
    return redirect()->intended('/dashboard');


    if (!Auth::attempt([
      'email' => $validated['email'],
      'password' => $validated['password'],
    ])) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid email or password.'
      ], 401);
    }

    $request->session()->regenerate();

    $user = User::find(Auth::id());
    $roleName = strtolower($user->role ?? '');

    if ($roleName === 'employee') {
      return response()->json([
        'status' => true,
        'message' => 'Employee login successful.',
        'role' => 'employee',
        'redirect' => route('employee.dashboard')
      ], 200);
    }

    if ($roleName === 'admin') {
      return response()->json([
        'status' => true,
        'message' => 'Admin login successful.',
        'role' => 'admin',
        'redirect' => route('admin.dashboard')
      ], 200);
    }

    if ($roleName === 'student') {
      return response()->json([
        'status' => true,
        'message' => 'Student login successful.',
        'role' => 'student',
        'redirect' => route('student.dashboard')
      ], 200);
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
      'status' => false,
      'message' => 'Unauthorized role.'
    ], 403);
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->forget([
      'logged_in_user_id',
      'logged_in_user_name',
      'logged_in_user_email',
      'logged_in_user_role',
    ]);

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
      'status' => true,
      'message' => 'Logout successful.',
      'redirect' => route('login')
    ], 200);
  }
}
