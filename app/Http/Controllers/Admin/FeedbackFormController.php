<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackForm;
use App\Models\FeedbackResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackFormController extends Controller
{
  public function formData(Request $request): JsonResponse
  {
    $formId = $request->input('form_id');
    $form = $formId ? FeedbackForm::find($formId) : null;

    return response()->json([
      'status' => true,
      'form' => $form,
    ]);
  }

  public function getPublicForm(string $publicKey): JsonResponse
  {
    $form = FeedbackForm::where('public_key', $publicKey)
      ->where('form_status', 'open')
      ->first();

    if (!$form) {
      return response()->json([
        'status' => false,
        'message' => 'Feedback form not found.',
      ], 404);
    }

    return response()->json([
      'status' => true,
      'form' => $form,
    ]);
  }

  public function submit(Request $request): JsonResponse
  {
    return $this->submitPayload($request, (int) $request->input('form_id'));
  }

  public function submitPublicApi(Request $request, string $publicKey): JsonResponse
  {
    $form = FeedbackForm::where('public_key', $publicKey)->first();

    if (!$form) {
      return response()->json([
        'status' => false,
        'message' => 'Feedback form not found.',
      ], 404);
    }

    return $this->submitPayload($request, (int) $form->form_id);
  }

  private function submitPayload(Request $request, int $formId): JsonResponse
  {
    $form = FeedbackForm::find($formId);

    if (!$form) {
      return response()->json([
        'status' => false,
        'message' => 'Feedback form not found.',
      ], 404);
    }

    FeedbackResponse::create([
      'form_id' => $form->form_id,
      'student_id' => $request->input('student_id'),
      'user_id' => $request->input('user_id'),
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'mobile' => $request->input('mobile'),
      'session_id' => $request->input('session_id'),
      'center_id' => $request->input('center_id'),
      'programme_id' => $request->input('programme_id'),
      'technology_id' => $request->input('technology_id'),
      'batch_id' => $request->input('batch_id'),
      'answers_json' => $request->input('answers', []),
    ]);

    return response()->json([
      'status' => true,
      'message' => 'Feedback submitted successfully.',
    ]);
  }
}
