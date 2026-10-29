<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SessionController extends Controller
{
  public function index(): View
  {
    $sessions = Session::orderBy('session_id', 'asc')->get();
    return view('Admin.Master_Entry.sessions', compact('sessions'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_name' => ['required', 'string', 'max:100', 'unique:tbl_session,session_name'],
      'status'       => ['required', Rule::in(['old', 'current', 'upcomming'])],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    if ($validated['status'] === 'current') {
      Session::where('status', 'current')->update([
        'status' => 'old',
        'updated_by' => $roleName,
      ]);
    }

    $session = Session::create([
      'session_name' => trim($validated['session_name']),
      'status'       => $validated['status'],
      'created_by'   => $roleName,
      'updated_by'   => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Session created successfully.',
      'data'    => $session,
    ], 201);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $session = Session::where('session_id', $id)->firstOrFail();

    $validated = $request->validate([
      'session_name' => [
        'required',
        'string',
        'max:100',
        Rule::unique('tbl_session', 'session_name')->ignore($session->session_id, 'session_id'),
      ],
      'status' => ['required', Rule::in(['old', 'current', 'upcomming'])],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    if ($validated['status'] === 'current') {
      Session::where('status', 'current')
        ->where('session_id', '!=', $session->session_id)
        ->update([
          'status' => 'old',
          'updated_by' => $roleName,
        ]);
    }

    $session->update([
      'session_name' => trim($validated['session_name']),
      'status'       => $validated['status'],
      'updated_by'   => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Session updated successfully.',
      'data'    => $session,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $session = Session::where('session_id', $id)->firstOrFail();
    $session->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Session deleted successfully.',
    ]);
  }
}
