<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgrammeController extends Controller
{
  public function index(): View
  {
    $programmes = Programme::with('session')
      ->orderBy('programme_id', 'asc')
      ->get();

    $currentSessions = Session::where('status', 'current')
      ->orderBy('session_id', 'asc')
      ->get();

    return view('Admin.Master_Entry.programmes', compact('programmes', 'currentSessions'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_id' => [
        'required',
        'exists:tbl_session,session_id',
        function ($attribute, $value, $fail) {
          $isCurrent = Session::where('session_id', $value)
            ->where('status', 'current')
            ->exists();

          if (!$isCurrent) {
            $fail('Only current session can be selected.');
          }
        }
      ],
      'programme_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_programme', 'programme_name')->where(function ($query) use ($request) {
          return $query->where('session_id', $request->session_id);
        }),
      ],
      'duration' => ['required', 'string', 'max:100'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $programme = Programme::create([
      'session_id'     => $validated['session_id'],
      'programme_name' => trim($validated['programme_name']),
      'duration'       => trim($validated['duration']),
      'created_by'     => $roleName,
      'updated_by'     => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Programme created successfully.',
      'data'    => $programme,
    ], 201);
  }
  public function update(Request $request, $id): JsonResponse
  {
    $programme = Programme::where('programme_id', $id)->firstOrFail();

    $validated = $request->validate([
      'session_id' => [
        'required',
        'exists:tbl_session,session_id',
        function ($attribute, $value, $fail) {
          $isCurrent = Session::where('session_id', $value)
            ->where('status', 'current')
            ->exists();

          if (!$isCurrent) {
            $fail('Only current session can be selected.');
          }
        }
      ],
      'programme_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_programme', 'programme_name')
          ->ignore($programme->programme_id, 'programme_id')
          ->where(function ($query) use ($request) {
            return $query->where('session_id', $request->session_id);
          }),
      ],
      'duration' => ['required', 'string', 'max:100'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $programme->update([
      'session_id'     => $validated['session_id'],
      'programme_name' => trim($validated['programme_name']),
      'duration'       => trim($validated['duration']),
      'updated_by'     => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Programme updated successfully.',
      'data'    => $programme,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $programme = Programme::where('programme_id', $id)->firstOrFail();
    $programme->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Programme deleted successfully.',
    ]);
  }
}
