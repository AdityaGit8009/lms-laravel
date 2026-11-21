<?php

namespace App\Http\Controllers;

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

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        if (strtolower((string) $user->role) === 'student') {
            if (($user->approval_status ?? 'Pending') !== 'Approved') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is pending admin approval.',
                ], 403);
            }

            if (($user->is_block ?? 'N') === 'Y') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account has been blocked. Please contact admin.',
                ], 403);
            }
        }

        if (!$user->isAdmin() && !$user->isStudent()) {
            return response()->json([
                'status' => false,
                'message' => 'There is no portal for this account type.',
            ], 403);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $role = strtolower((string) $user->role);
        $dashboard = route($user->isStudent() ? 'student.dashboard' : 'admin.dashboard');
        $redirect = redirect()->intended($dashboard)->getTargetUrl();

        return response()->json([
            'status' => true,
            'message' => ucfirst($role ?: 'User') . ' login successful.',
            'role' => $role,
            'redirect' => $redirect,
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful.',
            'redirect' => route('login'),
        ], 200);
    }
}
