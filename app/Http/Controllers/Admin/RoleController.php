<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
  public function index(): View
  {
    $roles = Role::orderBy('role_id', 'asc')->get();
    return view('Admin.Master_Entry.roles', compact('roles'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'role_name' => ['required', 'string', 'max:100', 'unique:roles,role_name'],
    ]);

    $role = Role::create([
      'role_name' => trim($validated['role_name']),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Role created successfully.',
      'data'    => $role,
    ], 201);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $role = Role::where('role_id', $id)->firstOrFail();

    $validated = $request->validate([
      'role_name' => [
        'required',
        'string',
        'max:100',
        Rule::unique('roles', 'role_name')->ignore($role->role_id, 'role_id'),
      ],
    ]);

    $role->update([
      'role_name' => trim($validated['role_name']),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Role updated successfully.',
      'data'    => $role,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $role = Role::where('role_id', $id)->firstOrFail();

    $isUsed = \App\Models\User::whereRaw('LOWER(role) = ?', [strtolower($role->role_name)])->exists();

    if ($isUsed) {
      return response()->json([
        'status'  => false,
        'message' => 'This role is already assigned to users, so it cannot be deleted.',
      ], 422);
    }

    $role->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Role deleted successfully.',
    ]);
  }
}
