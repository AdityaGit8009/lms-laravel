<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\UploadContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
  public function index(): View
  {
    $subjects = Subject::orderBy('subject_id', 'asc')->get();
    return view('Admin.Master_Entry.subjects', compact('subjects'));
  }

  public function topicsPage(Request $request): View
  {
    $subjects = Subject::orderBy('subject_name', 'asc')
      ->get(['subject_id', 'subject_name']);

    $selectedSubjectId = (int) $request->query('subject_id');

    if (!$subjects->contains('subject_id', $selectedSubjectId)) {
      $selectedSubjectId = $subjects->first()->subject_id ?? null;
    }

    return view('Admin.Master_Entry.subject_topics', compact('subjects', 'selectedSubjectId'));
  }

  public function getTopicsBySubject($id): JsonResponse
  {
    $subject = Subject::select('subject_id', 'subject_name')->findOrFail($id);

    $topics = Topic::where('subject_id', $id)
      ->orderBy('topic_id', 'asc')
      ->get(['topic_id', 'subject_id', 'topic_name']);

    $contentTitles = UploadContent::whereIn('topic_id', $topics->pluck('topic_id'))
      ->select('content_id', 'topic_id', 'title')
      ->orderByDesc('content_id')
      ->get()
      ->groupBy('topic_id')
      ->map(function ($rows) {
        return $rows->first();
      });

    $rows = $topics->map(function ($topic, $index) use ($contentTitles) {
      $content = $contentTitles->get($topic->topic_id);

      return [
        'sr_no'      => $index + 1,
        'topic_id'   => $topic->topic_id,
        'subject_id' => $topic->subject_id,
        'topic'      => $topic->topic_name,
        'title'      => $content->title ?? '-',
      ];
    })->values();

    return response()->json([
      'status'  => true,
      'subject' => [
        'subject_id'   => $subject->subject_id,
        'subject_name' => $subject->subject_name,
      ],
      'total'   => $rows->count(),
      'data'    => $rows,
    ]);
  }

  // Existing route:
  // Route::put('/upload-content/{id}/topic-title', [SubjectController::class, 'updateTopicTitle'])
  public function updateTopicTitle(Request $request, $id): JsonResponse
  {
    // Yahan {id} ko topic_id treat kar rahe hain
    $topic = Topic::where('topic_id', $id)->firstOrFail();

    $validated = $request->validate([
      'subject_id' => ['required', 'integer', Rule::exists('tbl_subject', 'subject_id')],
      'topic_name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('tbl_topic', 'topic_name')
          ->where(function ($query) use ($request) {
            $query->where('subject_id', $request->subject_id);
          })
          ->ignore($topic->topic_id, 'topic_id'),
      ],
    ]);

    if ((int) $topic->subject_id !== (int) $validated['subject_id']) {
      return response()->json([
        'status'  => false,
        'message' => 'Selected subject does not match this topic.',
      ], 422);
    }

    $user = Auth::user();
    $roleName = strtolower((string) ($user->role ?? 'admin'));

    $topic->update([
      'topic_name' => trim($validated['topic_name']),
      'updated_by' => $roleName,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Topic updated successfully.',
    ]);
  }

  // Existing route:
  // Route::delete('/upload-content/{id}', [SubjectController::class, 'deleteTopicTitle'])
  public function deleteTopicTitle($id): JsonResponse
  {
    // Yahan {id} ko topic_id treat kar rahe hain
    $topic = Topic::where('topic_id', $id)->firstOrFail();

    DB::transaction(function () use ($topic) {
      UploadContent::where('topic_id', $topic->topic_id)->delete();
      $topic->delete();
    });

    return response()->json([
      'status'  => true,
      'message' => 'Topic deleted successfully.',
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'subject_name'  => ['required', 'string', 'max:150', 'unique:tbl_subject,subject_name'],
      'syllabus'      => ['nullable', 'string'],
      'subject_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $user = Auth::user();
    $roleName = strtolower((string)($user->role ?? 'admin'));

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
      'subject_name'  => trim($validated['subject_name']),
      'syllabus'      => $validated['syllabus'] ?? null,
      'subject_image' => $imageName,
      'created_by'    => $roleName,
      'updated_by'    => $roleName,
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
    $roleName = strtolower((string)($user->role ?? 'admin'));

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
      'subject_name'  => trim($validated['subject_name']),
      'syllabus'      => $validated['syllabus'] ?? null,
      'subject_image' => $imageName,
      'updated_by'    => $roleName,
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
