<?php

namespace App\Http\Controllers;

use App\Models\RegisterStudent;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
  public function show(): View
  {
    return view('register');
  }

  public function register(Request $request)
  {
    $validated = $request->validate([
      'name'     => ['required', 'regex:/^[A-Za-z\s]{3,50}$/'],
      'college'  => ['required', 'string', 'max:100'],
      'regno'    => ['required', 'string', 'min:5', 'max:15', 'unique:tbl_register,regno'],
      'course'   => ['required', 'string'],
      'branch'   => ['required', 'string', 'max:100'],
      'year'     => ['required', 'string', 'max:20'],
      'mobile'   => ['required', 'regex:/^[6-9]\d{9}$/', 'unique:tbl_register,mobile'],
      'email'    => ['required', 'email', 'max:255', 'unique:tbl_register,email', 'unique:users,email'],
      'gender'   => ['required', Rule::in(['male', 'female'])],
      'password' => ['required', 'string', 'min:6', 'max:20'],
      'pro'      => ['required', 'string'],
      'location' => ['required', 'string', 'max:255'],
      'pic'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    DB::beginTransaction();

    try {
      $photoPath = null;

      if ($request->hasFile('pic')) {
        $photoPath = $request->file('pic')->store('students', 'public');
      }

      $hashedPassword = Hash::make($validated['password']);

      $studentRole = Role::where('role_name', 'student')->first();

      if (!$studentRole) {
        throw new \Exception('Student role not found in roles table.');
      }

      RegisterStudent::create([
        'name'            => $validated['name'],
        'college'         => $validated['college'],
        'regno'           => $validated['regno'],
        'course'          => $validated['course'],
        'branch'          => $validated['branch'],
        'year'            => $validated['year'],
        'mobile'          => $validated['mobile'],
        'email'           => $validated['email'],
        'password'        => $hashedPassword,
        'gender'          => $validated['gender'],
        'program'         => $validated['pro'],
        'location'        => $validated['location'],
        'photo'           => $photoPath,
        'approval_status' => 'Pending',
      ]);

      User::create([
        'name'              => $validated['name'],
        'email'             => $validated['email'],
        'phone'             => $validated['mobile'],
        'role'              => 'student',
        'password'          => $hashedPassword,
        'email_verified_at' => now(),
      ]);

      DB::commit();

      return response()->json([
        'status'  => true,
        'message' => 'Registration successful. Your account is pending admin approval.'
      ], 201);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Registration failed.',
        'error'   => $e->getMessage()
      ], 500);
    }
  }
}
