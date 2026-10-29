<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Models\Session;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TechnologyController extends Controller
{
  public function index(): View
  {
    $technologies = Technology::with(['session', 'programme'])
      ->orderBy('technology_id', 'asc')
      ->get();

    $currentSessions = Session::where('status', 'current')
      ->orderBy('session_id', 'asc')
      ->get();

    $programmes = Programme::whereIn('session_id', $currentSessions->pluck('session_id')->toArray())
      ->orderBy('programme_name', 'asc')
      ->get();

    return view('Admin.Master_Entry.technologies', compact('technologies', 'currentSessions', 'programmes'));
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
      'technology_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_technology', 'technology_name')->where(function ($query) use ($request) {
          return $query->where('programme_id', $request->programme_id);
        }),
      ],
      'technology_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $imageName = null;

    if ($request->hasFile('technology_image')) {
      $uploadPath = public_path('uploads/technologies');
      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      $file = $request->file('technology_image');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $imageName);
    }

    $technology = Technology::create([
      'session_id'       => $validated['session_id'],
      'programme_id'     => $validated['programme_id'],
      'technology_name'  => trim($validated['technology_name']),
      'technology_image' => $imageName,
      'created_by'       => $roleName,
      'updated_by'       => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Technology created successfully.',
      'data'    => $technology,
    ], 201);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $technology = Technology::where('technology_id', $id)->firstOrFail();

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
      'technology_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_technology', 'technology_name')
          ->ignore($technology->technology_id, 'technology_id')
          ->where(function ($query) use ($request) {
            return $query->where('programme_id', $request->programme_id);
          }),
      ],
      'technology_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $imageName = $technology->technology_image;

    if ($request->hasFile('technology_image')) {
      $uploadPath = public_path('uploads/technologies');
      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      if (!empty($technology->technology_image) && File::exists($uploadPath . '/' . $technology->technology_image)) {
        File::delete($uploadPath . '/' . $technology->technology_image);
      }

      $file = $request->file('technology_image');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $imageName);
    }

    $technology->update([
      'session_id'       => $validated['session_id'],
      'programme_id'     => $validated['programme_id'],
      'technology_name'  => trim($validated['technology_name']),
      'technology_image' => $imageName,
      'updated_by'       => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Technology updated successfully.',
      'data'    => $technology,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $technology = Technology::where('technology_id', $id)->firstOrFail();

    if (!empty($technology->technology_image) && File::exists(public_path('uploads/technologies/' . $technology->technology_image))) {
      File::delete(public_path('uploads/technologies/' . $technology->technology_image));
    }

    $technology->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Technology deleted successfully.',
    ]);
  }
}
