@extends('Admin.layouts.app')

@section('title', 'Edit Content Bundle')

@push('styles')
<style>
  :root {
    --primary: #1677ff;
    --primary-soft: #eef5ff;
    --primary-dark: #0d47a1;
    --success: #16a34a;
    --success-soft: #effdf4;
    --danger: #dc2626;
    --danger-soft: #fef2f2;
    --warning: #f59e0b;
    --text-dark: #111827;
    --text-muted: #6b7280;
    --border: #e5e7eb;
    --border-soft: #eef2f7;
    --bg-soft: #f8fafc;
    --white: #ffffff;
    --shadow-sm: 0 8px 24px rgba(15, 23, 42, 0.06);
    --shadow-md: 0 16px 38px rgba(15, 23, 42, 0.09);
  }

  .edit-card {
    border: 0;
    border-radius: 20px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    background: var(--white);
  }

  .edit-card .card-body {
    padding: 24px;
  }

  .edit-topbar {
    display: flex;
    justify-content: space-between;
    align-items: start;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 18px;
    padding: 18px 20px;
    border: 1px solid #e6eefc;
    border-radius: 18px;
    background: linear-gradient(135deg, #ffffff, #f3f8ff);
    box-shadow: var(--shadow-sm);
  }

  .edit-topbar h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 6px;
  }

  .edit-topbar p {
    margin: 0;
    color: var(--text-muted);
  }

  .top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .top-actions .btn {
    border-radius: 12px;
    font-weight: 700;
    padding: 10px 16px;
  }

  .hero-box {
    border: 1px solid #dbeafe;
    border-radius: 18px;
    background: linear-gradient(135deg, #eff6ff, #ffffff);
    padding: 18px 20px;
    margin-bottom: 22px;
  }

  .hero-box h4 {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 8px;
  }

  .hero-box p {
    color: var(--text-muted);
    margin-bottom: 12px;
  }

  .hero-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px solid #dbeafe;
    border-radius: 999px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
  }

  .wizard-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 12px;
    margin-bottom: 22px;
  }

  .wizard-tab {
    border: 1px solid var(--border);
    background: #fff;
    border-radius: 14px;
    padding: 12px 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: 0.25s ease;
    min-height: 64px;
  }

  .wizard-tab.active {
    border-color: var(--primary);
    background: var(--primary-soft);
    box-shadow: 0 8px 20px rgba(22, 119, 255, 0.10);
  }

  .wizard-tab.completed {
    border-color: var(--success);
    background: var(--success-soft);
  }

  .wizard-step-no {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #111827;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
  }

  .wizard-tab.active .wizard-step-no {
    background: var(--primary);
    color: #fff;
  }

  .wizard-tab.completed .wizard-step-no {
    background: var(--success);
    color: #fff;
  }

  .wizard-tab-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
  }

  .wizard-tab-text strong {
    font-size: 13px;
    color: #111827;
  }

  .wizard-tab-text span {
    font-size: 11px;
    color: #6b7280;
  }

  .wizard-pane {
    display: none;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px;
    background: #fff;
  }

  .wizard-pane.active {
    display: block;
  }

  .pane-title {
    font-size: 19px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 18px;
  }

  .form-label {
    font-size: 13px;
    font-weight: 700;
    color: #475467;
    margin-bottom: 8px;
  }

  .form-control,
  .form-select {
    min-height: 48px;
    border-radius: 12px;
    border: 1px solid #d0d5dd;
    box-shadow: none !important;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.10) !important;
  }

  textarea.form-control {
    min-height: 110px;
  }

  .help-text {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
  }

  .error-text {
    color: var(--danger);
    font-size: 13px;
    margin-top: 5px;
  }

  .info-file {
    margin-top: 10px;
    border: 1px dashed #bfdbfe;
    background: #f8fbff;
    padding: 10px 12px;
    border-radius: 12px;
    font-size: 13px;
  }

  .info-file a {
    color: var(--primary-dark);
    font-weight: 700;
    text-decoration: none;
  }

  .repeat-card {
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 16px;
    background: #fcfdff;
    margin-bottom: 14px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.03);
  }

  .repeat-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }

  .repeat-head h6 {
    margin: 0;
    font-size: 15px;
    font-weight: 800;
    color: #111827;
  }

  .btn-add-row {
    border-radius: 12px;
    font-weight: 700;
    padding: 10px 16px;
  }

  .btn-remove-row {
    border: 1px solid #fecaca;
    background: #fff1f2;
    color: #b91c1c;
    border-radius: 10px;
    font-weight: 700;
    padding: 8px 12px;
  }

  .test-box {
    border: 1px dashed #d0d5dd;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 18px;
    background: #fafcff;
  }

  .hidden-block {
    display: none;
  }

  .wizard-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
    gap: 12px;
    flex-wrap: wrap;
  }

  .wizard-footer .btn {
    min-width: 130px;
    border-radius: 12px;
    font-weight: 700;
  }

  .wizard-actions-right {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
  }

  .modal-header,
  .modal-footer {
    border-color: #eef2f7;
  }

  .add-topic-link {
    display: inline-block;
    margin-top: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #0d6efd;
    text-decoration: none;
  }

  .add-topic-link:hover {
    text-decoration: underline;
  }

  .ck-editor__editable_inline {
    min-height: 220px;
  }

  @media (max-width: 1399px) {
    .wizard-header {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  @media (max-width: 991px) {
    .wizard-header {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 767px) {
    .wizard-header {
      grid-template-columns: repeat(1, 1fr);
    }

    .wizard-footer {
      flex-direction: column;
      align-items: stretch;
    }

    .wizard-actions-right {
      width: 100%;
    }

    .wizard-actions-right .btn,
    .wizard-footer .btn {
      width: 100%;
    }

    .edit-card .card-body,
    .wizard-pane,
    .hero-box {
      padding: 16px;
    }
  }
</style>
@endpush

@section('content')
@php
$assignmentRows = old('assignments');
if (!is_array($assignmentRows)) {
$assignmentRows = $assignments->map(function ($row) {
return [
'assignment_id' => $row->assignment_id,
'question' => $row->Assignment_Ques,
'max_marks' => $row->max_marks,
'due_date' => $row->due_date,
'existing_file' => $row->Assignment_file,
];
})->toArray();
}
if (empty($assignmentRows)) {
$assignmentRows = [[
'assignment_id' => '',
'question' => '',
'max_marks' => '',
'due_date' => '',
'existing_file' => '',
]];
}

$recordingRows = old('recordings');
if (!is_array($recordingRows)) {
$recordingRows = $recordings->map(function ($row) {
return [
'rec_id' => $row->rec_id,
'video_recording' => $row->video_Recording,
'max_marks' => $row->max_marks,
'due_date' => $row->due_date,
];
})->toArray();
}
if (empty($recordingRows)) {
$recordingRows = [[
'rec_id' => '',
'video_recording' => '',
'max_marks' => '',
'due_date' => '',
]];
}

$onlineRows = old('online_questions');
if (!is_array($onlineRows)) {
$onlineRows = $onlineQuestions->map(function ($row) {
return [
'otq_id' => $row->otq_id,
'marks' => $row->marks,
'question' => $row->question,
'option_a' => $row->option_a,
'option_b' => $row->option_b,
'option_c' => $row->option_c,
'option_d' => $row->option_d,
'correct_option' => $row->correct_option,
];
})->toArray();
}

if (empty($onlineRows)) {
$onlineRows = [[
'otq_id' => '',
'marks' => '',
'question' => '',
'option_a' => '',
'option_b' => '',
'option_c' => '',
'option_d' => '',
'correct_option' => '',
]];
}

$mockEnabled = old('mock_test_enabled', !empty($mockTest) ? 'yes' : 'no');
$onlineEnabled = old('online_test_enabled', !empty($onlineTest) ? 'yes' : 'no');
@endphp

<main class="main-content">
  <div class="page-body">
    <div class="edit-topbar">
      <div>
        <h1>Edit Content Bundle</h1>
        <p>All 7 sections editable with simple wizard view and cleaner layout.</p>
      </div>

      <div class="top-actions">
        <a href="{{ route('admin.content-bundles.show', [$subject->subject_id, $topic->topic_id]) }}" class="btn btn-outline-primary">
          View
        </a>
        <a href="{{ route('admin.content-bundles.index') }}" class="btn btn-outline-secondary">
          Back
        </a>
      </div>
    </div>

    <div class="card edit-card">
      <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
          <strong>Please fix the highlighted errors.</strong>
        </div>
        @endif

        <div class="hero-box">
          <h4>{{ old('title', optional($content)->title ?: 'Untitled Content Bundle') }}</h4>
          <p>Edit complete subject-topic bundle including content, assignments, recordings, outcomes and tests.</p>

          <div class="hero-meta">
            <span>📘 Subject: {{ $subject->subject_name ?? '-' }}</span>
            <span>📚 Topic: {{ $topic->topic_name ?? '-' }}</span>
            <span>📝 Assignments: {{ count($assignmentRows) }}</span>
            <span>🎥 Recordings: {{ count($recordingRows) }}</span>
          </div>
        </div>

        <div class="wizard-header" id="wizardTabs">
          <button type="button" class="wizard-tab active" data-step="1">
            <span class="wizard-step-no">1</span>
            <span class="wizard-tab-text">
              <strong>Basic</strong>
              <span>Subject, topic, title</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="2">
            <span class="wizard-step-no">2</span>
            <span class="wizard-tab-text">
              <strong>Objective</strong>
              <span>Learning objective</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="3">
            <span class="wizard-step-no">3</span>
            <span class="wizard-tab-text">
              <strong>Content</strong>
              <span>Resources</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="4">
            <span class="wizard-step-no">4</span>
            <span class="wizard-tab-text">
              <strong>Assignments</strong>
              <span>Trainer task & files</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="5">
            <span class="wizard-step-no">5</span>
            <span class="wizard-tab-text">
              <strong>Recording</strong>
              <span>Recording task</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="6">
            <span class="wizard-step-no">6</span>
            <span class="wizard-tab-text">
              <strong>Outcomes</strong>
              <span>Learning outcomes</span>
            </span>
          </button>

          <button type="button" class="wizard-tab" data-step="7">
            <span class="wizard-step-no">7</span>
            <span class="wizard-tab-text">
              <strong>Test</strong>
              <span>Mock & online</span>
            </span>
          </button>
        </div>

        <form action="{{ route('admin.content-bundles.update', [$subject->subject_id, $topic->topic_id]) }}" method="POST" enctype="multipart/form-data" id="editBundleForm">
          @csrf
          @method('PUT')

          <div class="wizard-pane active" data-step="1">
            <div class="pane-title">Step 1: Basic Details</div>

            <div class="row g-3">
              <div class="col-lg-6">
                <label class="form-label">Subject Name</label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                  <option value="">Select Subject</option>
                  @foreach($subjects as $item)
                  <option value="{{ $item->subject_id }}" {{ old('subject_id', $subject->subject_id) == $item->subject_id ? 'selected' : '' }}>
                    {{ $item->subject_name }}
                  </option>
                  @endforeach
                </select>
                @error('subject_id')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-lg-6">
                <label class="form-label">Topic</label>
                <select name="topic_id" id="topic_id" class="form-select" data-old-topic="{{ old('topic_id', $topic->topic_id) }}" required>
                  <option value="">Select Topic</option>
                  @foreach($oldTopics as $item)
                  <option value="{{ $item->topic_id }}" {{ old('topic_id', $topic->topic_id) == $item->topic_id ? 'selected' : '' }}>
                    {{ $item->topic_name }}
                  </option>
                  @endforeach
                </select>

                <a href="javascript:void(0)" class="add-topic-link" id="openTopicModal">+ Add Topic</a>

                @error('topic_id')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', optional($content)->title) }}" placeholder="Enter title" required>
                @error('title')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="2">
            <div class="pane-title">Step 2: Learning Objective</div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Learning Objective</label>
                <textarea name="learning_objective" id="learning_objective" class="form-control ckeditor-field">{{ old('learning_objective', optional($content)->learning_objective) }}</textarea>
                @error('learning_objective')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="3">
            <div class="pane-title">Step 3: Content & Resources</div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Content</label>
                <textarea name="content_body" id="content_body" class="form-control ckeditor-field">{{ old('content_body', optional($content)->content_body) }}</textarea>
                @error('content_body')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Upload File</label>
                <input type="file" name="resource_file" class="form-control">
                @error('resource_file')
                <div class="error-text">{{ $message }}</div>
                @enderror

                @if(!empty(optional($content)->resource_file))
                <div class="info-file">
                  Current File:
                  <a href="{{ asset('uploads/content/resources/' . optional($content)->resource_file) }}" target="_blank">
                    {{ optional($content)->resource_file }}
                  </a>
                </div>
                @endif
              </div>

              <div class="col-md-6">
                <label class="form-label">Video Link</label>
                <input type="text" name="video_link" class="form-control" value="{{ old('video_link', optional($content)->video_link) }}" placeholder="Paste video link">
                @error('video_link')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="4">
            <div class="pane-title">Step 4: Assignments</div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Trainer Task</label>
                <textarea name="trainer_task" id="trainer_task" class="form-control ckeditor-field">{{ old('trainer_task', optional($content)->trainer_task) }}</textarea>
                @error('trainer_task')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label class="form-label">Assignment Questions</label>
                <textarea name="assignment_questions" id="assignment_questions" class="form-control ckeditor-field">{{ old('assignment_questions', $assignmentQuestions ?? '') }}</textarea>
                <small class="help-text">Numbered list ya bullet list me likho. Save hone par ye same upload style me update ho jayega.</small>
                @error('assignment_questions')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Assignment File</label>
                <input type="file" name="assignment_file" class="form-control">
                @error('assignment_file')
                <div class="error-text">{{ $message }}</div>
                @enderror

                @if(!empty($assignmentFile))
                <div class="info-file">
                  Current File:
                  <a href="{{ asset('uploads/assignments/' . $assignmentFile) }}" target="_blank">
                    {{ $assignmentFile }}
                  </a>
                </div>
                @endif
              </div>

              <div class="col-md-3">
                <label class="form-label">Max Marks</label>
                <input type="number" name="max_marks" class="form-control" min="0"
                  value="{{ old('max_marks', $assignmentMaxMarks) }}">
                @error('max_marks')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-3">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control"
                  value="{{ old('due_date', !empty($assignmentDueDate) ? \Carbon\Carbon::parse($assignmentDueDate)->format('Y-m-d') : '') }}">
                @error('due_date')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="5">
            <div class="pane-title">Step 5: Recording</div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Video Recording</label>
                <textarea name="video_recording" id="video_recording" class="form-control ckeditor-field">{{ old('video_recording', $videoRecording ?? '') }}</textarea>
                <small class="help-text">Numbered list ya bullet list me likho. Save hone par ye same upload style me update ho jayega.</small>
                @error('video_recording')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Recording Max Marks</label>
                <input type="number" name="recording_max_marks" class="form-control" min="0"
                  value="{{ old('recording_max_marks', $recordingMaxMarks) }}">
                @error('recording_max_marks')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label class="form-label">Recording Due Date</label>
                <input type="date" name="recording_due_date" class="form-control"
                  value="{{ old('recording_due_date', !empty($recordingDueDate) ? \Carbon\Carbon::parse($recordingDueDate)->format('Y-m-d') : '') }}">
                @error('recording_due_date')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="6">
            <div class="pane-title">Step 6: Learning Outcomes</div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Learning Outcomes</label>
                <textarea name="learning_outcomes" id="learning_outcomes" class="form-control ckeditor-field">{{ old('learning_outcomes', optional($content)->learning_outcomes) }}</textarea>
                @error('learning_outcomes')
                <div class="error-text">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="wizard-pane" data-step="7">
            <div class="pane-title">Step 7: Test</div>

            <div class="test-box">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Mock Test</label>
                  <select name="mock_test_enabled" id="mock_test_enabled" class="form-select">
                    <option value="no" {{ $mockEnabled == 'no' ? 'selected' : '' }}>No</option>
                    <option value="yes" {{ $mockEnabled == 'yes' ? 'selected' : '' }}>Yes</option>
                  </select>
                </div>
              </div>

              <div id="mockTestWrapper" class="hidden-block mt-3">
                <div class="row g-3">
                  <div class="col-md-3">
                    <label class="form-label">Mock Test Marks</label>
                    <input type="number" name="mock_test_marks" class="form-control" min="0"
                      value="{{ old('mock_test_marks', optional($mockTest)->marks) }}">
                    @error('mock_test_marks')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label">Mock Test Content</label>
                    <textarea name="mock_test" id="mock_test" class="form-control ckeditor-field">{{ old('mock_test', optional($mockTest)->mock_test) }}</textarea>
                    @error('mock_test')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="test-box">
              <div class="row g-3 align-items-end">
                <div class="col-md-4">
                  <label class="form-label">Online Test</label>
                  <select name="online_test_enabled" id="online_test_enabled" class="form-select">
                    <option value="no" {{ $onlineEnabled == 'no' ? 'selected' : '' }}>No</option>
                    <option value="yes" {{ $onlineEnabled == 'yes' ? 'selected' : '' }}>Yes</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label class="form-label">No. of Questions</label>
                  <input type="number" id="online_question_count" class="form-control" min="0" value="{{ count($onlineRows) }}">
                </div>

                <div class="col-md-4 d-flex gap-2">
                  <button type="button" class="btn btn-primary w-100" id="syncQuestionCountBtn">Sync Count</button>
                </div>
              </div>

              <div id="onlineTestWrapper" class="hidden-block mt-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                  <h6 class="mb-0 fw-bold">Online Questions</h6>
                  <button type="button" class="btn btn-success btn-add-row" id="addOnlineQuestionBtn">+ Add Question</button>
                </div>

                @error('online_questions')
                <div class="error-text mb-3">{{ $message }}</div>
                @enderror

                <div id="onlineQuestionsWrapper">
                  @foreach($onlineRows as $index => $row)
                  <div class="repeat-card online-question-card">
                    <div class="repeat-head">
                      <h6>Question {{ $loop->iteration }}</h6>
                      <button type="button" class="btn-remove-row remove-online-question">Remove</button>
                    </div>

                    <input type="hidden" name="online_questions[{{ $index }}][otq_id]" value="{{ $row['otq_id'] ?? '' }}">

                    <div class="row g-3">
                      <div class="col-md-2">
                        <label class="form-label">Marks</label>
                        <input type="number" name="online_questions[{{ $index }}][marks]" class="form-control" min="0" value="{{ $row['marks'] ?? '' }}">
                      </div>

                      <div class="col-12">
                        <label class="form-label">Question</label>
                        <textarea
                          id="online_question_{{ $index }}"
                          name="online_questions[{{ $index }}][question]"
                          class="form-control ckeditor-field online-question-editor"
                          rows="4">{{ $row['question'] ?? '' }}</textarea>
                      </div>

                      <div class="col-md-6">
                        <label class="form-label">Option A</label>
                        <input type="text" name="online_questions[{{ $index }}][option_a]" class="form-control" value="{{ $row['option_a'] ?? '' }}">
                      </div>

                      <div class="col-md-6">
                        <label class="form-label">Option B</label>
                        <input type="text" name="online_questions[{{ $index }}][option_b]" class="form-control" value="{{ $row['option_b'] ?? '' }}">
                      </div>

                      <div class="col-md-6">
                        <label class="form-label">Option C</label>
                        <input type="text" name="online_questions[{{ $index }}][option_c]" class="form-control" value="{{ $row['option_c'] ?? '' }}">
                      </div>

                      <div class="col-md-6">
                        <label class="form-label">Option D</label>
                        <input type="text" name="online_questions[{{ $index }}][option_d]" class="form-control" value="{{ $row['option_d'] ?? '' }}">
                      </div>

                      <div class="col-md-4">
                        <label class="form-label">Correct Option</label>
                        <select name="online_questions[{{ $index }}][correct_option]" class="form-select">
                          <option value="">Select Correct Option</option>
                          <option value="A" {{ ($row['correct_option'] ?? '') == 'A' ? 'selected' : '' }}>A</option>
                          <option value="B" {{ ($row['correct_option'] ?? '') == 'B' ? 'selected' : '' }}>B</option>
                          <option value="C" {{ ($row['correct_option'] ?? '') == 'C' ? 'selected' : '' }}>C</option>
                          <option value="D" {{ ($row['correct_option'] ?? '') == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

          <div class="wizard-footer">
            <button type="button" class="btn btn-outline-secondary" id="prevStepBtn">Previous</button>

            <div class="wizard-actions-right">
              <button type="button" class="btn btn-primary" id="nextStepBtn">Next</button>
              <button type="submit" class="btn btn-success d-none" id="submitBtn">
                Update Bundle
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="topicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="topicForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Topic</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Topic Name</label>
            <input type="text" id="topic_name" class="form-control" placeholder="Enter topic name">
            <div class="error-text" id="topic_error"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Topic</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
  $(function() {
    const topicModal = new bootstrap.Modal(document.getElementById('topicModal'));
    const addTopicUrl = @json(route('admin.upload-content.topic.store'));
    const topicsUrlTemplate = @json(route('upload-content.topics', ['subjectId' => '__SUBJECT_ID__']));
    const editors = {};
    let currentStep = 1;
    const totalSteps = 7;
    let isSubmitting = false;

    function buildTopicsUrl(subjectId) {
      return topicsUrlTemplate.replace('__SUBJECT_ID__', subjectId);
    }

    function initEditorByElement(element) {
      if (!element || !element.id || editors[element.id]) {
        return;
      }

      ClassicEditor.create(element, {
        toolbar: [
          'heading', '|',
          'bold', 'italic', 'link', '|',
          'bulletedList', 'numberedList', '|',
          'blockQuote', 'insertTable', '|',
          'undo', 'redo'
        ]
      }).then(editor => {
        editors[element.id] = editor;
      }).catch(error => {
        console.error(error);
      });
    }

    document.querySelectorAll('.ckeditor-field').forEach((element) => {
      initEditorByElement(element);
    });

    function renderTopics(topics, selectedId = '') {
      let html = '<option value="">Select Topic</option>';

      if (Array.isArray(topics) && topics.length > 0) {
        topics.forEach(function(topic) {
          const selected = String(topic.topic_id) === String(selectedId) ? 'selected' : '';
          html += `<option value="${topic.topic_id}" ${selected}>${topic.topic_name}</option>`;
        });
      }

      $('#topic_id').html(html);
    }

    function loadTopics(subjectId, selectedId = '') {
      if (!subjectId) {
        renderTopics([], '');
        return;
      }

      $('#topic_id').html('<option value="">Loading topics...</option>');

      $.ajax({
        url: buildTopicsUrl(subjectId),
        type: 'GET',
        success: function(response) {
          if (response.status) {
            renderTopics(response.topics, selectedId);
          } else {
            renderTopics([], '');
          }
        },
        error: function() {
          renderTopics([], '');
        }
      });
    }

    function showStep(step) {
      currentStep = step;

      $('.wizard-pane').removeClass('active');
      $('.wizard-pane[data-step="' + step + '"]').addClass('active');

      $('.wizard-tab').each(function() {
        const tabStep = parseInt($(this).attr('data-step'));
        $(this).removeClass('active completed');

        if (tabStep < step) {
          $(this).addClass('completed');
        } else if (tabStep === step) {
          $(this).addClass('active');
        }
      });

      $('#prevStepBtn').prop('disabled', step === 1);

      if (step === totalSteps) {
        $('#nextStepBtn').addClass('d-none');
        $('#submitBtn').removeClass('d-none');
      } else {
        $('#nextStepBtn').removeClass('d-none');
        $('#submitBtn').addClass('d-none');
      }
    }

    function validateStep1() {
      const subjectId = $('#subject_id').val();
      const topicId = $('#topic_id').val();
      const title = $('#title').val().trim();

      if (!subjectId) {
        alert('Please select subject.');
        return false;
      }

      if (!topicId) {
        alert('Please select topic.');
        return false;
      }

      if (!title) {
        alert('Please enter title.');
        return false;
      }

      return true;
    }

    function toggleMockBlock() {
      if ($('#mock_test_enabled').val() === 'yes') {
        $('#mockTestWrapper').slideDown(150);
      } else {
        $('#mockTestWrapper').slideUp(150);
      }
    }

    function toggleOnlineBlock() {
      if ($('#online_test_enabled').val() === 'yes') {
        $('#onlineTestWrapper').slideDown(150);
      } else {
        $('#onlineTestWrapper').slideUp(150);
      }
    }

    function refreshTitles(wrapperSelector, cardSelector, titlePrefix) {
      $(wrapperSelector).find(cardSelector).each(function(index) {
        $(this).find('.repeat-head h6').text(titlePrefix + ' ' + (index + 1));
      });
    }

    let assignmentIndex = $('#assignmentWrapper .assignment-card').length;
    let recordingIndex = $('#recordingWrapper .recording-card').length;
    let onlineQuestionIndex = $('#onlineQuestionsWrapper .online-question-card').length;

    function assignmentTemplate(index) {
      return `
        <div class="repeat-card assignment-card">
          <div class="repeat-head">
            <h6>Assignment ${index + 1}</h6>
            <button type="button" class="btn-remove-row remove-assignment">Remove</button>
          </div>

          <input type="hidden" name="assignments[${index}][assignment_id]" value="">
          <input type="hidden" name="assignments[${index}][existing_file]" value="">

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Question</label>
              <textarea name="assignments[${index}][question]" class="form-control" rows="4" placeholder="Enter assignment question"></textarea>
            </div>

            <div class="col-md-4">
              <label class="form-label">File</label>
              <input type="file" name="assignments[${index}][file]" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Max Marks</label>
              <input type="number" name="assignments[${index}][max_marks]" class="form-control" min="0">
            </div>

            <div class="col-md-4">
              <label class="form-label">Due Date</label>
              <input type="date" name="assignments[${index}][due_date]" class="form-control">
            </div>
          </div>
        </div>
      `;
    }

    function recordingTemplate(index) {
      return `
        <div class="repeat-card recording-card">
          <div class="repeat-head">
            <h6>Recording ${index + 1}</h6>
            <button type="button" class="btn-remove-row remove-recording">Remove</button>
          </div>

          <input type="hidden" name="recordings[${index}][rec_id]" value="">

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Video Recording</label>
              <textarea name="recordings[${index}][video_recording]" class="form-control" rows="4" placeholder="Enter video recording task"></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Max Marks</label>
              <input type="number" name="recordings[${index}][max_marks]" class="form-control" min="0">
            </div>

            <div class="col-md-6">
              <label class="form-label">Due Date</label>
              <input type="date" name="recordings[${index}][due_date]" class="form-control">
            </div>
          </div>
        </div>
      `;
    }

    function onlineQuestionTemplate(index) {
      return `
        <div class="repeat-card online-question-card">
          <div class="repeat-head">
            <h6>Question ${index + 1}</h6>
            <button type="button" class="btn-remove-row remove-online-question">Remove</button>
          </div>

          <input type="hidden" name="online_questions[${index}][otq_id]" value="">

          <div class="row g-3">
            <div class="col-md-2">
              <label class="form-label">Marks</label>
              <input type="number" name="online_questions[${index}][marks]" class="form-control" min="0">
            </div>

            <div class="col-12">
              <label class="form-label">Question</label>
              <textarea
                id="online_question_${index}"
                name="online_questions[${index}][question]"
                class="form-control ckeditor-field online-question-editor"
                rows="4"></textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">Option A</label>
              <input type="text" name="online_questions[${index}][option_a]" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Option B</label>
              <input type="text" name="online_questions[${index}][option_b]" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Option C</label>
              <input type="text" name="online_questions[${index}][option_c]" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Option D</label>
              <input type="text" name="online_questions[${index}][option_d]" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Correct Option</label>
              <select name="online_questions[${index}][correct_option]" class="form-select">
                <option value="">Select Correct Option</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
              </select>
            </div>
          </div>
        </div>
      `;
    }

    $('#addAssignmentBtn').on('click', function() {
      $('#assignmentWrapper').append(assignmentTemplate(assignmentIndex));
      assignmentIndex++;
      refreshTitles('#assignmentWrapper', '.assignment-card', 'Assignment');
    });

    $('#assignmentWrapper').on('click', '.remove-assignment', function() {
      $(this).closest('.assignment-card').remove();
      refreshTitles('#assignmentWrapper', '.assignment-card', 'Assignment');
    });

    $('#addRecordingBtn').on('click', function() {
      $('#recordingWrapper').append(recordingTemplate(recordingIndex));
      recordingIndex++;
      refreshTitles('#recordingWrapper', '.recording-card', 'Recording');
    });

    $('#recordingWrapper').on('click', '.remove-recording', function() {
      $(this).closest('.recording-card').remove();
      refreshTitles('#recordingWrapper', '.recording-card', 'Recording');
    });

    $('#addOnlineQuestionBtn').on('click', function() {
      const cardHtml = onlineQuestionTemplate(onlineQuestionIndex);
      $('#onlineQuestionsWrapper').append(cardHtml);

      const newTextarea = document.getElementById(`online_question_${onlineQuestionIndex}`);
      initEditorByElement(newTextarea);

      onlineQuestionIndex++;
      refreshTitles('#onlineQuestionsWrapper', '.online-question-card', 'Question');
      $('#online_question_count').val($('#onlineQuestionsWrapper .online-question-card').length);
    });

    $('#onlineQuestionsWrapper').on('click', '.remove-online-question', function() {
      const card = $(this).closest('.online-question-card');
      const textarea = card.find('.online-question-editor')[0];

      if (textarea && textarea.id && editors[textarea.id]) {
        editors[textarea.id].destroy().catch(function(error) {
          console.error(error);
        });
        delete editors[textarea.id];
      }

      card.remove();
      refreshTitles('#onlineQuestionsWrapper', '.online-question-card', 'Question');
      $('#online_question_count').val($('#onlineQuestionsWrapper .online-question-card').length);
    });

    $('#syncQuestionCountBtn').on('click', function() {
      $('#online_question_count').val($('#onlineQuestionsWrapper .online-question-card').length);
    });

    $('#nextStepBtn').on('click', function() {
      if (currentStep === 1 && !validateStep1()) {
        return;
      }

      if (currentStep < totalSteps) {
        showStep(currentStep + 1);
      }
    });

    $('#prevStepBtn').on('click', function() {
      if (currentStep > 1) {
        showStep(currentStep - 1);
      }
    });

    $('.wizard-tab').on('click', function() {
      const targetStep = parseInt($(this).attr('data-step'));

      if (targetStep > currentStep && currentStep === 1 && !validateStep1()) {
        return;
      }

      showStep(targetStep);
    });

    $('#subject_id').on('change', function() {
      loadTopics($(this).val(), '');
    });

    $('#openTopicModal').on('click', function() {
      const subjectId = $('#subject_id').val();

      if (!subjectId) {
        alert('Please select subject first.');
        return;
      }

      $('#topic_name').val('');
      $('#topic_error').text('');
      topicModal.show();
    });

    $('#topicForm').on('submit', function(e) {
      e.preventDefault();

      const subjectId = $('#subject_id').val();
      const topicName = $('#topic_name').val().trim();

      $('#topic_error').text('');

      if (!subjectId) {
        $('#topic_error').text('Please select subject first.');
        return;
      }

      if (!topicName) {
        $('#topic_error').text('Topic name is required.');
        return;
      }

      $.ajax({
        url: addTopicUrl,
        type: 'POST',
        data: {
          _token: $('input[name="_token"]').val(),
          subject_id: subjectId,
          topic_name: topicName
        },
        success: function(response) {
          if (response.status) {
            topicModal.hide();
            loadTopics(subjectId, response.topic.topic_id);
          } else {
            $('#topic_error').text(response.message || 'Unable to add topic.');
          }
        },
        error: function(xhr) {
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            if (xhr.responseJSON.errors.topic_name) {
              $('#topic_error').text(xhr.responseJSON.errors.topic_name[0]);
            } else if (xhr.responseJSON.errors.subject_id) {
              $('#topic_error').text(xhr.responseJSON.errors.subject_id[0]);
            } else {
              $('#topic_error').text('Validation error.');
            }
          } else {
            $('#topic_error').text('Unable to add topic.');
          }
        }
      });
    });

    $('#mock_test_enabled').on('change', toggleMockBlock);
    $('#online_test_enabled').on('change', toggleOnlineBlock);

    $('#editBundleForm').on('submit', function(e) {
      if (isSubmitting) {
        e.preventDefault();
        return false;
      }

      Object.keys(editors).forEach(function(key) {
        const textarea = document.getElementById(key);
        if (textarea) {
          textarea.value = editors[key].getData();
        }
      });

      isSubmitting = true;
      $('#submitBtn').prop('disabled', true).text('Updating...');
    });

    toggleMockBlock();
    toggleOnlineBlock();
    showStep(1);
  });
</script>
@endpush