<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\MockTest;
use App\Models\OnlineTest;
use App\Models\OnlineTestQuestion;
use App\Models\Recording;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\UploadContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UploadContentController extends Controller
{
  public function index(): View
  {
    $subjects = Subject::orderBy('subject_name', 'asc')->get();

    $oldTopics = collect();
    if (old('subject_id')) {
      $oldTopics = Topic::where('subject_id', old('subject_id'))
        ->where('status', 1)
        ->orderBy('topic_name', 'asc')
        ->get();
    }

    return view('Admin.Content.upload_content', compact('subjects', 'oldTopics'));
  }

  public function getTopicsBySubject($subjectId): JsonResponse
  {
    $topics = Topic::where('subject_id', $subjectId)
      ->where('status', 1)
      ->orderBy('topic_name', 'asc')
      ->get(['topic_id', 'topic_name']);

    return response()->json([
      'status' => true,
      'topics' => $topics,
    ]);
  }

  public function storeTopic(Request $request): JsonResponse
  {
    $request->validate([
      'subject_id' => ['required', 'exists:tbl_subject,subject_id'],
      'topic_name' => [
        'required',
        'string',
        'max:150',
        Rule::unique('tbl_topic', 'topic_name')->where(function ($query) use ($request) {
          return $query->where('subject_id', $request->subject_id);
        }),
      ],
    ]);

    $actor = Auth::check() ? (Auth::user()->name ?? 'Admin') : 'Admin';

    $topic = Topic::create([
      'subject_id' => $request->subject_id,
      'topic_name' => trim($request->topic_name),
      'status'     => 1,
      'created_by' => $actor,
      'updated_by' => $actor,
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Topic added successfully.',
      'topic'   => [
        'topic_id'   => $topic->topic_id,
        'topic_name' => $topic->topic_name,
      ],
    ]);
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'subject_id' => ['required', 'exists:tbl_subject,subject_id'],
      'topic_id'   => [
        'required',
        Rule::exists('tbl_topic', 'topic_id')->where(function ($query) use ($request) {
          return $query->where('subject_id', $request->subject_id)
            ->where('status', 1);
        }),
      ],
      'title'                 => ['required', 'string', 'max:255'],
      'learning_objective'    => ['nullable', 'string'],
      'content_body'          => ['nullable', 'string'],
      'resource_file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png,mp4', 'max:10240'],
      'video_link'            => ['nullable', 'string', 'max:255'],
      'trainer_task'          => ['nullable', 'string'],

      'assignment_questions'  => ['nullable', 'string', 'required_with:assignment_file,max_marks,due_date'],
      'assignment_file'       => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png', 'max:10240'],
      'max_marks'             => ['nullable', 'integer', 'min:0'],
      'due_date'              => ['nullable', 'date'],

      'video_recording'       => ['nullable', 'string', 'required_with:recording_max_marks,recording_due_date'],
      'recording_max_marks'   => ['nullable', 'integer', 'min:0'],
      'recording_due_date'    => ['nullable', 'date'],

      'mock_test_enabled'     => ['nullable', Rule::in(['yes', 'no'])],
      'mock_test'             => ['nullable', 'string'],
      'mock_test_marks'       => ['nullable', 'integer', 'min:0'],

      'online_test_enabled'   => ['nullable', Rule::in(['yes', 'no'])],
      'online_question_count' => ['nullable', 'integer', 'min:1'],
      'online_questions'      => ['nullable', 'array'],

      'learning_outcomes'     => ['nullable', 'string'],
    ]);

    $alreadyExists = UploadContent::where('subject_id', $request->subject_id)
      ->where('topic_id', $request->topic_id)
      ->exists();

    if ($alreadyExists) {
      return redirect()
        ->back()
        ->withInput()
        ->withErrors([
          'topic_id' => 'This topic content has already been uploaded for the selected subject.'
        ])
        ->with('error', 'This topic content has already been uploaded.');
    }

    DB::beginTransaction();

    try {
      $resourceFileName = $this->uploadFile($request, 'resource_file', 'uploads/content/resources');

      UploadContent::create([
        'subject_id'         => $request->subject_id,
        'topic_id'           => $request->topic_id,
        'title'              => trim($request->title),
        'learning_objective' => $request->learning_objective,
        'content_body'       => $request->content_body,
        'resource_file'      => $resourceFileName,
        'video_link'         => $request->video_link,
        'trainer_task'       => $request->trainer_task,
        'learning_outcomes'  => $request->learning_outcomes,
        'created_by'         => $this->actor(),
        'updated_by'         => $this->actor(),
      ]);

      $this->storeAssignments($request);
      $this->storeRecording($request);
      $this->storeMockTest($request);
      $this->storeOnlineTest($request);

      DB::commit();

      return redirect()
        ->route('admin.upload-content.index')
        ->with('success', 'Content, assignments, recording, mock test and online test saved successfully.');
    } catch (ValidationException $e) {
      DB::rollBack();
      throw $e;
    } catch (\Throwable $e) {
      DB::rollBack();

      return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Something went wrong: ' . $e->getMessage());
    }
  }

  private function storeAssignments(Request $request): void
  {
    $questions = $this->extractAssignmentQuestions($request->assignment_questions);

    if (empty($questions)) {
      return;
    }

    $assignmentFileName = $this->uploadFile($request, 'assignment_file', 'uploads/assignments');

    foreach ($questions as $questionHtml) {
      Assignment::create([
        'topic_id'        => $request->topic_id,
        'subject_id'      => $request->subject_id,
        'Assignment_Ques' => $questionHtml,
        'Assignment_file' => $assignmentFileName,
        'max_marks'       => $request->max_marks,
        'due_date'        => $request->due_date,
      ]);
    }
  }

  private function storeRecording(Request $request): void
  {
    $recordings = $this->extractRecordingItems($request->video_recording);

    if (empty($recordings)) {
      return;
    }

    foreach ($recordings as $recordingHtml) {
      Recording::create([
        'subject_id'      => $request->subject_id,
        'topic_id'        => $request->topic_id,
        'video_Recording' => $recordingHtml,
        'max_marks'       => $request->recording_max_marks,
        'due_date'        => $request->recording_due_date,
      ]);
    }
  }

  private function storeMockTest(Request $request): void
  {
    if ($request->mock_test_enabled !== 'yes') {
      return;
    }

    $mockText = trim(strip_tags((string) $request->mock_test));
    if ($mockText === '') {
      throw ValidationException::withMessages([
        'mock_test' => 'Please enter mock test content.',
      ]);
    }

    if ($request->mock_test_marks === null || $request->mock_test_marks === '') {
      throw ValidationException::withMessages([
        'mock_test_marks' => 'Please enter mock test marks.',
      ]);
    }

    MockTest::create([
      'subject_id' => $request->subject_id,
      'topic_id'   => $request->topic_id,
      'mock_test'  => $request->mock_test,
      'marks'      => $request->mock_test_marks,
    ]);
  }

  private function storeOnlineTest(Request $request): void
  {
    if ($request->online_test_enabled !== 'yes') {
      return;
    }

    $normalizedQuestions = $this->normalizeOnlineQuestions($request->input('online_questions', []));

    if (empty($normalizedQuestions)) {
      throw ValidationException::withMessages([
        'online_questions' => 'Please add at least one online test question or import CSV.',
      ]);
    }

    $onlineTest = OnlineTest::create([
      'subject_id'      => $request->subject_id,
      'topic_id'        => $request->topic_id,
      'total_questions' => count($normalizedQuestions),
    ]);

    foreach ($normalizedQuestions as $index => $question) {
      OnlineTestQuestion::create([
        'online_test_id' => $onlineTest->online_test_id,
        'question_no'    => $index + 1,
        'marks'          => $question['marks'],
        'question'       => $question['question'],
        'option_a'       => $question['option_a'],
        'option_b'       => $question['option_b'],
        'option_c'       => $question['option_c'],
        'option_d'       => $question['option_d'],
        'correct_option' => $question['correct_option'],
      ]);
    }
  }

  private function normalizeOnlineQuestions(array $questions): array
  {
    $normalized = [];

    foreach ($questions as $index => $row) {
      $question      = $row['question'] ?? '';
      $marks         = $row['marks'] ?? null;
      $optionA       = trim((string) ($row['option_a'] ?? ''));
      $optionB       = trim((string) ($row['option_b'] ?? ''));
      $optionC       = trim((string) ($row['option_c'] ?? ''));
      $optionD       = trim((string) ($row['option_d'] ?? ''));
      $correctOption = strtoupper(trim((string) ($row['correct_option'] ?? '')));

      $questionPlain = trim(strip_tags((string) $question));

      $hasAnyValue = $questionPlain !== ''
        || $marks !== null
        || $optionA !== ''
        || $optionB !== ''
        || $optionC !== ''
        || $optionD !== ''
        || $correctOption !== '';

      if (!$hasAnyValue) {
        continue;
      }

      if ($questionPlain === '') {
        throw ValidationException::withMessages([
          "online_questions.$index.question" => 'Question text is required.',
        ]);
      }

      if ($marks === null || $marks === '' || !is_numeric($marks)) {
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

      $normalized[] = [
        'marks'          => (int) $marks,
        'question'       => $question,
        'option_a'       => $optionA,
        'option_b'       => $optionB,
        'option_c'       => $optionC,
        'option_d'       => $optionD,
        'correct_option' => $correctOption,
      ];
    }

    return $normalized;
  }

  private function extractAssignmentQuestions(?string $html): array
  {
    if (!$html || trim(strip_tags($html)) === '') {
      return [];
    }

    $questions = [];

    libxml_use_internal_errors(true);

    $dom = new \DOMDocument('1.0', 'UTF-8');
    $wrappedHtml = '<?xml encoding="utf-8" ?><div id="assignment-wrapper">' . $html . '</div>';
    $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new \DOMXPath($dom);

    $listItems = $xpath->query('//div[@id="assignment-wrapper"]//li');
    if ($listItems && $listItems->length > 0) {
      foreach ($listItems as $li) {
        $itemHtml = trim($this->innerHtml($li));
        if ($itemHtml !== '' && trim(strip_tags($itemHtml)) !== '') {
          $questions[] = $itemHtml;
        }
      }
    }

    if (empty($questions)) {
      $paragraphs = $xpath->query('//div[@id="assignment-wrapper"]/p | //div[@id="assignment-wrapper"]/div');
      if ($paragraphs && $paragraphs->length > 0) {
        foreach ($paragraphs as $node) {
          $itemHtml = trim($this->innerHtml($node));
          if ($itemHtml !== '' && trim(strip_tags($itemHtml)) !== '') {
            $questions[] = $itemHtml;
          }
        }
      }
    }

    if (empty($questions)) {
      $plain = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html)));
      $lines = preg_split('/\r\n|\r|\n/', $plain);

      foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
          $questions[] = e($line);
        }
      }
    }

    return array_values(array_unique($questions));
  }

  private function extractRecordingItems(?string $html): array
  {
    if (!$html || trim(strip_tags($html)) === '') {
      return [];
    }

    $items = [];

    libxml_use_internal_errors(true);

    $dom = new \DOMDocument('1.0', 'UTF-8');
    $wrappedHtml = '<?xml encoding="utf-8" ?><div id="recording-wrapper">' . $html . '</div>';
    $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $xpath = new \DOMXPath($dom);

    $listItems = $xpath->query('//div[@id="recording-wrapper"]//li');
    if ($listItems && $listItems->length > 0) {
      foreach ($listItems as $li) {
        $itemHtml = trim($this->innerHtml($li));
        if ($itemHtml !== '' && trim(strip_tags($itemHtml)) !== '') {
          $items[] = $itemHtml;
        }
      }
    }

    if (empty($items)) {
      $paragraphs = $xpath->query('//div[@id="recording-wrapper"]/p | //div[@id="recording-wrapper"]/div');
      if ($paragraphs && $paragraphs->length > 0) {
        foreach ($paragraphs as $node) {
          $itemHtml = trim($this->innerHtml($node));
          if ($itemHtml !== '' && trim(strip_tags($itemHtml)) !== '') {
            $items[] = $itemHtml;
          }
        }
      }
    }

    if (empty($items)) {
      $plain = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html)));
      $lines = preg_split('/\r\n|\r|\n/', $plain);

      foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
          $items[] = e($line);
        }
      }
    }

    return array_values(array_unique($items));
  }

  private function innerHtml(\DOMNode $node): string
  {
    $html = '';
    foreach ($node->childNodes as $child) {
      $html .= $node->ownerDocument->saveHTML($child);
    }
    return $html;
  }

  private function uploadFile(Request $request, string $fieldName, string $folder): ?string
  {
    if (!$request->hasFile($fieldName)) {
      return null;
    }

    $file = $request->file($fieldName);
    $uploadPath = public_path($folder);

    if (!File::exists($uploadPath)) {
      File::makeDirectory($uploadPath, 0755, true, true);
    }

    $fileName = time() . '_' . Str::random(8) . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
    $file->move($uploadPath, $fileName);

    return $fileName;
  }

  private function actor(): string
  {
    return Auth::check() ? (Auth::user()->name ?? 'Admin') : 'Admin';
  }



  public function downloadOnlineTestCsvTemplate()
  {
    $fileName = 'online_test_template.csv';

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => "attachment; filename={$fileName}",
    ];

    $columns = ['question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'];

    $sampleRows = [
      [
        'What is Java?',
        'Programming Language',
        'Database',
        'Operating System',
        'Browser',
        'A'
      ],
      [
        'Which keyword is used to inherit a class in Java?',
        'implement',
        'extends',
        'inherits',
        'super',
        'B'
      ],
    ];

    $callback = function () use ($columns, $sampleRows) {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);

      foreach ($sampleRows as $row) {
        fputcsv($file, $row);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
