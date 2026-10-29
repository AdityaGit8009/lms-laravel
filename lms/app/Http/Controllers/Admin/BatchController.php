<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Programme;
use App\Models\Session;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BatchController extends Controller
{
  public function index(): View
  {
    $batches = Batch::with(['session', 'programme', 'technology'])
      ->orderBy('batch_id', 'asc')
      ->get();

    $currentSessions = Session::where('status', 'current')
      ->orderBy('session_id', 'asc')
      ->get();

    $programmes = Programme::whereIn('session_id', $currentSessions->pluck('session_id')->toArray())
      ->orderBy('programme_name', 'asc')
      ->get();

    $technologies = Technology::whereIn('session_id', $currentSessions->pluck('session_id')->toArray())
      ->orderBy('technology_name', 'asc')
      ->get();

    return view('Admin.Master_Entry.batches', compact(
      'batches',
      'currentSessions',
      'programmes',
      'technologies'
    ));
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
      'programme_id' => [
        'required',
        'exists:tbl_programme,programme_id',
        function ($attribute, $value, $fail) use ($request) {
          $exists = Programme::where('programme_id', $value)
            ->where('session_id', $request->session_id)
            ->exists();

          if (!$exists) {
            $fail('Selected programme does not belong to selected session.');
          }
        }
      ],
      'technology_id' => [
        'required',
        'exists:tbl_technology,technology_id',
        function ($attribute, $value, $fail) use ($request) {
          $exists = Technology::where('technology_id', $value)
            ->where('session_id', $request->session_id)
            ->where('programme_id', $request->programme_id)
            ->exists();

          if (!$exists) {
            $fail('Selected technology does not belong to selected programme.');
          }
        }
      ],
      'batch_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_batch', 'batch_name')->where(function ($query) use ($request) {
          return $query->where('technology_id', $request->technology_id);
        }),
      ],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $batch = Batch::create([
      'session_id'    => $validated['session_id'],
      'programme_id'  => $validated['programme_id'],
      'technology_id' => $validated['technology_id'],
      'batch_name'    => trim($validated['batch_name']),
      'created_by'    => $roleName,
      'updated_by'    => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Batch created successfully.',
      'data'    => $batch,
    ], 201);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $batch = Batch::where('batch_id', $id)->firstOrFail();

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
      'programme_id' => [
        'required',
        'exists:tbl_programme,programme_id',
        function ($attribute, $value, $fail) use ($request) {
          $exists = Programme::where('programme_id', $value)
            ->where('session_id', $request->session_id)
            ->exists();

          if (!$exists) {
            $fail('Selected programme does not belong to selected session.');
          }
        }
      ],
      'technology_id' => [
        'required',
        'exists:tbl_technology,technology_id',
        function ($attribute, $value, $fail) use ($request) {
          $exists = Technology::where('technology_id', $value)
            ->where('session_id', $request->session_id)
            ->where('programme_id', $request->programme_id)
            ->exists();

          if (!$exists) {
            $fail('Selected technology does not belong to selected programme.');
          }
        }
      ],
      'batch_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_batch', 'batch_name')
          ->ignore($batch->batch_id, 'batch_id')
          ->where(function ($query) use ($request) {
            return $query->where('technology_id', $request->technology_id);
          }),
      ],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $batch->update([
      'session_id'    => $validated['session_id'],
      'programme_id'  => $validated['programme_id'],
      'technology_id' => $validated['technology_id'],
      'batch_name'    => trim($validated['batch_name']),
      'updated_by'    => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Batch updated successfully.',
      'data'    => $batch,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $batch = Batch::where('batch_id', $id)->firstOrFail();
    $batch->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Batch deleted successfully.',
    ]);
  }
}
