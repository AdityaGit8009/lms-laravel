<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeedbackForm;
use App\Models\FeedbackResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentFeedbackController extends Controller
{
  public function index()
  {
    $studentProfile = $this->getLoggedStudentProfile();

    if (!$studentProfile) {
      return redirect()->route('login')->with('error', 'Student profile not found.');
    }

    $forms = FeedbackForm::query()
      ->where('form_status', 'open')
      ->orderByDesc('form_id')
      ->get()
      ->filter(function ($form) use ($studentProfile) {
        return $this->studentMatchesForm($studentProfile, $form);
      })
      ->map(function ($form) use ($studentProfile) {
        $submitted = FeedbackResponse::where('form_id', $form->form_id)
          ->where(function ($q) use ($studentProfile) {
            $q->where('user_id', Auth::id());

            if (!empty($studentProfile->stu_email)) {
              $q->orWhere('email', $studentProfile->stu_email)
                ->orWhere('respondent_email', $studentProfile->stu_email);
            }
          })
          ->exists();

        $form->submitted = $submitted;
        return $form;
      })
      ->values();

    return view('Student.feedback.index', [
      'forms' => $forms,
      'studentProfile' => $studentProfile,
    ]);
  }

  public function show($formId)
  {
    $studentProfile = $this->getLoggedStudentProfile();

    if (!$studentProfile) {
      return redirect()->route('login')->with('error', 'Student profile not found.');
    }

    $form = FeedbackForm::findOrFail($formId);

    if ($form->form_status !== 'open' || !$this->studentMatchesForm($studentProfile, $form)) {
      abort(403, 'This feedback form is not available for you.');
    }

    $alreadySubmitted = FeedbackResponse::where('form_id', $form->form_id)
      ->where(function ($q) use ($studentProfile) {
        $q->where('user_id', Auth::id());

        if (!empty($studentProfile->stu_email)) {
          $q->orWhere('email', $studentProfile->stu_email)
            ->orWhere('respondent_email', $studentProfile->stu_email);
        }
      })
      ->exists();

    if ($alreadySubmitted) {
      return redirect()->route('student.feedback.index')
        ->with('info', 'You have already submitted this feedback form.');
    }

    return view('Student.feedback.show', [
      'form' => $form,
      'studentProfile' => $studentProfile,
    ]);
  }

  public function store(Request $request, $formId)
  {
    $studentProfile = $this->getLoggedStudentProfile();

    if (!$studentProfile) {
      return redirect()->route('login')->with('error', 'Student profile not found.');
    }

    $form = FeedbackForm::findOrFail($formId);

    if ($form->form_status !== 'open' || !$this->studentMatchesForm($studentProfile, $form)) {
      abort(403, 'This feedback form is not available for you.');
    }

    $alreadySubmitted = FeedbackResponse::where('form_id', $form->form_id)
      ->where(function ($q) use ($studentProfile) {
        $q->where('user_id', Auth::id());

        if (!empty($studentProfile->stu_email)) {
          $q->orWhere('email', $studentProfile->stu_email)
            ->orWhere('respondent_email', $studentProfile->stu_email);
        }
      })
      ->exists();

    if ($alreadySubmitted) {
      return redirect()->route('student.feedback.index')
        ->with('info', 'You have already submitted this feedback form.');
    }

    $questions = is_array($form->questions_json) ? $form->questions_json : [];
    $answersPayload = [];

    foreach ($questions as $index => $question) {
      $field = 'question_' . $index;
      $type = $question['type'] ?? 'short_answer';
      $questionText = $question['text'] ?? 'Question';
      $required = !empty($question['required']);

      $value = $request->input($field);

      if ($type === 'checkbox') {
        $value = $request->input($field, []);
      }

      if ($required) {
        if ($type === 'checkbox') {
          if (empty($value) || !is_array($value)) {
            return back()->withInput()->with('error', "Please answer: {$questionText}");
          }
        } else {
          if ($value === null || $value === '') {
            return back()->withInput()->with('error', "Please answer: {$questionText}");
          }
        }
      }

      $answersPayload[] = [
        'question' => $questionText,
        'type'     => $type,
        'value'    => $type === 'checkbox'
          ? array_values(array_filter((array) $value))
          : $value,
      ];
    }

    FeedbackResponse::create([
      'form_id'          => $form->form_id,
      'user_id'          => Auth::id(),
      'email'            => $studentProfile->stu_email,
      'respondent_email' => $studentProfile->stu_email,
      'name'             => $studentProfile->stu_name,
      'username'         => $studentProfile->stu_name,
      'mobile'           => $studentProfile->stu_mobile,
      'answers_json'     => $answersPayload,
    ]);

    return redirect()->route('student.feedback.index')
      ->with('success', 'Feedback submitted successfully.');
  }

  private function getLoggedStudentProfile()
  {
    $user = Auth::user();

    if (!$user) {
      return null;
    }

    return DB::table('tbl_student')
      ->where('stu_email', $user->email)
      ->where('del_status', 'N')
      ->where('is_block', 'N')
      ->first();
  }

  private function studentMatchesForm($studentProfile, $form): bool
  {
    return $this->matchSelectedIds($studentProfile->stu_session ?? null, $form->session_ids ?? [])
      && $this->matchSelectedIds($studentProfile->stu_course ?? null, $form->programme_ids ?? [])
      && $this->matchSelectedIds($studentProfile->stu_branch ?? null, $form->technology_ids ?? [])
      && $this->matchSelectedIds($studentProfile->stu_pro ?? null, $form->batch_ids ?? []);
  }

  private function matchSelectedIds($studentValue, $formValues): bool
  {
    if (!is_array($formValues) || empty($formValues)) {
      return true;
    }

    if (in_array('all', $formValues, true)) {
      return true;
    }

    $normalized = array_map('intval', $formValues);

    return in_array((int) $studentValue, $normalized, true);
  }
}
