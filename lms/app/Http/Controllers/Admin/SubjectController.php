<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
  public function index(): View
  {
    $subjects = Subject::orderBy('subject_id', 'asc')->get();
    return view('Admin.Master_Entry.subjects', compact('subjects'));
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'subject_name' => ['required', 'string', 'max:150', 'unique:tbl_subject,subject_name'],
      'syllabus'     => ['nullable', 'string'],
      'subject_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $imageName = null;

    if ($request->hasFile('subject_image')) {
      $uploadPath = public_path('uploads/subjects');

      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      $file = $request->file('subject_image');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $imageName);
    }

    $subject = Subject::create([
      'subject_name' => trim($validated['subject_name']),
      'syllabus'     => $validated['syllabus'] ?? null,
      'subject_image' => $imageName,
      'created_by'   => $roleName,
      'updated_by'   => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Subject created successfully.',
      'data'    => $subject,
    ], 201);
  }

  public function update(Request $request, $id): JsonResponse
  {
    $subject = Subject::where('subject_id', $id)->firstOrFail();

    $validated = $request->validate([
      'subject_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_subject', 'subject_name')->ignore($subject->subject_id, 'subject_id'),
      ],
      'syllabus'      => ['nullable', 'string'],
      'subject_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $user = Auth::user();
    $roleName = $user && $user->role ? $user->role->role_name : 'admin';

    $imageName = $subject->subject_image;

    if ($request->hasFile('subject_image')) {
      $uploadPath = public_path('uploads/subjects');

      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      if (!empty($subject->subject_image) && File::exists($uploadPath . '/' . $subject->subject_image)) {
        File::delete($uploadPath . '/' . $subject->subject_image);
      }

      $file = $request->file('subject_image');
      $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $imageName);
    }

    $subject->update([
      'subject_name' => trim($validated['subject_name']),
      'syllabus'     => $validated['syllabus'] ?? null,
      'subject_image' => $imageName,
      'updated_by'   => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Subject updated successfully.',
      'data'    => $subject,
    ]);
  }

  public function destroy($id): JsonResponse
  {
    $subject = Subject::where('subject_id', $id)->firstOrFail();

    if (!empty($subject->subject_image) && File::exists(public_path('uploads/subjects/' . $subject->subject_image))) {
      File::delete(public_path('uploads/subjects/' . $subject->subject_image));
    }

    $subject->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Subject deleted successfully.',
    ]);
  }
}
