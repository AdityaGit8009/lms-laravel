<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class ContentBundleController extends Controller
{

  public function index(Request $request): View
  {
    $subjects = DB::table('tbl_subject')
      ->orderBy('subject_name', 'asc')
      ->get();

    $selectedSubjectId = $request->filled('subject_id') ? (int) $request->subject_id : null;

    $selectedTopicIdsRaw = $request->input('topic_ids', []);
    if (!is_array($selectedTopicIdsRaw)) {
      $selectedTopicIdsRaw = [$selectedTopicIdsRaw];
    }

    $selectedTopicIdsRaw = array_values(array_filter($selectedTopicIdsRaw, function ($value) {
      return $value !== null && $value !== '';
    }));

    $oldTopics = collect();

    if (!empty($selectedSubjectId)) {
      $topicQuery = DB::table('tbl_topic')
        ->where('subject_id', $selectedSubjectId);

      if (Schema::hasColumn('tbl_topic', 'status')) {
        $topicQuery->where('status', 1);
      }

      $oldTopics = $topicQuery
        ->orderBy('topic_name', 'asc')
        ->get(['topic_id', 'topic_name']);
    }

    $hasSearched = !empty($selectedSubjectId);
    $bundles = collect();

    if ($hasSearched) {
      $hasAllSelected = in_array('all', $selectedTopicIdsRaw, true);

      if ($hasAllSelected || empty($selectedTopicIdsRaw)) {
        $selectedTopicIds = $oldTopics->pluck('topic_id')
          ->map(fn($id) => (int) $id)
          ->values()
          ->all();
      } else {
        $selectedTopicIds = array_values(array_filter(array_map(function ($id) {
          return is_numeric($id) ? (int) $id : null;
        }, $selectedTopicIdsRaw)));
      }

      if (!empty($selectedTopicIds)) {
        $uploadKeys = DB::table('tbl_upload_content')
          ->select('subject_id', 'topic_id');

        $assignmentKeys = DB::table('tbl_assignments')
          ->select('subject_id', 'topic_id');

        $recordingKeys = DB::table('tbl_recordings')
          ->select('subject_id', 'topic_id');

        $bundleKeys = $uploadKeys
          ->union($assignmentKeys)
          ->union($recordingKeys);

        $contentSummary = DB::table('tbl_upload_content')
          ->select(
            'subject_id',
            'topic_id',
            DB::raw('MAX(content_id) as latest_content_id'),
            DB::raw('MAX(updated_at) as content_updated_at')
          )
          ->groupBy('subject_id', 'topic_id');

        $assignmentSummary = DB::table('tbl_assignments')
          ->select(
            'subject_id',
            'topic_id',
            DB::raw('COUNT(*) as assignment_count'),
            DB::raw('MAX(updated_at) as assignment_updated_at')
          )
          ->groupBy('subject_id', 'topic_id');

        $recordingSummary = DB::table('tbl_recordings')
          ->select(
            'subject_id',
            'topic_id',
            DB::raw('COUNT(*) as recording_count'),
            DB::raw('MAX(updated_at) as recording_updated_at')
          )
          ->groupBy('subject_id', 'topic_id');

        $bundles = DB::query()
          ->fromSub($bundleKeys, 'bk')
          ->leftJoin('tbl_subject as s', 's.subject_id', '=', 'bk.subject_id')
          ->leftJoinSub($contentSummary, 'c', function ($join) {
            $join->on('c.subject_id', '=', 'bk.subject_id')
              ->on('c.topic_id', '=', 'bk.topic_id');
          })
          ->leftJoinSub($assignmentSummary, 'a', function ($join) {
            $join->on('a.subject_id', '=', 'bk.subject_id')
              ->on('a.topic_id', '=', 'bk.topic_id');
          })
          ->leftJoinSub($recordingSummary, 'r', function ($join) {
            $join->on('r.subject_id', '=', 'bk.subject_id')
              ->on('r.topic_id', '=', 'bk.topic_id');
          })
          ->select(
            'bk.subject_id',
            'bk.topic_id',
            's.subject_name',
            'c.latest_content_id',
            DB::raw('COALESCE(a.assignment_count, 0) as assignment_count'),
            DB::raw('COALESCE(r.recording_count, 0) as recording_count'),
            DB::raw("
                            GREATEST(
                                COALESCE(c.content_updated_at, '1970-01-01 00:00:00'),
                                COALESCE(a.assignment_updated_at, '1970-01-01 00:00:00'),
                                COALESCE(r.recording_updated_at, '1970-01-01 00:00:00')
                            ) as latest_updated_at
                        ")
          )
          ->where('bk.subject_id', $selectedSubjectId)
          ->whereIn('bk.topic_id', $selectedTopicIds)
          ->orderByDesc('latest_updated_at')
          ->get();
      }
    }

    return view('Admin.Content.list_view', compact(
      'subjects',
      'oldTopics',
      'bundles',
      'selectedSubjectId',
      'selectedTopicIdsRaw',
      'hasSearched'
    ));
  }

  public function getTopicsBySubject($subjectId): JsonResponse
  {
    $topicQuery = DB::table('tbl_topic')
      ->where('subject_id', $subjectId);

    if (Schema::hasColumn('tbl_topic', 'status')) {
      $topicQuery->where('status', 1);
    }

    $topics = $topicQuery
      ->orderBy('topic_name', 'asc')
      ->get(['topic_id', 'topic_name']);

    return response()->json([
      'status' => true,
      'topics' => $topics,
    ]);
  }

  public function show($subjectId, $topicId): View
  {
    $subject = DB::table('tbl_subject')
      ->where('subject_id', $subjectId)
      ->first();

    $topic = DB::table('tbl_topic')
      ->where('topic_id', $topicId)
      ->where('subject_id', $subjectId)
      ->first();

    if (!$subject || !$topic) {
      abort(404);
    }

    $content = DB::table('tbl_upload_content')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderByDesc('content_id')
      ->first();

    $assignments = DB::table('tbl_assignments')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderByDesc('assignment_id')
      ->get();

    $recordings = DB::table('tbl_recordings')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderByDesc('rec_id')
      ->get();

    $mockTest = DB::table('tbl_mock_tests')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->first();

    $onlineTest = DB::table('tbl_online_tests')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->first();

    $onlineQuestions = collect();

    if ($onlineTest) {
      $onlineQuestions = DB::table('tbl_online_test_questions')
        ->where('online_test_id', $onlineTest->online_test_id)
        ->orderBy('question_no', 'asc')
        ->get();
    }

    if (
      !$content &&
      $assignments->isEmpty() &&
      $recordings->isEmpty() &&
      !$mockTest &&
      !$onlineTest
    ) {
      abort(404);
    }

    return view('Admin.Content.view_content', compact(
      'subject',
      'topic',
      'content',
      'assignments',
      'recordings',
      'mockTest',
      'onlineTest',
      'onlineQuestions'
    ));
  }

  public function edit($subjectId, $topicId): View
  {
    $subject = DB::table('tbl_subject')
      ->where('subject_id', $subjectId)
      ->first();

    $topic = DB::table('tbl_topic')
      ->where('topic_id', $topicId)
      ->where('subject_id', $subjectId)
      ->first();

    if (!$subject || !$topic) {
      abort(404);
    }

    $content = DB::table('tbl_upload_content')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderByDesc('content_id')
      ->first();

    $assignments = DB::table('tbl_assignments')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderBy('assignment_id')
      ->get();

    $recordings = DB::table('tbl_recordings')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->orderBy('rec_id')
      ->get();

    $mockTest = DB::table('tbl_mock_tests')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->first();

    $onlineTest = DB::table('tbl_online_tests')
      ->where('subject_id', $subjectId)
      ->where('topic_id', $topicId)
      ->first();

    $onlineQuestions = collect();

    if ($onlineTest) {
      $onlineQuestions = DB::table('tbl_online_test_questions')
        ->where('online_test_id', $onlineTest->online_test_id)
        ->orderBy('question_no', 'asc')
        ->get();
    }

    $subjects = DB::table('tbl_subject')
      ->orderBy('subject_name')
      ->get();

    $oldTopics = DB::table('tbl_topic')
      ->where('subject_id', $subjectId)
      ->where('status', 1)
      ->orderBy('topic_name')
      ->get();

    $assignmentQuestions = $this->buildHtmlListFromRows(
      $assignments->pluck('Assignment_Ques')->filter()->values()->all()
    );

    $assignmentFile = $assignments->pluck('Assignment_file')->filter()->first();
    $assignmentMaxMarks = $assignments->pluck('max_marks')->filter(function ($value) {
      return $value !== null && $value !== '';
    })->first();
    $assignmentDueDate = $assignments->pluck('due_date')->filter()->first();

    $videoRecording = $this->buildHtmlListFromRows(
      $recordings->pluck('video_Recording')->filter()->values()->all()
    );

    $recordingMaxMarks = $recordings->pluck('max_marks')->filter(function ($value) {
      return $value !== null && $value !== '';
    })->first();
    $recordingDueDate = $recordings->pluck('due_date')->filter()->first();

    return view('Admin.Content.edit_content', compact(
      'subject',
      'topic',
      'content',
      'assignments',
      'recordings',
      'mockTest',
      'onlineTest',
      'onlineQuestions',
      'subjects',
      'oldTopics',
      'assignmentQuestions',
      'assignmentFile',
      'assignmentMaxMarks',
      'assignmentDueDate',
      'videoRecording',
      'recordingMaxMarks',
      'recordingDueDate'
    ));
  }

  public function update(Request $request, $subjectId, $topicId): RedirectResponse
  {
    $currentSubject = DB::table('tbl_subject')
      ->where('subject_id', $subjectId)
      ->first();

    $currentTopic = DB::table('tbl_topic')
      ->where('topic_id', $topicId)
      ->where('subject_id', $subjectId)
      ->first();

    if (!$currentSubject || !$currentTopic) {
      abort(404);
    }

    $validated = $request->validate([
      'subject_id' => ['required', 'integer', 'exists:tbl_subject,subject_id'],
      'topic_id' => ['required', 'integer', 'exists:tbl_topic,topic_id'],
      'title' => ['required', 'string', 'max:255'],
      'learning_objective' => ['nullable', 'string'],
      'content_body' => ['nullable', 'string'],
      'video_link' => ['nullable', 'string', 'max:255'],
      'trainer_task' => ['nullable', 'string'],
      'learning_outcomes' => ['nullable', 'string'],
      'resource_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png,mp4', 'max:10240'],

      'assignment_questions' => ['nullable', 'string'],
      'assignment_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png', 'max:10240'],
      'max_marks' => ['nullable', 'numeric', 'min:0'],
      'due_date' => ['nullable', 'date'],

      'video_recording' => ['nullable', 'string'],
      'recording_max_marks' => ['nullable', 'numeric', 'min:0'],
      'recording_due_date' => ['nullable', 'date'],

      'mock_test_enabled' => ['nullable', 'in:yes,no'],
      'mock_test_marks' => ['nullable', 'numeric', 'min:0'],
      'mock_test' => ['nullable', 'string'],

      'online_test_enabled' => ['nullable', 'in:yes,no'],
      'online_questions' => ['nullable', 'array'],
      'online_questions.*.otq_id' => ['nullable', 'integer'],
      'online_questions.*.marks' => ['nullable', 'numeric', 'min:0'],
      'online_questions.*.question' => ['nullable', 'string'],
      'online_questions.*.option_a' => ['nullable', 'string'],
      'online_questions.*.option_b' => ['nullable', 'string'],
      'online_questions.*.option_c' => ['nullable', 'string'],
      'online_questions.*.option_d' => ['nullable', 'string'],
      'online_questions.*.correct_option' => ['nullable', 'in:A,B,C,D'],
    ]);

    $topicBelongsToSubject = DB::table('tbl_topic')
      ->where('topic_id', $validated['topic_id'])
      ->where('subject_id', $validated['subject_id'])
      ->exists();

    if (!$topicBelongsToSubject) {
      return back()
        ->withErrors(['topic_id' => 'Selected topic does not belong to selected subject.'])
        ->withInput();
    }

    $newSubjectId = (int) $validated['subject_id'];
    $newTopicId = (int) $validated['topic_id'];
    $oldSubjectId = (int) $subjectId;
    $oldTopicId = (int) $topicId;

    $existingContent = DB::table('tbl_upload_content')
      ->where('subject_id', $oldSubjectId)
      ->where('topic_id', $oldTopicId)
      ->orderByDesc('content_id')
      ->first();

    $existingAssignments = DB::table('tbl_assignments')
      ->where('subject_id', $oldSubjectId)
      ->where('topic_id', $oldTopicId)
      ->orderBy('assignment_id')
      ->get();

    $existingRecordings = DB::table('tbl_recordings')
      ->where('subject_id', $oldSubjectId)
      ->where('topic_id', $oldTopicId)
      ->orderBy('rec_id')
      ->get();

    $existingMockTest = DB::table('tbl_mock_tests')
      ->where('subject_id', $oldSubjectId)
      ->where('topic_id', $oldTopicId)
      ->first();

    $existingOnlineTest = DB::table('tbl_online_tests')
      ->where('subject_id', $oldSubjectId)
      ->where('topic_id', $oldTopicId)
      ->first();

    $existingOnlineQuestions = collect();

    if ($existingOnlineTest) {
      $existingOnlineQuestions = DB::table('tbl_online_test_questions')
        ->where('online_test_id', $existingOnlineTest->online_test_id)
        ->orderBy('question_no', 'asc')
        ->get()
        ->keyBy('otq_id');
    }

    if ($newSubjectId !== $oldSubjectId || $newTopicId !== $oldTopicId) {
      $bundleExists =
        DB::table('tbl_upload_content')
        ->where('subject_id', $newSubjectId)
        ->where('topic_id', $newTopicId)
        ->exists()
        || DB::table('tbl_assignments')
        ->where('subject_id', $newSubjectId)
        ->where('topic_id', $newTopicId)
        ->exists()
        || DB::table('tbl_recordings')
        ->where('subject_id', $newSubjectId)
        ->where('topic_id', $newTopicId)
        ->exists()
        || DB::table('tbl_mock_tests')
        ->where('subject_id', $newSubjectId)
        ->where('topic_id', $newTopicId)
        ->exists()
        || DB::table('tbl_online_tests')
        ->where('subject_id', $newSubjectId)
        ->where('topic_id', $newTopicId)
        ->exists();

      if ($bundleExists) {
        return back()
          ->withErrors([
            'topic_id' => 'This subject-topic bundle already exists. Please edit that bundle or select another topic.'
          ])
          ->withInput();
      }
    }

    DB::transaction(function () use (
      $request,
      $validated,
      $existingContent,
      $existingAssignments,
      $existingRecordings,
      $existingMockTest,
      $existingOnlineTest,
      $existingOnlineQuestions,
      $newSubjectId,
      $newTopicId
    ) {
      $resourceFileName = $existingContent->resource_file ?? null;

      if ($request->hasFile('resource_file')) {
        $resourceFileName = $this->uploadFile(
          $request->file('resource_file'),
          'uploads/content/resources'
        );

        if (!empty($existingContent->resource_file)) {
          $this->deleteFileIfExists('uploads/content/resources', $existingContent->resource_file);
        }
      }

      $actor = auth()->check() ? (auth()->user()->name ?? 'Admin') : 'Admin';
      $mockEnabled = $this->normalizeYesNo($validated['mock_test_enabled'] ?? 'no');
      $onlineEnabled = $this->normalizeYesNo($validated['online_test_enabled'] ?? 'no');

      $contentData = [
        'subject_id' => $newSubjectId,
        'topic_id' => $newTopicId,
        'title' => $validated['title'],
        'learning_objective' => $validated['learning_objective'] ?? null,
        'content_body' => $validated['content_body'] ?? null,
        'resource_file' => $resourceFileName,
        'video_link' => $validated['video_link'] ?? null,
        'trainer_task' => $validated['trainer_task'] ?? null,
        'learning_outcomes' => $validated['learning_outcomes'] ?? null,
        'updated_by' => $actor,
        'updated_at' => now(),
      ];

      if ($this->contentColumnExists('mock_test_enabled')) {
        $contentData['mock_test_enabled'] = $mockEnabled;
      }

      if ($this->contentColumnExists('online_test_enabled')) {
        $contentData['online_test_enabled'] = $onlineEnabled;
      }

      if ($this->contentColumnExists('mock_test_marks')) {
        $contentData['mock_test_marks'] = $mockEnabled === 'yes'
          ? ($validated['mock_test_marks'] ?? null)
          : null;
      }

      if ($this->contentColumnExists('mock_test')) {
        $contentData['mock_test'] = $mockEnabled === 'yes'
          ? ($validated['mock_test'] ?? null)
          : null;
      }

      if ($existingContent) {
        DB::table('tbl_upload_content')
          ->where('content_id', $existingContent->content_id)
          ->update($contentData);
      } else {
        $contentData['created_by'] = $actor;
        $contentData['created_at'] = now();

        DB::table('tbl_upload_content')->insert($contentData);
      }

      $this->syncAssignmentsFromGroupedEditor(
        $request,
        $validated,
        $existingAssignments,
        $newSubjectId,
        $newTopicId
      );

      $this->syncRecordingsFromGroupedEditor(
        $validated,
        $existingRecordings,
        $newSubjectId,
        $newTopicId
      );

      $this->syncMockTest(
        $validated,
        $existingMockTest,
        $newSubjectId,
        $newTopicId,
        $mockEnabled
      );

      $this->syncOnlineTest(
        $validated,
        $existingOnlineTest,
        $existingOnlineQuestions,
        $newSubjectId,
        $newTopicId,
        $onlineEnabled
      );
    });

    return redirect()
      ->route('admin.content-bundles.show', [$newSubjectId, $newTopicId])
      ->with('success', 'Complete bundle updated successfully.');
  }

  private function syncAssignmentsFromGroupedEditor(
    Request $request,
    array $validated,
    Collection $existingAssignments,
    int $subjectId,
    int $topicId
  ): void {
    $assignmentItems = $this->extractHtmlItems($validated['assignment_questions'] ?? null);
    $maxMarks = ($validated['max_marks'] ?? '') !== '' ? $validated['max_marks'] : null;
    $dueDate = !empty($validated['due_date']) ? $validated['due_date'] : null;

    $existingFileNames = $existingAssignments->pluck('Assignment_file')
      ->filter()
      ->unique()
      ->values()
      ->all();

    $assignmentFileName = $existingAssignments->pluck('Assignment_file')->filter()->first();

    if ($request->hasFile('assignment_file')) {
      $assignmentFileName = $this->uploadFile(
        $request->file('assignment_file'),
        'uploads/assignments'
      );

      foreach ($existingFileNames as $oldFile) {
        $this->deleteFileIfExists('uploads/assignments', $oldFile);
      }
    }

    if ($existingAssignments->isNotEmpty()) {
      DB::table('tbl_assignments')
        ->whereIn('assignment_id', $existingAssignments->pluck('assignment_id')->all())
        ->delete();
    }

    if (empty($assignmentItems)) {
      if (!$request->hasFile('assignment_file')) {
        foreach ($existingFileNames as $oldFile) {
          $this->deleteFileIfExists('uploads/assignments', $oldFile);
        }
      } elseif (!empty($assignmentFileName)) {
        $this->deleteFileIfExists('uploads/assignments', $assignmentFileName);
      }

      return;
    }

    foreach ($assignmentItems as $item) {
      DB::table('tbl_assignments')->insert([
        'subject_id' => $subjectId,
        'topic_id' => $topicId,
        'Assignment_Ques' => $item,
        'Assignment_file' => $assignmentFileName,
        'max_marks' => $maxMarks,
        'due_date' => $dueDate,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }

  private function syncRecordingsFromGroupedEditor(
    array $validated,
    Collection $existingRecordings,
    int $subjectId,
    int $topicId
  ): void {
    $recordingItems = $this->extractHtmlItems($validated['video_recording'] ?? null);
    $maxMarks = ($validated['recording_max_marks'] ?? '') !== '' ? $validated['recording_max_marks'] : null;
    $dueDate = !empty($validated['recording_due_date']) ? $validated['recording_due_date'] : null;

    if ($existingRecordings->isNotEmpty()) {
      DB::table('tbl_recordings')
        ->whereIn('rec_id', $existingRecordings->pluck('rec_id')->all())
        ->delete();
    }

    if (empty($recordingItems)) {
      return;
    }

    foreach ($recordingItems as $item) {
      DB::table('tbl_recordings')->insert([
        'subject_id' => $subjectId,
        'topic_id' => $topicId,
        'video_Recording' => $item,
        'max_marks' => $maxMarks,
        'due_date' => $dueDate,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }

  private function syncMockTest(
    array $validated,
    $existingMockTest,
    int $subjectId,
    int $topicId,
    string $mockEnabled
  ): void {
    $mockHtml = trim((string) ($validated['mock_test'] ?? ''));
    $mockPlain = trim(strip_tags($mockHtml));
    $mockMarks = ($validated['mock_test_marks'] ?? '') !== '' ? $validated['mock_test_marks'] : null;

    if ($mockEnabled !== 'yes') {
      if ($existingMockTest) {
        DB::table('tbl_mock_tests')
          ->where('mock_id', $existingMockTest->mock_id)
          ->delete();
      }
      return;
    }

    if ($mockPlain === '') {
      throw ValidationException::withMessages([
        'mock_test' => 'Please enter mock test content.',
      ]);
    }

    if ($mockMarks === null) {
      throw ValidationException::withMessages([
        'mock_test_marks' => 'Please enter mock test marks.',
      ]);
    }

    $mockData = [
      'subject_id' => $subjectId,
      'topic_id' => $topicId,
      'mock_test' => $mockHtml,
      'marks' => $mockMarks,
      'updated_at' => now(),
    ];

    if ($existingMockTest) {
      DB::table('tbl_mock_tests')
        ->where('mock_id', $existingMockTest->mock_id)
        ->update($mockData);
    } else {
      $mockData['created_at'] = now();
      DB::table('tbl_mock_tests')->insert($mockData);
    }
  }

  private function syncOnlineTest(
    array $validated,
    $existingOnlineTest,
    Collection $existingOnlineQuestions,
    int $subjectId,
    int $topicId,
    string $onlineEnabled
  ): void {
    $onlineRows = $validated['online_questions'] ?? [];
    $preparedOnlineRows = [];

    foreach ($onlineRows as $index => $row) {
      $otqId = !empty($row['otq_id']) ? (int) $row['otq_id'] : null;
      $questionHtml = trim((string) ($row['question'] ?? ''));
      $questionPlain = trim(strip_tags($questionHtml));
      $marks = ($row['marks'] ?? '') !== '' ? $row['marks'] : null;
      $optionA = trim((string) ($row['option_a'] ?? ''));
      $optionB = trim((string) ($row['option_b'] ?? ''));
      $optionC = trim((string) ($row['option_c'] ?? ''));
      $optionD = trim((string) ($row['option_d'] ?? ''));
      $correctOption = !empty($row['correct_option']) ? strtoupper(trim((string) $row['correct_option'])) : null;

      $isBlank = $questionPlain === ''
        && $optionA === ''
        && $optionB === ''
        && $optionC === ''
        && $optionD === ''
        && $marks === null
        && !$otqId
        && $correctOption === null;

      if ($isBlank) {
        continue;
      }

      if ($questionPlain === '') {
        throw ValidationException::withMessages([
          "online_questions.$index.question" => 'Question text is required.',
        ]);
      }

      if ($marks === null || !is_numeric($marks)) {
        throw ValidationException::withMessages([
          "online_questions.$index.marks" => 'Question marks are required.',
        ]);
      }

      if ($optionA === '' || $optionB === '' || $optionC === '' || $optionD === '') {
        throw ValidationException::withMessages([
          "online_questions.$index.options" => 'All 4 options are required.',
        ]);
      }

      if (!in_array($correctOption, ['A', 'B', 'C', 'D'], true)) {
        throw ValidationException::withMessages([
          "online_questions.$index.correct_option" => 'Correct option must be A, B, C, or D.',
        ]);
      }

      $preparedOnlineRows[] = [
        'otq_id' => $otqId,
        'marks' => (int) $marks,
        'question' => $questionHtml,
        'option_a' => $optionA,
        'option_b' => $optionB,
        'option_c' => $optionC,
        'option_d' => $optionD,
        'correct_option' => $correctOption,
      ];
    }

    if ($onlineEnabled !== 'yes') {
      if ($existingOnlineTest) {
        DB::table('tbl_online_test_questions')
          ->where('online_test_id', $existingOnlineTest->online_test_id)
          ->delete();

        DB::table('tbl_online_tests')
          ->where('online_test_id', $existingOnlineTest->online_test_id)
          ->delete();
      }

      return;
    }

    if (empty($preparedOnlineRows)) {
      throw ValidationException::withMessages([
        'online_questions' => 'Please add at least one online test question.',
      ]);
    }

    $totalQuestions = count($preparedOnlineRows);
    $onlineTestId = null;

    if ($existingOnlineTest) {
      $updateData = [
        'subject_id' => $subjectId,
        'topic_id' => $topicId,
        'updated_at' => now(),
      ];

      if ($this->onlineTestColumnExists('total_questions')) {
        $updateData['total_questions'] = $totalQuestions;
      }

      DB::table('tbl_online_tests')
        ->where('online_test_id', $existingOnlineTest->online_test_id)
        ->update($updateData);

      $onlineTestId = $existingOnlineTest->online_test_id;
    } else {
      $insertData = [
        'subject_id' => $subjectId,
        'topic_id' => $topicId,
        'created_at' => now(),
        'updated_at' => now(),
      ];

      if ($this->onlineTestColumnExists('total_questions')) {
        $insertData['total_questions'] = $totalQuestions;
      }

      $onlineTestId = DB::table('tbl_online_tests')->insertGetId($insertData);
    }

    $submittedOnlineIds = [];
    $questionNo = 1;

    foreach ($preparedOnlineRows as $row) {
      $otqId = $row['otq_id'];

      $questionData = [
        'online_test_id' => $onlineTestId,
        'question_no' => $questionNo,
        'marks' => $row['marks'],
        'question' => $row['question'],
        'option_a' => $row['option_a'],
        'option_b' => $row['option_b'],
        'option_c' => $row['option_c'],
        'option_d' => $row['option_d'],
        'correct_option' => $row['correct_option'],
        'updated_at' => now(),
      ];

      if ($otqId && $existingOnlineQuestions->has($otqId)) {
        DB::table('tbl_online_test_questions')
          ->where('otq_id', $otqId)
          ->update($questionData);

        $submittedOnlineIds[] = $otqId;
      } else {
        $questionData['created_at'] = now();
        $newOtqId = DB::table('tbl_online_test_questions')->insertGetId($questionData);
        $submittedOnlineIds[] = $newOtqId;
      }

      $questionNo++;
    }

    $deleteOnlineIds = $existingOnlineQuestions->keys()
      ->diff($submittedOnlineIds)
      ->values();

    if ($deleteOnlineIds->isNotEmpty()) {
      DB::table('tbl_online_test_questions')
        ->whereIn('otq_id', $deleteOnlineIds->all())
        ->delete();
    }

    if ($this->onlineTestColumnExists('total_questions')) {
      DB::table('tbl_online_tests')
        ->where('online_test_id', $onlineTestId)
        ->update([
          'total_questions' => count($submittedOnlineIds),
          'updated_at' => now(),
        ]);
    }
  }

  private function buildHtmlListFromRows(array $rows): string
  {
    $items = array_values(array_filter(array_map(function ($row) {
      return $this->cleanHtmlFragment($row);
    }, $rows), function ($row) {
      return $row !== '';
    }));

    if (empty($items)) {
      return '';
    }

    $html = '<ol>';

    foreach ($items as $item) {
      $html .= '<li>' . $item . '</li>';
    }

    $html .= '</ol>';

    return $html;
  }

  private function extractHtmlItems(?string $html): array
  {
    $html = trim((string) $html);

    if ($html === '') {
      return [];
    }

    if (preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $html, $matches)) {
      return array_values(array_filter(array_map(function ($item) {
        return $this->cleanHtmlFragment($item);
      }, $matches[1]), function ($item) {
        return $item !== '';
      }));
    }

    $prepared = preg_replace('/<br\s*\/?>/i', '[[SPLIT]]', $html);
    $prepared = preg_replace('/<\/(p|div|h[1-6])>/i', '[[SPLIT]]', $prepared);
    $prepared = preg_replace('/<(p|div|h[1-6])[^>]*>/i', '', $prepared);

    $parts = explode('[[SPLIT]]', $prepared);
    $items = [];

    foreach ($parts as $part) {
      $clean = $this->cleanHtmlFragment($part);

      if ($clean !== '') {
        $items[] = $clean;
      }
    }

    return $items;
  }

  private function cleanHtmlFragment(?string $html): string
  {
    $html = trim((string) $html);

    if ($html === '') {
      return '';
    }

    $html = str_replace('&nbsp;', ' ', $html);
    $html = preg_replace('/^\s*(\d+[\.\)]|[-•])\s*/u', '', $html);
    $html = preg_replace('/\s+/u', ' ', $html);

    return trim($html);
  }

  private function uploadFile(UploadedFile $file, string $folder): string
  {
    $destinationPath = public_path($folder);

    if (!File::exists($destinationPath)) {
      File::makeDirectory($destinationPath, 0755, true, true);
    }

    $fileName = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
    $file->move($destinationPath, $fileName);

    return $fileName;
  }

  private function deleteFileIfExists(string $folder, ?string $fileName): void
  {
    if (empty($fileName)) {
      return;
    }

    $fullPath = public_path($folder . '/' . $fileName);

    if (File::exists($fullPath)) {
      File::delete($fullPath);
    }
  }

  private function normalizeYesNo(?string $value): string
  {
    return strtolower((string) $value) === 'yes' ? 'yes' : 'no';
  }

  private function contentColumnExists(string $column): bool
  {
    return Schema::hasColumn('tbl_upload_content', $column);
  }

  private function onlineTestColumnExists(string $column): bool
  {
    return Schema::hasColumn('tbl_online_tests', $column);
  }
}
