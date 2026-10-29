<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CenterController extends Controller
{
  public function index(): View
  {
    $centers = Center::orderBy('center_id', 'desc')->get();
    return view('Admin.Master_Entry.centers', compact('centers'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'center_name' => ['required', 'string', 'max:150', 'unique:tbl_centers,center_name'],
      'latitude'    => ['required', 'numeric', 'between:-90,90'],
      'longitude'   => ['required', 'numeric', 'between:-180,180'],
      'radius'      => ['required', 'numeric', 'min:1'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $center = Center::create([
      'center_name' => $validated['center_name'],
      'latitude'    => $validated['latitude'],
      'longitude'   => $validated['longitude'],
      'radius'      => $validated['radius'],
      'created_by'  => $username,
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Center created successfully.',
      'data'    => $center,
    ]);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $center = Center::findOrFail($id);

    $validated = $request->validate([
      'center_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_centers', 'center_name')->ignore($center->center_id, 'center_id')
      ],
      'latitude'    => ['required', 'numeric', 'between:-90,90'],
      'longitude'   => ['required', 'numeric', 'between:-180,180'],
      'radius'      => ['required', 'numeric', 'min:1'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $center->update([
      'center_name' => $validated['center_name'],
      'latitude'    => $validated['latitude'],
      'longitude'   => $validated['longitude'],
      'radius'      => $validated['radius'],
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Center updated successfully.',
      'data'    => $center,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $center = Center::findOrFail($id);
    $center->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Center deleted successfully.',
    ]);
  }
}
