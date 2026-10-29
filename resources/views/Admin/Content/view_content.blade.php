@extends('Admin.layouts.app')

@section('title', 'Bundle Details')

@push('styles')
<style>
  :root {
    --primary: #2563eb;
    --primary-soft: #eff6ff;
    --primary-dark: #1d4ed8;
    --success-soft: #ecfdf3;
    --success: #16a34a;
    --warning-soft: #fff7ed;
    --warning: #ea580c;
    --danger-soft: #fef2f2;
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --border: #e2e8f0;
    --bg-soft: #f8fafc;
    --white: #ffffff;
    --shadow-sm: 0 8px 24px rgba(15, 23, 42, 0.06);
    --shadow-md: 0 20px 45px rgba(15, 23, 42, 0.10);
    --radius-lg: 20px;
    --radius-md: 16px;
    --radius-sm: 12px;
  }

  .page-body {
    background: linear-gradient(180deg, #f8fbff 0%, #f8fafc 100%);
    min-height: 100vh;
    padding-bottom: 24px;
  }

  .page-topbar {
    background: linear-gradient(135deg, #ffffff, #f1f7ff);
    border: 1px solid #e6eefc;
    border-radius: 20px;
    padding: 18px 22px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 20px;
  }

  .page-topbar h1 {
    font-size: 30px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 6px;
    letter-spacing: -.4px;
  }

  .page-topbar p {
    margin: 0;
    color: var(--text-muted);
    font-size: 14px;
  }

  .btn-back {
    border-radius: 12px;
    padding: 10px 18px;
    font-weight: 600;
    border: 1px solid #dbeafe;
    background: #fff;
    color: var(--primary-dark);
    transition: .25s ease;
  }

  .btn-back:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
    transform: translateY(-1px);
  }

  .detail-shell {
    border: 0;
    border-radius: 24px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    background: var(--white);
    position: relative;
  }

  .detail-shell::before {
    content: "";
    position: absolute;
    inset: 0 auto auto 0;
    width: 100%;
    height: 6px;
    background: linear-gradient(90deg, #2563eb, #38bdf8, #22c55e);
  }

  .detail-shell .card-body {
    padding: 28px;
  }

  .hero-strip {
    position: relative;
    background:
      radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 30%),
      radial-gradient(circle at bottom left, rgba(34, 197, 94, 0.08), transparent 28%),
      linear-gradient(135deg, #eff6ff 0%, #ffffff 60%, #f8fafc 100%);
    border: 1px solid #dbeafe;
    border-radius: 22px;
    padding: 24px;
    margin-bottom: 24px;
    overflow: hidden;
  }

  .hero-strip::after {
    content: "";
    position: absolute;
    right: -40px;
    top: -40px;
    width: 150px;
    height: 150px;
    background: rgba(37, 99, 235, 0.06);
    border-radius: 50%;
  }

  .hero-strip h2 {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 8px;
    color: var(--text-dark);
    letter-spacing: -.4px;
    position: relative;
    z-index: 1;
  }

  .hero-subtext {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 16px;
    position: relative;
    z-index: 1;
  }

  .hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 1;
  }

  .hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid #dbeafe;
    color: #334155;
    border-radius: 999px;
    padding: 9px 14px;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.08);
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
  }

  .info-card {
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
    height: 100%;
  }

  .info-card .label {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 8px;
  }

  .info-card .value {
    color: var(--text-dark);
    font-size: 15px;
    font-weight: 700;
    word-break: break-word;
  }

  .accordion {
    --bs-accordion-border-width: 0;
    --bs-accordion-btn-focus-box-shadow: none;
  }

  .accordion-item {
    border: 1px solid var(--border) !important;
    border-radius: 18px !important;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
    background: #fff;
  }

  .accordion-header {
    margin-bottom: 0;
  }

  .accordion-button {
    font-weight: 800;
    color: var(--text-dark);
    background: linear-gradient(180deg, #ffffff, #fcfdff);
    box-shadow: none !important;
    padding: 18px 20px;
    font-size: 15px;
    border: 0;
  }

  .accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #eff6ff, #f8fbff);
    color: var(--primary-dark);
    border-bottom: 1px solid #dbeafe;
  }

  .accordion-button::after {
    background-size: 15px;
    width: 15px;
    height: 15px;
  }

  .accordion-body {
    background: #fff;
    color: #334155;
    line-height: 1.8;
    padding: 20px;
    font-size: 14px;
  }

  .content-box {
    background: linear-gradient(180deg, #ffffff, #fbfdff);
    border: 1px solid #e9eef5;
    border-radius: 16px;
    padding: 18px;
  }

  .content-box p:last-child,
  .content-box ul:last-child,
  .content-box ol:last-child {
    margin-bottom: 0;
  }

  .resource-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
  }

  .resource-card {
    flex: 1 1 280px;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
    background: linear-gradient(180deg, #ffffff, #f8fafc);
  }

  .resource-card .resource-label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 10px;
    letter-spacing: .5px;
  }

  .resource-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff !important;
    text-decoration: none;
    font-weight: 700;
    transition: .25s ease;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.20);
  }

  .resource-link:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
  }

  .muted-empty {
    display: inline-block;
    background: #f8fafc;
    color: #94a3b8;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 600;
  }

  .table-wrap {
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
  }

  .table {
    margin-bottom: 0;
  }

  .table thead th {
    white-space: nowrap;
    background: linear-gradient(180deg, #f8fbff, #eef4ff);
    color: #1e293b;
    font-size: 13px;
    font-weight: 800;
    border-bottom: 1px solid #dbeafe !important;
    padding: 14px 16px;
    vertical-align: middle;
  }

  .table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    color: #334155;
    font-size: 14px;
    border-color: #edf2f7 !important;
  }

  .table tbody tr:hover {
    background: #f8fbff;
  }

  .badge-soft {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    padding: 6px 10px;
    border-radius: 999px;
    background: var(--primary-soft);
    color: var(--primary-dark);
    font-weight: 800;
    font-size: 12px;
  }

  .empty-row {
    padding: 22px !important;
    text-align: center;
    color: #94a3b8 !important;
    font-weight: 600;
    background: #fcfdff;
  }

  .audit-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
  }

  .audit-card {
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
  }

  .audit-card .audit-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 8px;
  }

  .audit-card .audit-value {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    word-break: break-word;
  }

  @media (max-width: 991px) {
    .detail-shell .card-body {
      padding: 20px;
    }

    .hero-strip {
      padding: 20px;
    }

    .hero-strip h2 {
      font-size: 24px;
    }

    .info-grid,
    .audit-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 767px) {
    .page-topbar {
      padding: 16px;
    }

    .page-topbar h1 {
      font-size: 24px;
    }

    .detail-shell .card-body {
      padding: 16px;
    }

    .hero-strip {
      padding: 18px;
      border-radius: 18px;
    }

    .hero-strip h2 {
      font-size: 21px;
    }

    .hero-meta span {
      width: 100%;
      justify-content: center;
    }

    .accordion-button,
    .accordion-body {
      padding: 16px;
    }

    .info-grid,
    .audit-grid {
      grid-template-columns: 1fr;
    }

    .table thead th,
    .table tbody td {
      padding: 12px;
    }
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-topbar d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <h3 class="mb-1">Content details</h3>
        <p>Structured and visually refined combined view of upload content, assignments, and recordings.</p>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.content-bundles.edit', [$subject->subject_id, $topic->topic_id]) }}" class="btn btn-primary">
          ✏ Edit Content
        </a>

        <a href="{{ route('admin.content-bundles.index') }}" class="btn btn-back">
          ← Back
        </a>
      </div>
    </div>

    <div class="card detail-shell">
      <div class="card-body">

        <div class="hero-strip">
          <h4>{{ !empty(optional($content)->title) ? optional($content)->title : 'Untitled Bundle' }}</h4>
          <div class="hero-subtext">Subject and topic based combined learning bundle with all linked assets in one place.</div>

          <div class="hero- meta">
            <span>📘 Subject: {{ $subject->subject_name ?? '-' }}</span>
            <span>📚 Topic: {{ $topic->topic_name ?? '-' }}</span>
            <span>📝 Assignments: {{ $assignments->count() }}</span>
            <span>🎥 Recordings: {{ $recordings->count() }}</span>
          </div>
        </div>

        <div class="accordion" id="bundleAccordion">

          <div class="accordion-item">
            <h2 class="accordion-header" id="head1">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                Basic Details
              </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="info-grid">
                  <div class="info-card">
                    <span class="label">Subject</span>
                    <div class="value">{{ $subject->subject_name ?? '-' }}</div>
                  </div>
                  <div class="info-card">
                    <span class="label">Topic</span>
                    <div class="value">{{ $topic->topic_name ?? '-' }}</div>
                  </div>
                  <div class="info-card">
                    <span class="label">Title</span>
                    <div class="value">{{ !empty(optional($content)->title) ? optional($content)->title : '-' }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head2">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                Learning Objective
              </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="content-box">
                  {!! !empty(optional($content)->learning_objective) ? optional($content)->learning_objective : '<span class="muted-empty">No learning objective available</span>' !!}
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head3">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                Content Body
              </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="content-box">
                  {!! !empty(optional($content)->content_body) ? optional($content)->content_body : '<span class="muted-empty">No content body available</span>' !!}
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head4">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                Resources
              </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="resource-wrap">
                  <div class="resource-card">
                    <span class="resource-label">Resource File</span>
                    @if(!empty(optional($content)->resource_file))
                    <a href="{{ asset('uploads/content/resources/' . optional($content)->resource_file) }}" target="_blank" class="resource-link">
                      View Resource File
                    </a>
                    @else
                    <span class="muted-empty">No resource file available</span>
                    @endif
                  </div>

                  <div class="resource-card">
                    <span class="resource-label">Video Link</span>
                    @if(!empty(optional($content)->video_link))
                    <a href="{{ optional($content)->video_link }}" target="_blank" class="resource-link">
                      Open Video Link
                    </a>
                    @else
                    <span class="muted-empty">No video link available</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head5">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                Trainer Task
              </button>
            </h2>
            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="content-box">
                  {!! !empty(optional($content)->trainer_task) ? optional($content)->trainer_task : '<span class="muted-empty">No trainer task available</span>' !!}
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head6">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                Learning Outcomes
              </button>
            </h2>
            <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="content-box">
                  {!! !empty(optional($content)->learning_outcomes) ? optional($content)->learning_outcomes : '<span class="muted-empty">No learning outcomes available</span>' !!}
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head7">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                Assignments
              </button>
            </h2>
            <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="table-wrap">
                  <div class="table-responsive">
                    <table class="table align-middle">
                      <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <th>Question</th>
                          <th>File</th>
                          <th>Max Marks</th>
                          <th>Due Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($assignments as $key => $row)
                        <tr>
                          <td><span class="badge-soft">{{ $key + 1 }}</span></td>
                          <td>{!! !empty($row->Assignment_Ques) ? $row->Assignment_Ques : '-' !!}</td>
                          <td>
                            @if(!empty($row->Assignment_file))
                            <a href="{{ asset('uploads/assignments/' . $row->Assignment_file) }}" target="_blank" class="resource-link" style="min-height:auto;padding:8px 14px;font-size:13px;">
                              View File
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>{{ $row->max_marks ?? '-' }}</td>
                          <td>{{ !empty($row->due_date) ? \Carbon\Carbon::parse($row->due_date)->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="5" class="empty-row">No assignments found.</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head8">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8">
                Recordings
              </button>
            </h2>
            <div id="collapse8" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="table-wrap">
                  <div class="table-responsive">
                    <table class="table align-middle">
                      <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <th>Video Recording</th>
                          <th>Max Marks</th>
                          <th>Due Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($recordings as $key => $row)
                        <tr>
                          <td><span class="badge-soft">{{ $key + 1 }}</span></td>
                          <td>{!! !empty($row->video_Recording) ? $row->video_Recording : '-' !!}</td>
                          <td>{{ $row->max_marks ?? '-' }}</td>
                          <td>{{ !empty($row->due_date) ? \Carbon\Carbon::parse($row->due_date)->format('d M Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="4" class="empty-row">No recordings found.</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="head9">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9">
                Audit Information
              </button>
            </h2>
            <div id="collapse9" class="accordion-collapse collapse" data-bs-parent="#bundleAccordion">
              <div class="accordion-body">
                <div class="audit-grid">
                  <div class="audit-card">
                    <div class="audit-label">Created By</div>
                    <div class="audit-value">{{ optional($content)->created_by ?? '-' }}</div>
                  </div>

                  <div class="audit-card">
                    <div class="audit-label">Updated By</div>
                    <div class="audit-value">{{ optional($content)->updated_by ?? '-' }}</div>
                  </div>

                  <div class="audit-card">
                    <div class="audit-label">Created At</div>
                    <div class="audit-value">
                      {{ !empty(optional($content)->created_at) ? \Carbon\Carbon::parse(optional($content)->created_at)->format('d M Y h:i A') : '-' }}
                    </div>
                  </div>

                  <div class="audit-card">
                    <div class="audit-label">Updated At</div>
                    <div class="audit-value">
                      {{ !empty(optional($content)->updated_at) ? \Carbon\Carbon::parse(optional($content)->updated_at)->format('d M Y h:i A') : '-' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</main>
@endsection