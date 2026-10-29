<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
  public function index(): View
  {
    $courses = Course::orderBy('course_id', 'desc')->get();
    return view('Admin.Master_Entry.courses', compact('courses'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'course_name' => ['required', 'string', 'max:150', 'unique:tbl_courses,course_name'],
      'duration'    => ['required', 'numeric', 'min:1'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $course = Course::create([
      'course_name' => $validated['course_name'],
      'duration'    => $validated['duration'],
      'created_by'  => $username,
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Course created successfully.',
      'data'    => $course,
    ]);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $course = Course::findOrFail($id);

    $validated = $request->validate([
      'course_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_courses', 'course_name')->ignore($course->course_id, 'course_id')
      ],
      'duration'    => ['required', 'numeric', 'min:1'],
    ]);

    $user = Auth::user();
    $username = $user->name ?? $user->username ?? $user->email ?? 'Admin';

    $course->update([
      'course_name' => $validated['course_name'],
      'duration'    => $validated['duration'],
      'updated_by'  => $username,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Course updated successfully.',
      'data'    => $course,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $course = Course::findOrFail($id);
    $course->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Course deleted successfully.',
    ]);
  }
}
