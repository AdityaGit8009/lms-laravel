<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $admin->name  = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        if ($request->hasFile('image')) {
            if ($admin->image && file_exists(public_path('uploads/' . $admin->image))) {
                @unlink(public_path('uploads/' . $admin->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads'), $filename);

            $admin->image = $filename;
        }

        $admin->save();

        return response()->json([
            'status'    => true,
            'message'   => 'Profile updated successfully.',
            'image_url' => $admin->image
                ? asset('uploads/' . $admin->image)
                : asset('uploads/default-user.png'),
            'name'      => $admin->name,
            'phone'     => $admin->phone,
            'email'     => $admin->email,
        ], 200);
    }

    public function changePasswordPage()
    {
        return view('admin.change-password');
    }

    public function changePassword(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'current_password'      => 'required|string',
            'new_password'          => 'required|string|min:6|different:current_password',
            'confirm_password'      => 'required|same:new_password',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required'     => 'New password is required.',
            'new_password.min'          => 'New password must be at least 6 characters.',
            'new_password.different'    => 'New password must be different from current password.',
            'confirm_password.required' => 'Confirm password is required.',
            'confirm_password.same'     => 'Confirm password does not match new password.',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully.'
        ], 200);
    }
}
