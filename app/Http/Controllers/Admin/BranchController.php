<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BranchController extends Controller
{
  public function index(): View
  {
    $branches = Branch::with('course')->orderBy('branch_id', 'desc')->get();
    $courses  = Course::orderBy('course_name', 'asc')->get();

    return view('Admin.Master_Entry.branches', compact('branches', 'courses'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'course_id'    => ['required', 'exists:tbl_courses,course_id'],
      'branch_name'  => ['required', 'string', 'max:150'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $branch = Branch::create([
      'course_id'   => $validated['course_id'],
      'branch_name' => $validated['branch_name'],
      'created_by'  => $username,
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Branch created successfully.',
      'data'    => $branch,
    ]);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $branch = Branch::findOrFail($id);

    $validated = $request->validate([
      'course_id'    => ['required', 'exists:tbl_courses,course_id'],
      'branch_name'  => ['required', 'string', 'max:150'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $branch->update([
      'course_id'   => $validated['course_id'],
      'branch_name' => $validated['branch_name'],
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Branch updated successfully.',
      'data'    => $branch,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $branch = Branch::findOrFail($id);
    $branch->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Branch deleted successfully.',
    ]);
  }
}
