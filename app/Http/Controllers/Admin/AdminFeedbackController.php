<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackForm;
use App\Models\FeedbackResponse;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminFeedbackController extends Controller
{
  public function create()
  {
    return view('Admin.feedback.builder', [
      'form' => null,
      'sessions' => Session::orderByDesc('session_id')->get(),
    ]);
  }

  public function edit($id)
  {
    $form = FeedbackForm::findOrFail($id);

    return view('Admin.feedback.builder', [
      'form' => $form,
      'sessions' => Session::orderByDesc('session_id')->get(),
    ]);
  }

  public function delete($id)
  {
    $form = FeedbackForm::findOrFail($id);
    $form->delete();

    return redirect()
      ->route('admin.feedback.manage')
      ->with('success', 'Feedback form deleted successfully.');
  }

  public function store(Request $request)
  {
    $validated = $this->validateForm($request);

    FeedbackForm::create([
      'public_key'      => (string) Str::uuid(),
      'title'           => $validated['title'],
      'description'     => $validated['description'] ?? null,
      'session_ids'     => $this->normalizeMulti($validated['session_ids'] ?? []),
      'center_ids'      => $this->normalizeMulti($validated['center_ids'] ?? []),
      'programme_ids'   => $this->normalizeMulti($validated['programme_ids'] ?? []),
      'technology_ids'  => $this->normalizeMulti($validated['technology_ids'] ?? []),
      'batch_ids'       => $this->normalizeMulti($validated['batch_ids'] ?? []),
      'form_status'     => $validated['form_status'],
      'questions_json'  => json_decode($validated['questions_json'], true),
      'created_by'      => Auth::id(),
    ]);

    return redirect()->route('admin.feedback.manage')->with('success', 'Feedback form created successfully.');
  }

  public function update(Request $request, $id)
  {
    $form = FeedbackForm::findOrFail($id);
    $validated = $this->validateForm($request);

    $form->update([
      'title'           => $validated['title'],
      'description'     => $validated['description'] ?? null,
      'session_ids'     => $this->normalizeMulti($validated['session_ids'] ?? []),
      'center_ids'      => $this->normalizeMulti($validated['center_ids'] ?? []),
      'programme_ids'   => $this->normalizeMulti($validated['programme_ids'] ?? []),
      'technology_ids'  => $this->normalizeMulti($validated['technology_ids'] ?? []),
      'batch_ids'       => $this->normalizeMulti($validated['batch_ids'] ?? []),
      'form_status'     => $validated['form_status'],
      'questions_json'  => json_decode($validated['questions_json'], true),
    ]);

    return redirect()->route('admin.feedback.manage')->with('success', 'Feedback form updated successfully.');
  }

  public function copy($id)
  {
    $form = FeedbackForm::findOrFail($id);

    $newForm = $form->replicate();
    $newForm->public_key = (string) Str::uuid();
    $newForm->title = $form->title . ' (Copy)';
    $newForm->created_at = now();
    $newForm->updated_at = now();
    $newForm->save();

    return redirect()->route('admin.feedback.manage')
      ->with('success', 'Feedback form copied successfully.');
  }

  public function manage()
  {
    $forms = FeedbackForm::latest('form_id')->get()->map(function ($form) {
      $form->session_names = $this->resolveNames('tbl_session', 'session_id', 'session_name', $form->session_ids);
      $form->center_names = $this->resolveNames('tbl_centers', 'center_id', 'center_name', $form->center_ids);
      $form->programme_names = $this->resolveNames('tbl_programme', 'programme_id', 'programme_name', $form->programme_ids);
      $form->technology_names = $this->resolveNames('tbl_technology', 'technology_id', 'technology_name', $form->technology_ids);
      $form->batch_names = $this->resolveNames('tbl_batch', 'batch_id', 'batch_name', $form->batch_ids);
      return $form;
    });

    return view('Admin.feedback.manage', compact('forms'));
  }

  // public function responsePage($id)
  // {
  //   $form = FeedbackForm::with('responses')->findOrFail($id);

  //   $form->session_names = $this->resolveNames('tbl_session', 'session_id', 'session_name', $form->session_ids);
  //   $form->center_names = $this->resolveNames('tbl_centers', 'center_id', 'center_name', $form->center_ids);
  //   $form->programme_names = $this->resolveNames('tbl_programme', 'programme_id', 'programme_name', $form->programme_ids);
  //   $form->technology_names = $this->resolveNames('tbl_technology', 'technology_id', 'technology_name', $form->technology_ids);
  //   $form->batch_names = $this->resolveNames('tbl_batch', 'batch_id', 'batch_name', $form->batch_ids);

  //   return view('Admin.feedback.response_page', compact('form'));
  // }

  public function cascadeCenters(Request $request)
  {
    $sessionIds = $this->filterIds($request->input('session_ids', []));

    $centers = DB::table('tbl_programme_centers as pc')
      ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
      ->when(!empty($sessionIds), function ($q) use ($sessionIds) {
        $q->whereIn('pc.session_id', $sessionIds);
      })
      ->select('c.center_id as id', 'c.center_name as text')
      ->distinct()
      ->orderBy('c.center_name')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $centers
    ]);
  }

  public function cascadeProgrammes(Request $request)
  {
    $sessionIds = $this->filterIds($request->input('session_ids', []));
    $centerIds = $this->filterIds($request->input('center_ids', []));

    $programmes = DB::table('tbl_programme_centers as pc')
      ->join('tbl_programme as p', 'p.programme_id', '=', 'pc.programme_id')
      ->when(!empty($sessionIds), function ($q) use ($sessionIds) {
        $q->whereIn('pc.session_id', $sessionIds);
      })
      ->when(!empty($centerIds), function ($q) use ($centerIds) {
        $q->whereIn('pc.center_id', $centerIds);
      })
      ->select('p.programme_id as id', 'p.programme_name as text')
      ->distinct()
      ->orderBy('p.programme_name')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $programmes
    ]);
  }

  public function cascadeTechnologies(Request $request)
  {
    $sessionIds = $this->filterIds($request->input('session_ids', []));
    $programmeIds = $this->filterIds($request->input('programme_ids', []));

    $technologies = DB::table('tbl_technology')
      ->when(!empty($sessionIds), function ($q) use ($sessionIds) {
        $q->whereIn('session_id', $sessionIds);
      })
      ->when(!empty($programmeIds), function ($q) use ($programmeIds) {
        $q->whereIn('programme_id', $programmeIds);
      })
      ->select('technology_id as id', 'technology_name as text')
      ->distinct()
      ->orderBy('technology_name')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $technologies
    ]);
  }

  public function cascadeBatches(Request $request)
  {
    $sessionIds = $this->filterIds($request->input('session_ids', []));
    $programmeIds = $this->filterIds($request->input('programme_ids', []));
    $technologyIds = $this->filterIds($request->input('technology_ids', []));

    $batches = DB::table('tbl_batch')
      ->when(!empty($sessionIds), function ($q) use ($sessionIds) {
        $q->whereIn('session_id', $sessionIds);
      })
      ->when(!empty($programmeIds), function ($q) use ($programmeIds) {
        $q->whereIn('programme_id', $programmeIds);
      })
      ->when(!empty($technologyIds), function ($q) use ($technologyIds) {
        $q->whereIn('technology_id', $technologyIds);
      })
      ->select('batch_id as id', 'batch_name as text')
      ->distinct()
      ->orderBy('batch_name')
      ->get();

    return response()->json([
      'success' => true,
      'data' => $batches
    ]);
  }

  public function overallAnalytics()
  {
    $responses = FeedbackResponse::latest('response_id')->get();

    $sessions = DB::table('tbl_session')
      ->orderByDesc('session_id')
      ->get(['session_id', 'session_name']);

    $forms = FeedbackForm::orderByDesc('form_id')->get(['form_id', 'title']);

    return view('Admin.feedback.overall_analytics', compact(
      'responses',
      'sessions',
      'forms'
    ));
  }

  public function filteredStudents(Request $request)
  {
    try {
      $filters = $this->extractFilters($request);

      $forms = $this->getFilteredForms($filters);
      $students = $this->getFilteredStudents($filters)->orderBy('s.stu_name')->get();

      $rows = collect();

      foreach ($students as $student) {
        $matchedForms = $forms->filter(function ($form) use ($student) {
          return $this->studentMatchesForm($student, $form);
        })->values();

        if ($matchedForms->isEmpty()) {
          $rows->push([
            'student_id'   => $student->stu_id,
            'form_id'      => null,
            'student_name' => $student->stu_name,
            'mobile'       => $student->stu_mobile,
            'submitted'    => false,
          ]);
          continue;
        }

        foreach ($matchedForms as $form) {
          $response = $this->findStudentResponse($student, $form->form_id);

          $rows->push([
            'student_id'   => $student->stu_id,
            'form_id'      => $form->form_id,
            'student_name' => $student->stu_name,
            'mobile'       => $student->stu_mobile,
            'submitted'    => $response ? true : false,
          ]);
        }
      }

      return response()->json([
        'success' => true,
        'data'    => $rows->values(),
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function studentResponse(Request $request)
  {
    try {
      $studentId = (int) $request->input('student_id');
      $formId = (int) $request->input('form_id');

      $student = DB::table('tbl_student as s')
        ->leftJoin('users as u', 'u.email', '=', 's.stu_email')
        ->where('s.stu_id', $studentId)
        ->select(
          's.stu_id',
          's.stu_name',
          's.stu_mobile',
          's.stu_email',
          'u.id as user_id'
        )
        ->first();

      if (!$student || !$formId) {
        return response()->json([
          'success' => false,
          'message' => 'Student or form not found.'
        ]);
      }

      $response = $this->findStudentResponse($student, $formId);

      if (!$response) {
        return response()->json([
          'success' => false,
          'message' => 'Response not found.'
        ]);
      }

      $answers = collect($response->answers_json ?? [])->map(function ($item) {
        return [
          'question' => $item['question'] ?? 'Question',
          'type'     => $item['type'] ?? 'text',
          'value'    => $item['value'] ?? '-',
        ];
      })->values();

      return response()->json([
        'success' => true,
        'data' => [
          'student' => [
            'name'   => $student->stu_name ?? '-',
            'mobile' => $student->stu_mobile ?? '-',
          ],
          'answers' => $answers,
        ]
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage()
      ], 500);
    }
  }

  public function overallData(Request $request)
  {
    try {
      $filters = $this->extractFilters($request);

      $forms = $this->getFilteredForms($filters);
      $students = $this->getFilteredStudents($filters)->get();

      $eligibleFormIds = [];
      $eligibleStudentMap = [];

      foreach ($students as $student) {
        foreach ($forms as $form) {
          if ($this->studentMatchesForm($student, $form)) {
            $eligibleFormIds[$form->form_id] = $form->form_id;
            $eligibleStudentMap[$student->stu_id] = $student;
          }
        }
      }

      if (empty($eligibleFormIds) || empty($eligibleStudentMap)) {
        return response()->json([
          'success' => true,
          'data' => [
            'students' => [],
            'questions' => [],
          ]
        ]);
      }

      $studentUserIds = collect($eligibleStudentMap)
        ->pluck('user_id')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

      $studentEmails = collect($eligibleStudentMap)
        ->pluck('stu_email')
        ->filter()
        ->unique()
        ->values()
        ->toArray();

      $responses = FeedbackResponse::whereIn('form_id', array_values($eligibleFormIds))
        ->where(function ($q) use ($studentUserIds, $studentEmails) {
          if (!empty($studentUserIds)) {
            $q->whereIn('user_id', $studentUserIds);
          }

          if (!empty($studentEmails)) {
            if (!empty($studentUserIds)) {
              $q->orWhereIn('email', $studentEmails)
                ->orWhereIn('respondent_email', $studentEmails);
            } else {
              $q->whereIn('email', $studentEmails)
                ->orWhereIn('respondent_email', $studentEmails);
            }
          }
        })
        ->orderBy('response_id')
        ->get();

      $studentsList = $responses->map(function ($response) use ($eligibleStudentMap) {
        $matchedStudent = collect($eligibleStudentMap)->first(function ($student) use ($response) {
          return $this->responseBelongsToStudent($response, $student);
        });

        return [
          'name'   => $matchedStudent->stu_name ?? ($response->name ?: ($response->username ?: 'Student')),
          'mobile' => $matchedStudent->stu_mobile ?? '-',
        ];
      })->unique(function ($item) {
        return strtolower($item['name'] . '|' . $item['mobile']);
      })->values()->toArray();

      $questionBucket = [];

      foreach ($responses as $response) {
        $matchedStudent = collect($eligibleStudentMap)->first(function ($student) use ($response) {
          return $this->responseBelongsToStudent($response, $student);
        });

        $studentName = $matchedStudent->stu_name ?? ($response->name ?: ($response->username ?: 'Student'));
        $studentMobile = $matchedStudent->stu_mobile ?? '-';

        foreach (($response->answers_json ?? []) as $answer) {
          $questionText = trim((string) ($answer['question'] ?? 'Question'));
          $type = strtolower(trim((string) ($answer['type'] ?? 'text')));
          $values = $this->normalizeAnswerValue($answer['value'] ?? null);

          if (!isset($questionBucket[$questionText])) {
            $questionBucket[$questionText] = [
              'question' => $questionText,
              'type' => $type,
              'raw_counts' => [],
              'student_responses' => [],
              'response_count' => 0,
            ];
          }

          if (in_array($type, ['paragraph', 'short_answer', 'text'], true)) {
            $questionBucket[$questionText]['student_responses'][] = [
              'name' => $studentName,
              'mobile' => $studentMobile,
              'response' => implode(', ', $values ?: ['-']),
            ];
            continue;
          }

          $questionBucket[$questionText]['response_count']++;

          foreach ($values as $val) {
            if (!isset($questionBucket[$questionText]['raw_counts'][$val])) {
              $questionBucket[$questionText]['raw_counts'][$val] = 0;
            }
            $questionBucket[$questionText]['raw_counts'][$val]++;
          }
        }
      }

      $questions = [];

      foreach ($questionBucket as $item) {
        if (in_array($item['type'], ['paragraph', 'short_answer', 'text'], true)) {
          $questions[] = [
            'question' => $item['question'],
            'type' => $item['type'],
            'student_responses' => $item['student_responses'],
          ];
          continue;
        }

        $total = max(1, (int) $item['response_count']);
        $optionStats = [];

        foreach ($item['raw_counts'] as $option => $count) {
          $optionStats[] = [
            'option' => $option,
            'count' => $count,
            'percentage' => round(($count / $total) * 100, 2),
          ];
        }

        $questions[] = [
          'question' => $item['question'],
          'type' => $item['type'],
          'option_stats' => $optionStats,
        ];
      }

      return response()->json([
        'success' => true,
        'data' => [
          'students' => $studentsList,
          'questions' => array_values($questions),
        ]
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  private function extractFilters(Request $request): array
  {
    return [
      'session_ids'    => $this->filterIds($request->input('session_ids', [])),
      'center_ids'     => $this->filterIds($request->input('center_ids', [])),
      'programme_ids'  => $this->filterIds($request->input('programme_ids', [])),
      'technology_ids' => $this->filterIds($request->input('technology_ids', [])),
      'batch_ids'      => $this->filterIds($request->input('batch_ids', [])),
      'form_ids'       => $this->filterIds($request->input('form_ids', [])),
    ];
  }

  private function getFilteredForms(array $filters)
  {
    $forms = FeedbackForm::query()
      ->when(!empty($filters['form_ids']), function ($q) use ($filters) {
        $q->whereIn('form_id', $filters['form_ids']);
      })
      ->where('form_status', 'open')
      ->orderByDesc('form_id')
      ->get();

    return $forms->filter(function ($form) use ($filters) {
      return $this->formMatchesFilters($form, $filters);
    })->values();
  }

  private function getFilteredStudents(array $filters)
  {
    $query = DB::table('tbl_student as s')
      ->leftJoin('users as u', 'u.email', '=', 's.stu_email')
      ->select(
        's.stu_id',
        's.stu_name',
        's.stu_mobile',
        's.stu_email',
        's.center_id',
        's.stu_course as programme_id',
        's.stu_branch as technology_id',
        's.stu_pro as batch_id',
        's.stu_session as session_id',
        'u.id as user_id'
      )
      ->where('s.del_status', 'N')
      ->where('s.is_block', 'N');

    if (!empty($filters['session_ids'])) {
      $query->whereIn('s.stu_session', $filters['session_ids']);
    }

    if (!empty($filters['center_ids'])) {
      $query->whereIn('s.center_id', $filters['center_ids']);
    }

    if (!empty($filters['programme_ids'])) {
      $query->whereIn('s.stu_course', $filters['programme_ids']);
    }

    if (!empty($filters['technology_ids'])) {
      $query->whereIn('s.stu_branch', $filters['technology_ids']);
    }

    if (!empty($filters['batch_ids'])) {
      $query->whereIn('s.stu_pro', $filters['batch_ids']);
    }

    return $query;
  }

  private function studentMatchesForm($student, $form): bool
  {
    return $this->matchSelectedIds($student->session_id, $form->session_ids ?? [])
      && $this->matchSelectedIds($student->center_id, $form->center_ids ?? [])
      && $this->matchSelectedIds($student->programme_id, $form->programme_ids ?? [])
      && $this->matchSelectedIds($student->technology_id, $form->technology_ids ?? [])
      && $this->matchSelectedIds($student->batch_id, $form->batch_ids ?? []);
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

  private function formMatchesFilters($form, array $filters): bool
  {
    $map = [
      'session_ids'    => $filters['session_ids'] ?? [],
      'center_ids'     => $filters['center_ids'] ?? [],
      'programme_ids'  => $filters['programme_ids'] ?? [],
      'technology_ids' => $filters['technology_ids'] ?? [],
      'batch_ids'      => $filters['batch_ids'] ?? [],
    ];

    foreach ($map as $column => $selectedIds) {
      if (empty($selectedIds)) {
        continue;
      }

      $formValues = $form->{$column} ?? [];

      if (!is_array($formValues)) {
        $formValues = [];
      }

      if (in_array('all', $formValues, true)) {
        continue;
      }

      $formValues = array_map('intval', $formValues);

      if (empty(array_intersect($selectedIds, $formValues))) {
        return false;
      }
    }

    return true;
  }

  private function findStudentResponse($student, int $formId)
  {
    return FeedbackResponse::where('form_id', $formId)
      ->where(function ($q) use ($student) {
        if (!empty($student->user_id)) {
          $q->where('user_id', $student->user_id);
        }

        if (!empty($student->stu_email)) {
          if (!empty($student->user_id)) {
            $q->orWhere('email', $student->stu_email)
              ->orWhere('respondent_email', $student->stu_email);
          } else {
            $q->where('email', $student->stu_email)
              ->orWhere('respondent_email', $student->stu_email);
          }
        }
      })
      ->latest('response_id')
      ->first();
  }

  private function responseBelongsToStudent($response, $student): bool
  {
    if (!empty($student->user_id) && (int) $response->user_id === (int) $student->user_id) {
      return true;
    }

    $studentEmail = strtolower((string) ($student->stu_email ?? ''));
    $responseEmail = strtolower((string) ($response->email ?? ''));
    $respondentEmail = strtolower((string) ($response->respondent_email ?? ''));

    return $studentEmail !== '' && (
      $studentEmail === $responseEmail || $studentEmail === $respondentEmail
    );
  }

  private function resolveNames(string $table, string $idColumn, string $nameColumn, ?array $ids): string
  {
    if (empty($ids)) {
      return '-';
    }

    if (in_array('all', $ids, true)) {
      return 'All';
    }

    $names = DB::table($table)
      ->whereIn($idColumn, $ids)
      ->pluck($nameColumn)
      ->toArray();

    return !empty($names) ? implode(', ', $names) : '-';
  }

  private function normalizeAnswerValue($value): array
  {
    if (is_array($value)) {
      return array_values(array_filter(array_map(function ($v) {
        return trim((string) $v);
      }, $value)));
    }

    $value = trim((string) $value);

    if ($value === '') {
      return [];
    }

    return [$value];
  }

  private function validateForm(Request $request): array
  {
    return $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'session_ids' => 'nullable|array',
      'center_ids' => 'nullable|array',
      'programme_ids' => 'nullable|array',
      'technology_ids' => 'nullable|array',
      'batch_ids' => 'nullable|array',
      'form_status' => 'required|in:open,close',
      'questions_json' => 'required|string',
    ]);
  }

  private function normalizeMulti(array $items): array
  {
    $items = array_values(array_filter($items, function ($v) {
      return $v !== null && $v !== '';
    }));

    if (in_array('all', $items, true)) {
      return ['all'];
    }

    return array_map('intval', $items);
  }

  private function filterIds(array $items): array
  {
    $items = array_values(array_filter($items, function ($v) {
      return $v !== null && $v !== '' && $v !== 'all';
    }));

    return array_map('intval', $items);
  }

  public function formDetails($id)
  {
    try {
      $form = FeedbackForm::findOrFail($id);

      $form->session_names = $this->resolveNames('tbl_session', 'session_id', 'session_name', $form->session_ids);
      $form->center_names = $this->resolveNames('tbl_centers', 'center_id', 'center_name', $form->center_ids);
      $form->programme_names = $this->resolveNames('tbl_programme', 'programme_id', 'programme_name', $form->programme_ids);
      $form->technology_names = $this->resolveNames('tbl_technology', 'technology_id', 'technology_name', $form->technology_ids);
      $form->batch_names = $this->resolveNames('tbl_batch', 'batch_id', 'batch_name', $form->batch_ids);

      return response()->json([
        'success' => true,
        'data' => [
          'form_id' => $form->form_id,
          'title' => $form->title,
          'description' => $form->description,
          'form_status' => $form->form_status,
          'session_names' => $form->session_names,
          'center_names' => $form->center_names,
          'programme_names' => $form->programme_names,
          'technology_names' => $form->technology_names,
          'batch_names' => $form->batch_names,
          'questions_json' => $form->questions_json ?? [],
        ]
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
