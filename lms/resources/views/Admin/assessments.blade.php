@extends('Admin.layouts.app')

@section('title', 'Assessments')

@section('content')
@php
$quizzes = [
[
'title' => 'React Hooks — Module 4 Quiz',
'course' => 'React Masterclass',
'badge_class' => 'badge-green',
'status' => 'Open',
'enrolled' => 48,
'submitted' => 34,
'due' => '24 Mar',
'avg_score' => 4.2,
'pass_rate' => 72,
],
[
'title' => 'Data Wrangling Assessment',
'course' => 'Data Science Pro',
'badge_class' => 'badge-green',
'status' => 'Open',
'enrolled' => 156,
'submitted' => 142,
'due' => '22 Mar',
'avg_score' => 4.8,
'pass_rate' => 89,
],
[
'title' => 'Python OOP Challenge',
'course' => 'Python Beginners',
'badge_class' => 'badge-amber',
'status' => 'Review',
'enrolled' => 88,
'submitted' => 61,
'due' => '20 Mar',
'avg_score' => 3.9,
'pass_rate' => 65,
],
[
'title' => 'Cloud Architecture Quiz 3',
'course' => 'AWS Cloud Essentials',
'badge_class' => 'badge-muted',
'status' => 'Closed',
'enrolled' => 72,
'submitted' => 72,
'due' => '18 Mar',
'avg_score' => 4.5,
'pass_rate' => 78,
],
[
'title' => 'UX Heuristics Final Test',
'course' => 'UX Design Basics',
'badge_class' => 'badge-green',
'status' => 'Open',
'enrolled' => 36,
'submitted' => 18,
'due' => '25 Mar',
'avg_score' => 4.1,
'pass_rate' => 61,
],
[
'title' => 'Marketing Funnel Assignment',
'course' => 'Digital Marketing',
'badge_class' => 'badge-rose',
'status' => 'Draft',
'enrolled' => 0,
'submitted' => 0,
'due' => '28 Mar',
'avg_score' => 0,
'pass_rate' => 0,
],
[
'title' => 'Business Strategy Case Study',
'course' => 'Business Strategy',
'badge_class' => 'badge-green',
'status' => 'Open',
'enrolled' => 45,
'submitted' => 30,
'due' => '23 Mar',
'avg_score' => 4.4,
'pass_rate' => 74,
],
[
'title' => 'AWS Lambda Functions Test',
'course' => 'AWS Cloud Essentials',
'badge_class' => 'badge-amber',
'status' => 'Review',
'enrolled' => 60,
'submitted' => 40,
'due' => '21 Mar',
'avg_score' => 3.8,
'pass_rate' => 58,
],
];

$submissions = [
['id' => 11, 'student' => 'Priya Sharma', 'quiz' => 'React Hooks Quiz', 'percentage' => '88%', 'score_class' => 'score-pass', 'score' => '44/50', 'time' => '28 min', 'submitted_at' => '23 Mar'],
['id' => 22, 'student' => 'Ankit Verma', 'quiz' => 'Data Wrangling', 'percentage' => '94%', 'score_class' => 'score-pass', 'score' => '47/50', 'time' => '35 min', 'submitted_at' => '23 Mar'],
['id' => 33, 'student' => 'Sita Nair', 'quiz' => 'Python OOP', 'percentage' => '62%', 'score_class' => 'score-avg', 'score' => '31/50', 'time' => '42 min', 'submitted_at' => '22 Mar'],
['id' => 44, 'student' => 'Deepak Kumar', 'quiz' => 'Cloud Architecture', 'percentage' => '41%', 'score_class' => 'score-fail', 'score' => '20/50', 'time' => '55 min', 'submitted_at' => '22 Mar'],
['id' => 55, 'student' => 'Meena Patel', 'quiz' => 'UX Heuristics', 'percentage' => '79%', 'score_class' => 'score-pass', 'score' => '39/50', 'time' => '31 min', 'submitted_at' => '21 Mar'],
['id' => 15, 'student' => 'Rahul Tiwari', 'quiz' => 'React Hooks Quiz', 'percentage' => '71%', 'score_class' => 'score-pass', 'score' => '36/50', 'time' => '25 min', 'submitted_at' => '21 Mar'],
['id' => 16, 'student' => 'Pooja Singh', 'quiz' => 'Data Wrangling', 'percentage' => '85%', 'score_class' => 'score-pass', 'score' => '43/50', 'time' => '38 min', 'submitted_at' => '20 Mar'],
['id' => 68, 'student' => 'Karan Das', 'quiz' => 'Python OOP', 'percentage' => '55%', 'score_class' => 'score-avg', 'score' => '28/50', 'time' => '50 min', 'submitted_at' => '20 Mar'],
['id' => 25, 'student' => 'Anjali Mehta', 'quiz' => 'UX Heuristics', 'percentage' => '68%', 'score_class' => 'score-pass', 'score' => '34/50', 'time' => '28 min', 'submitted_at' => '19 Mar'],
['id' => 31, 'student' => 'Vivek Sharma', 'quiz' => 'Cloud Architecture', 'percentage' => '76%', 'score_class' => 'score-pass', 'score' => '38/50', 'time' => '40 min', 'submitted_at' => '19 Mar'],
['id' => 42, 'student' => 'Swati Gupta', 'quiz' => 'React Hooks Quiz', 'percentage' => '92%', 'score_class' => 'score-pass', 'score' => '46/50', 'time' => '22 min', 'submitted_at' => '18 Mar'],
['id' => 53, 'student' => 'Rohan Patil', 'quiz' => 'Python OOP', 'percentage' => '38%', 'score_class' => 'score-fail', 'score' => '19/50', 'time' => '60 min', 'submitted_at' => '18 Mar'],
];

$summaryStats = [
['ic-accent', 'bi-journal-check', '124', 'Total Quizzes'],
['ic-green', 'bi-check2-circle', '8,912', 'Submissions'],
['ic-amber', 'bi-hourglass-split', '23', 'Pending Review'],
['ic-sky', 'bi-graph-up', '73%', 'Avg Pass Rate'],
];

$statusFilters = ['All', 'Open', 'Review', 'Closed', 'Draft'];

$scoreDistribution = [
['0–20%', 8],
['21–40%', 14],
['41–60%', 22],
['61–80%', 38],
['81–100%', 52],
];

$quizOptions = [
'Randomize Questions',
'Show Correct Answers After',
'Certificate on Pass',
'Email Results',
'Allow Review',
];

$checkedQuizOptions = [
'Show Correct Answers After',
'Email Results',
];

$templates = [
'10-Question MCQ (30 min)',
'20-Question Mixed (60 min)',
'5-Question Quick Check',
'Assignment Template',
'Final Exam Template',
'Peer Review Template',
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Assessments</span>
        </div>
        <h1>Assessments &amp; Quizzes</h1>
        <p>Manage quizzes, assignments and evaluate student performance.</p>
      </div>
      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm" data-bs-toggle="modal" data-bs-target="#templatesModal">
          <i class="bi bi-file-earmark-text me-1"></i>Templates
        </button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addQuizModal">
          <i class="bi bi-plus-lg me-1"></i>New Quiz
        </button>
      </div>
    </div>

    <div class="row g-3 mb-4">
      @foreach($summaryStats as $i => $stat)
      <div class="col-6 col-xl-3 d{{ $i + 1 }}">
        <div class="card d-flex flex-row align-items-center gap-3 p-3">
          <div class="icon-circle ic-48 {{ $stat[0] }}">
            <i class="bi {{ $stat[1] }}"></i>
          </div>
          <div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.5rem;color:var(--text)">
              {{ $stat[2] }}
            </div>
            <div class="text-xs text-muted">{{ $stat[3] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 col-xl-8 d1">
        <div class="card">
          <div class="card-header mb-3">
            <div class="card-title">Active Quizzes</div>
            <div class="d-flex gap-2">
              @foreach($statusFilters as $status)
              <button class="btn btn-{{ $status === 'All' ? 'primary' : 'ghost' }} btn-xs"
                onclick="filterQuiz('{{ $status }}', this)">
                {{ $status }}
              </button>
              @endforeach
            </div>
          </div>

          <div class="card-body pt-0">
            @foreach($quizzes as $quiz)
            <div class="quiz-card {{ $quiz['status'] === 'Draft' ? 'opacity-75' : '' }}">
              <div class="quiz-header">
                <div>
                  <div class="quiz-title">{{ $quiz['title'] }}</div>
                  <div class="quiz-course">{{ $quiz['course'] }}</div>
                </div>
                <span class="badge {{ $quiz['badge_class'] }}">{{ $quiz['status'] }}</span>
              </div>

              <div class="quiz-stats">
                <div>
                  <div class="quiz-stat-v">{{ $quiz['enrolled'] }}</div>
                  <div class="quiz-stat-l text-xs text-muted">Enrolled</div>
                </div>
                <div>
                  <div class="quiz-stat-v">{{ $quiz['submitted'] }}</div>
                  <div class="quiz-stat-l text-xs text-muted">Submitted</div>
                </div>
                <div>
                  <div class="quiz-stat-v">{{ $quiz['pass_rate'] }}%</div>
                  <div class="quiz-stat-l text-xs text-muted">Pass Rate</div>
                </div>
                <div>
                  <div class="quiz-stat-v">{{ $quiz['avg_score'] }}/5</div>
                  <div class="quiz-stat-l text-xs text-muted">Avg Score</div>
                </div>
              </div>

              <div class="quiz-footer">
                <div class="quiz-due">
                  <i class="bi bi-calendar3 me-1"></i>Due: {{ $quiz['due'] }}
                </div>
                <div class="d-flex gap-2">
                  <button class="btn btn-ghost btn-xs" onclick="showToast('View quiz submissions','info')">
                    <i class="bi bi-eye-fill me-1"></i>View
                  </button>
                  <button class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#addQuizModal">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                  </button>
                  <button class="btn btn-danger btn-xs" onclick="showToast('Quiz deleted','error')">
                    <i class="bi bi-trash3-fill"></i>
                  </button>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-4 d2">
        <div class="card mb-3">
          <div class="card-header mb-3">
            <div class="card-title">Score Distribution</div>
            <div class="card-subtitle">All assessments this month</div>
          </div>
          <div class="card-body pt-0">
            @foreach($scoreDistribution as $bucket)
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="text-xs text-muted" style="width:55px;flex-shrink:0">{{ $bucket[0] }}</span>
              <div class="progress" style="flex:1;height:16px">
                <div class="progress-fill" style="width:{{ $bucket[1] }}%;display:flex;align-items:center;padding:0 6px">
                  @if($bucket[1] > 15)
                  <span style="font-size:.65rem;color:#fff;font-weight:700">{{ $bucket[1] }}%</span>
                  @endif
                </div>
              </div>
            </div>
            @endforeach

            <div class="mt-2 pt-2" style="border-top:1px solid var(--border)">
              <canvas id="scoreDonut" height="120"></canvas>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header mb-2">
            <div class="card-title">Quick Results</div>
          </div>
          <div class="card-body pt-0">
            @foreach(array_slice($submissions, 0, 6) as $submission)
            <div class="submission-item">
              <img src="https://i.pravatar.cc/50?img={{ $submission['id'] }}" class="avatar av-32" alt="">
              <div style="flex:1;min-width:0">
                <div class="sub-name">{{ $submission['student'] }}</div>
                <div class="sub-quiz">{{ $submission['quiz'] }}</div>
              </div>
              <div class="sub-score {{ $submission['score_class'] }}">{{ $submission['percentage'] }}</div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="card d1">
      <div class="card-header mb-3">
        <div>
          <div class="card-title">All Submissions</div>
        </div>
        <button class="btn btn-ghost btn-sm" onclick="showToast('Exporting submissions...','info')">
          <i class="bi bi-download me-1"></i>Export
        </button>
      </div>

      <div class="card-body pt-0">
        <div class="tbl-wrap">
          <table id="submissionsTable" class="tbl" style="width:100%">
            <thead>
              <tr>
                <th>Student</th>
                <th>Quiz</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Status</th>
                <th>Time</th>
                <th>Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($submissions as $submission)
              @php
              $pct = (int) str_replace('%', '', $submission['percentage']);
              $statusClass = $pct >= 70 ? 'badge-green' : ($pct >= 50 ? 'badge-amber' : 'badge-rose');
              $statusLabel = $pct >= 70 ? 'Pass' : ($pct >= 50 ? 'Average' : 'Fail');
              @endphp
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img src="https://i.pravatar.cc/40?img={{ $submission['id'] }}" class="avatar av-32" alt="">
                    <span class="fw-600 text-sm">{{ $submission['student'] }}</span>
                  </div>
                </td>
                <td class="text-sm">{{ $submission['quiz'] }}</td>
                <td class="fw-700 text-accent">{{ $submission['score'] }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress" style="width:55px">
                      <div class="progress-fill" style="width:{{ $submission['percentage'] }}"></div>
                    </div>
                    <span class="text-xs">{{ $submission['percentage'] }}</span>
                  </div>
                </td>
                <td>
                  <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="text-sm text-muted">{{ $submission['time'] }}</td>
                <td class="text-xs text-muted">{{ $submission['submitted_at'] }}</td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem" onclick="showToast('View submission','info')">
                      <i class="bi bi-eye-fill"></i>
                    </button>
                    <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem" onclick="showToast('Download report','info')">
                      <i class="bi bi-download"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<div class="modal fade" id="addQuizModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-journal-plus me-2" style="color:var(--accent)"></i>Add / Edit Quiz
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="form-group">
              <label class="form-label">Quiz Title <span class="req">*</span></label>
              <input type="text" class="form-control" placeholder="e.g. React Hooks — Module 4 Quiz">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Course <span class="req">*</span></label>
              <select class="form-control">
                <option>Select course…</option>
                <option>React Masterclass</option>
                <option>Data Science Pro</option>
                <option>Python Beginners</option>
                <option>AWS Cloud Essentials</option>
                <option>UX Design Basics</option>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Quiz Type</label>
              <select class="form-control">
                <option>Multiple Choice</option>
                <option>True/False</option>
                <option>Short Answer</option>
                <option>Assignment</option>
                <option>Mixed</option>
              </select>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Questions</label>
              <input type="number" class="form-control" value="10" min="1">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Time Limit (min)</label>
              <input type="number" class="form-control" value="30" min="0">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Pass Mark %</label>
              <input type="number" class="form-control" value="60" min="1" max="100">
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Max Attempts</label>
              <input type="number" class="form-control" value="3" min="1">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Due Date</label>
              <input type="date" class="form-control" value="2026-03-28">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Status</label>
              <select class="form-control">
                <option>Draft</option>
                <option>Open</option>
                <option>Closed</option>
              </select>
            </div>
          </div>

          <div class="col-12">
            <div class="form-group mb-0">
              <label class="form-label">Options</label>
              <div class="d-flex flex-wrap gap-3 mt-1">
                @foreach($quizOptions as $option)
                <label class="form-check">
                  <input type="checkbox" {{ in_array($option, $checkedQuizOptions) ? 'checked' : '' }}>
                  <span class="form-check-label ms-1">{{ $option }}</span>
                </label>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-ghost btn-sm"
          onclick="showToast('Saved as draft','info'); bootstrap.Modal.getInstance(document.getElementById('addQuizModal')).hide()">
          <i class="bi bi-floppy me-1"></i>Save Draft
        </button>
        <button class="btn btn-primary btn-sm"
          onclick="showToast('Quiz published!','success'); bootstrap.Modal.getInstance(document.getElementById('addQuizModal')).hide()">
          <i class="bi bi-send-fill me-1"></i>Publish
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="templatesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-file-earmark-text me-2" style="color:var(--accent)"></i>Quiz Templates
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @foreach($templates as $template)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
          <div>
            <div class="fw-600 text-sm">{{ $template }}</div>
            <div class="text-xs text-muted">Standard template</div>
          </div>
          <button class="btn btn-primary btn-xs"
            onclick="showToast('Using {{ addslashes($template) }} template','success'); bootstrap.Modal.getInstance(document.getElementById('templatesModal')).hide(); new bootstrap.Modal(document.getElementById('addQuizModal')).show()">
            Use
          </button>
        </div>
        @endforeach
      </div>

      <div class="modal-footer">
        <button class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function filterQuiz(status, btn) {
    document.querySelectorAll('button[onclick*="filterQuiz"]').forEach(function(b) {
      b.classList.remove('btn-primary');
      b.classList.add('btn-ghost');
    });

    btn.classList.add('btn-primary');
    btn.classList.remove('btn-ghost');

    showToast('Showing ' + status + ' quizzes', 'info');
  }

  document.addEventListener('DOMContentLoaded', function() {
    let a = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#00d4aa';

    let TT = {
      backgroundColor: 'var(--surface2)',
      borderColor: 'var(--border)',
      borderWidth: 1,
      titleColor: 'var(--text)',
      bodyColor: 'var(--muted)',
      padding: 10,
      cornerRadius: 8
    };

    new Chart(document.getElementById('scoreDonut'), {
      type: 'doughnut',
      data: {
        labels: ['Pass (70%+)', 'Average (50-69%)', 'Fail (<50%)'],
        datasets: [{
          data: [62, 15, 23],
          backgroundColor: [a, '#f59e0b', '#f43f5e'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '65%',
        plugins: {
          legend: {
            labels: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans',
                size: 11
              },
              boxWidth: 8,
              usePointStyle: true
            }
          },
          tooltip: TT
        }
      }
    });

    if (typeof initDataTable === 'function') {
      initDataTable('submissionsTable', {
        columnDefs: [{
          targets: [7],
          orderable: false
        }],
        order: [
          [6, 'desc']
        ]
      });
    }
  });
</script>
@endpush