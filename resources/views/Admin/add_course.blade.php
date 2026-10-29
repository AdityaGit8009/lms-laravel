@extends('Admin.layouts.app')

@section('title', 'Add Course')

@section('content')
@php
$sectionOneLessons = [
['title' => 'Introduction & Setup', 'time' => '12:30', 'badge' => 'Free Preview', 'badge_class' => 'green'],
['title' => 'Installing Node.js and npm', 'time' => '10:45', 'badge' => 'Paid', 'badge_class' => 'muted'],
['title' => 'Project Overview', 'time' => '14:20', 'badge' => 'Paid', 'badge_class' => 'muted'],
];

$sectionTwoLessons = [
['title' => 'JSX Deep Dive', 'time' => '18:25'],
['title' => 'Components & Props', 'time' => '16:40'],
['title' => 'State & useState Hook', 'time' => '20:15'],
['title' => 'Event Handling', 'time' => '11:55'],
];

$pricingTypes = [
'Free' => 'Free course, open to all',
'One-Time' => 'Students pay once',
'Subscription' => 'Part of a subscription plan',
];

$resourceFiles = ['starter-code.zip', 'lecture-slides.pdf', 'cheatsheet.pdf'];

$courseSettings = [
'Enable Q&A / Discussions',
'Certificate on Completion',
'Allow Downloads',
'Show Student Count',
'Featured Course'
];

$checkedSettings = ['Certificate on Completion', 'Show Student Count'];

$reviewChecks = [
'Title & Description',
'Curriculum (2 sections, 7 lessons)',
'Thumbnail uploaded',
'Pricing set (₹3,200)',
'Tags added (3)',
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <a href="{{ route('courses') }}">Courses</a>
          <span>/</span>
          <span>Add Course</span>
        </div>
        <h1>Add New Course</h1>
        <p>Fill in the details below to publish a new course on EduCore.</p>
      </div>
      <div class="page-hdr-right">
        <a href="{{ route('courses') }}" class="btn btn-ghost btn-sm">
          <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <button class="btn btn-ghost btn-sm" onclick="saveDraft()">
          <i class="bi bi-floppy me-1"></i>Save Draft
        </button>
        <button class="btn btn-primary btn-sm" onclick="publishCourse()">
          <i class="bi bi-send-fill me-1"></i>Publish Course
        </button>
      </div>
    </div>

    <div class="form-steps mb-4">
      <div class="form-step active" id="step-ind-1">
        <div class="step-num">1</div>
        <div class="step-lbl">Basic Info</div>
      </div>
      <div class="step-line" id="line-1"></div>
      <div class="form-step" id="step-ind-2">
        <div class="step-num">2</div>
        <div class="step-lbl">Curriculum</div>
      </div>
      <div class="step-line" id="line-2"></div>
      <div class="form-step" id="step-ind-3">
        <div class="step-num">3</div>
        <div class="step-lbl">Media & Pricing</div>
      </div>
      <div class="step-line" id="line-3"></div>
      <div class="form-step" id="step-ind-4">
        <div class="step-num">4</div>
        <div class="step-lbl">Settings</div>
      </div>
    </div>

    <div id="step1">
      <div class="row g-3">
        <div class="col-12 col-lg-8">
          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-info-circle-fill" style="color:var(--accent)"></i>Basic Information
            </div>
            <div class="form-group">
              <label class="form-label">Course Title <span class="req">*</span></label>
              <input type="text" class="form-control" id="courseTitle" placeholder="e.g. Complete React Masterclass 2026">
              <div class="form-hint">Write a clear, descriptive title. Aim for 60–80 characters.</div>
            </div>
            <div class="form-group">
              <label class="form-label">Short Description <span class="req">*</span></label>
              <textarea class="form-control" id="courseDesc" placeholder="Brief overview shown in course listing (2–3 sentences)..." style="min-height:80px;"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Full Description</label>
              <textarea class="form-control" placeholder="Detailed course description, what students will learn, prerequisites, etc." style="min-height:140px;"></textarea>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="form-label">Category <span class="req">*</span></label>
                  <select class="form-control">
                    <option value="">Select category…</option>
                    <option>Technology</option>
                    <option>Design</option>
                    <option>Business</option>
                    <option>Marketing</option>
                    <option>Data Science</option>
                    <option>Arts</option>
                    <option>Music</option>
                    <option>Finance</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="form-label">Sub-Category</label>
                  <input type="text" class="form-control" placeholder="e.g. Frontend Development">
                </div>
              </div>
            </div>
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-people-fill" style="color:var(--accent)"></i>Instructor & Details
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Instructor <span class="req">*</span></label>
                  <select class="form-control">
                    <option value="">Select instructor…</option>
                    <option>Ravi Kumar</option>
                    <option>Neha Joshi</option>
                    <option>Amit Patel</option>
                    <option>Karan Gupta</option>
                    <option>Deepa Menon</option>
                    <option>Anita Mehta</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Language</label>
                  <select class="form-control">
                    <option>Hindi</option>
                    <option>English</option>
                    <option>Hinglish</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Difficulty Level</label>
                  <select class="form-control">
                    <option>Beginner</option>
                    <option>Intermediate</option>
                    <option>Advanced</option>
                    <option>All Levels</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Estimated Duration</label>
                  <div class="input-group">
                    <input type="number" class="form-control" value="20" min="1">
                    <span class="input-addon" style="border-left:none;border-radius:0 var(--inp-r) var(--inp-r) 0">hrs</span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Max Students</label>
                  <input type="number" class="form-control" placeholder="Unlimited" min="1">
                </div>
              </div>
            </div>
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-tags-fill" style="color:var(--accent)"></i>Tags
            </div>
            <div class="form-group mb-2">
              <label class="form-label">Course Tags</label>
              <div class="input-group">
                <input type="text" class="form-control" id="tagInput" placeholder="Type a tag and press Enter…">
                <button type="button" class="btn btn-ghost" onclick="addTag()">
                  <i class="bi bi-plus-lg"></i>
                </button>
              </div>
            </div>
            <div id="tagsContainer">
              <span class="tag-chip">React<button type="button" onclick="this.parentElement.remove()" title="Remove">×</button></span>
              <span class="tag-chip">JavaScript<button type="button" onclick="this.parentElement.remove()" title="Remove">×</button></span>
              <span class="tag-chip">Frontend<button type="button" onclick="this.parentElement.remove()" title="Remove">×</button></span>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="form-card mb-3">
            <div class="form-section-title">
              <i class="bi bi-image-fill" style="color:var(--accent)"></i>Course Thumbnail
            </div>
            <div class="upload-box" onclick="showToast('File picker opened','info')">
              <i class="bi bi-cloud-upload-fill"></i>
              <div class="ub-title">Click to upload thumbnail</div>
              <div class="ub-sub">JPG, PNG, WebP — 1280×720px recommended</div>
            </div>
          </div>

          <div class="form-card mb-3">
            <div class="form-section-title">
              <i class="bi bi-play-circle-fill" style="color:var(--accent)"></i>Preview Video
            </div>
            <div class="upload-box" onclick="showToast('Video picker opened','info')">
              <i class="bi bi-camera-video-fill"></i>
              <div class="ub-title">Upload promo video</div>
              <div class="ub-sub">MP4, max 200MB, 2–5 min recommended</div>
            </div>
            <div class="form-hint mt-2">Or paste a YouTube/Vimeo link:</div>
            <input type="url" class="form-control mt-1" placeholder="https://youtube.com/watch?v=…">
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-card-checklist" style="color:var(--accent)"></i>Requirements
            </div>
            <div class="form-group mb-2">
              <label class="form-label">Prerequisites</label>
              <textarea class="form-control" placeholder="List any prior knowledge required..." style="min-height:80px;"></textarea>
            </div>
            <div class="form-group mb-0">
              <label class="form-label">Learning Outcomes</label>
              <textarea class="form-control" placeholder="What students will be able to do after completing..." style="min-height:80px;"></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-3">
        <button class="btn btn-ghost btn-sm" onclick="saveDraft()">
          <i class="bi bi-floppy me-1"></i>Save Draft
        </button>
        <button class="btn btn-primary btn-sm" onclick="goStep(2)">
          Next: Curriculum <i class="bi bi-arrow-right ms-1"></i>
        </button>
      </div>
    </div>

    <div id="step2" style="display:none">
      <div class="form-card">
        <div class="form-section-title">
          <i class="bi bi-collection-fill" style="color:var(--accent)"></i>Curriculum Builder
        </div>
        <p class="text-sm text-muted mb-3">Organise your content into sections and lessons.</p>

        <div id="curriculum">
          <div class="curriculum-section mb-3" style="border:1px solid var(--border);border-radius:var(--card-r);overflow:hidden;">
            <div style="background:var(--surface2);padding:12px 16px;display:flex;align-items:center;gap:10px;cursor:pointer;">
              <i class="bi bi-grip-vertical text-muted"></i>
              <input type="text" class="form-control" value="Section 1: Getting Started" style="flex:1;background:transparent;border:none;box-shadow:none;font-weight:600;padding:0">
              <span class="text-xs text-muted">3 lessons</span>
              <button type="button" class="icon-btn" style="width:28px;height:28px;font-size:.78rem"><i class="bi bi-plus-lg"></i></button>
              <button type="button" class="icon-btn" style="width:28px;height:28px;font-size:.78rem;color:#f43f5e"><i class="bi bi-trash3-fill"></i></button>
            </div>
            <div style="padding:10px 16px;">
              @foreach($sectionOneLessons as $lesson)
              <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                <i class="bi bi-grip-vertical text-muted" style="font-size:.8rem"></i>
                <i class="bi bi-play-circle text-muted"></i>
                <span class="text-sm" style="flex:1">{{ $lesson['title'] }}</span>
                <span class="text-xs text-muted">{{ $lesson['time'] }}</span>
                <span class="badge badge-{{ $lesson['badge_class'] }}">{{ $lesson['badge'] }}</span>
                <button type="button" class="icon-btn" style="width:24px;height:24px;font-size:.72rem"><i class="bi bi-pencil-fill"></i></button>
                <button type="button" class="icon-btn" style="width:24px;height:24px;font-size:.72rem;color:#f43f5e"><i class="bi bi-trash3-fill"></i></button>
              </div>
              @endforeach
              <button class="btn btn-ghost btn-xs mt-2" style="width:100%;justify-content:center;" onclick="showToast('Lesson added','success')">
                <i class="bi bi-plus-lg me-1"></i>Add Lesson
              </button>
            </div>
          </div>

          <div class="curriculum-section mb-3" style="border:1px solid var(--border);border-radius:var(--card-r);overflow:hidden;">
            <div style="background:var(--surface2);padding:12px 16px;display:flex;align-items:center;gap:10px;cursor:pointer;">
              <i class="bi bi-grip-vertical text-muted"></i>
              <input type="text" class="form-control" value="Section 2: Core Concepts" style="flex:1;background:transparent;border:none;box-shadow:none;font-weight:600;padding:0">
              <span class="text-xs text-muted">4 lessons</span>
              <button type="button" class="icon-btn" style="width:28px;height:28px;font-size:.78rem"><i class="bi bi-plus-lg"></i></button>
              <button type="button" class="icon-btn" style="width:28px;height:28px;font-size:.78rem;color:#f43f5e"><i class="bi bi-trash3-fill"></i></button>
            </div>
            <div style="padding:10px 16px;">
              @foreach($sectionTwoLessons as $lesson)
              <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                <i class="bi bi-grip-vertical text-muted" style="font-size:.8rem"></i>
                <i class="bi bi-play-circle text-muted"></i>
                <span class="text-sm" style="flex:1">{{ $lesson['title'] }}</span>
                <span class="text-xs text-muted">{{ $lesson['time'] }}</span>
                <span class="badge badge-muted">Paid</span>
                <button type="button" class="icon-btn" style="width:24px;height:24px;font-size:.72rem"><i class="bi bi-pencil-fill"></i></button>
                <button type="button" class="icon-btn" style="width:24px;height:24px;font-size:.72rem;color:#f43f5e"><i class="bi bi-trash3-fill"></i></button>
              </div>
              @endforeach
              <button class="btn btn-ghost btn-xs mt-2" style="width:100%;justify-content:center;" onclick="showToast('Lesson added','success')">
                <i class="bi bi-plus-lg me-1"></i>Add Lesson
              </button>
            </div>
          </div>
        </div>

        <button class="btn btn-ghost btn-sm" onclick="showToast('New section added','success')">
          <i class="bi bi-plus-lg me-1"></i>Add Section
        </button>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-ghost btn-sm" onclick="goStep(1)">
          <i class="bi bi-arrow-left me-1"></i>Back
        </button>
        <button class="btn btn-primary btn-sm" onclick="goStep(3)">
          Next: Media & Pricing <i class="bi bi-arrow-right ms-1"></i>
        </button>
      </div>
    </div>

    <div id="step3" style="display:none">
      <div class="row g-3">
        <div class="col-12 col-lg-8">
          <div class="form-card mb-3">
            <div class="form-section-title">
              <i class="bi bi-currency-rupee" style="color:var(--accent)"></i>Pricing
            </div>
            <div class="row g-3 mb-3">
              @foreach($pricingTypes as $type => $desc)
              <div class="col-md-4">
                <div class="pricing-opt"
                  onclick="document.querySelectorAll('.pricing-opt').forEach(e=>e.classList.remove('selected'));this.classList.add('selected')"
                  style="border:2px solid {{ $type === 'One-Time' ? 'var(--accent)' : 'var(--border)' }}; border-radius:var(--card-r);padding:13px;cursor:pointer; transition:all .2s; background:{{ $type === 'One-Time' ? 'var(--accent-dim)' : 'transparent' }}">
                  <div style="font-weight:700;font-size:.88rem;color:var(--text);margin-bottom:3px;">{{ $type }}</div>
                  <div style="font-size:.72rem;color:var(--muted);">{{ $desc }}</div>
                </div>
              </div>
              @endforeach
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Original Price (₹)</label>
                  <div class="input-group">
                    <span class="input-addon">₹</span>
                    <input type="number" class="form-control" value="3999" min="0">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Sale Price (₹)</label>
                  <div class="input-group">
                    <span class="input-addon">₹</span>
                    <input type="number" class="form-control" value="3200" min="0">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label class="form-label">Discount %</label>
                  <input type="text" class="form-control" value="20%" disabled style="background:var(--surface2)">
                </div>
              </div>
            </div>
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-file-earmark-arrow-up-fill" style="color:var(--accent)"></i>Course Resources
            </div>
            <div class="upload-box" onclick="showToast('Resource picker opened','info')">
              <i class="bi bi-file-earmark-arrow-up-fill"></i>
              <div class="ub-title">Upload Resources (PDFs, ZIPs, Slides)</div>
              <div class="ub-sub">Max 50MB per file · Multiple files allowed</div>
            </div>
            <div class="mt-3">
              @foreach($resourceFiles as $file)
              <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                <i class="bi bi-file-earmark-fill text-muted"></i>
                <span class="text-sm" style="flex:1">{{ $file }}</span>
                <span class="text-xs text-muted">5MB</span>
                <button type="button" class="icon-btn" style="width:26px;height:26px;font-size:.75rem;color:#f43f5e">
                  <i class="bi bi-trash3-fill"></i>
                </button>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="form-card mb-3">
            <div class="form-section-title">
              <i class="bi bi-bar-chart-fill" style="color:var(--accent)"></i>SEO
            </div>
            <div class="form-group">
              <label class="form-label">Meta Title</label>
              <input type="text" class="form-control" placeholder="SEO title for search engines...">
            </div>
            <div class="form-group mb-0">
              <label class="form-label">Meta Description</label>
              <textarea class="form-control" placeholder="Brief description for search engines..." style="min-height:80px;"></textarea>
            </div>
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-gear-fill" style="color:var(--accent)"></i>Course Settings
            </div>
            <div class="d-flex flex-column gap-2">
              @foreach($courseSettings as $option)
              <label class="form-check">
                <input type="checkbox" {{ in_array($option, $checkedSettings) ? 'checked' : '' }}>
                <span class="form-check-label">{{ $option }}</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-ghost btn-sm" onclick="goStep(2)">
          <i class="bi bi-arrow-left me-1"></i>Back
        </button>
        <button class="btn btn-primary btn-sm" onclick="goStep(4)">
          Next: Settings <i class="bi bi-arrow-right ms-1"></i>
        </button>
      </div>
    </div>

    <div id="step4" style="display:none">
      <div class="row g-3">
        <div class="col-12 col-lg-8">
          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-gear-fill" style="color:var(--accent)"></i>Publication Settings
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Status</label>
                  <select class="form-control">
                    <option>Draft</option>
                    <option selected>Published</option>
                    <option>Under Review</option>
                    <option>Archived</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label">Visibility</label>
                  <select class="form-control">
                    <option selected>Public</option>
                    <option>Enrolled Only</option>
                    <option>Private (Link Only)</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="form-label">Batch / Cohort</label>
                  <select class="form-control">
                    <option>No Batch (Self-Paced)</option>
                    <option>Batch A — Mar 2026</option>
                    <option>Batch B — Apr 2026</option>
                    <option>Batch C — May 2026</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-0">
                  <label class="form-label">Publish Date</label>
                  <input type="date" class="form-control" value="2026-03-25">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="form-card">
            <div class="form-section-title">
              <i class="bi bi-check-circle-fill" style="color:#10b981"></i>Review &amp; Publish
            </div>
            <div class="d-flex flex-column gap-2 mb-4">
              @foreach($reviewChecks as $check)
              <div style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;font-size:.9rem"></i>
                <span class="text-sm">{{ $check }}</span>
              </div>
              @endforeach
              <div style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-dash-circle-fill" style="color:var(--muted);font-size:.9rem"></i>
                <span class="text-sm text-muted">Preview video (optional)</span>
              </div>
            </div>
            <button class="btn btn-primary btn-sm w-100 mb-2" onclick="publishCourse()">
              <i class="bi bi-send-fill me-1"></i>Publish Course
            </button>
            <button class="btn btn-ghost btn-sm w-100" onclick="saveDraft()">
              <i class="bi bi-floppy me-1"></i>Save as Draft
            </button>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-start mt-3">
        <button class="btn btn-ghost btn-sm" onclick="goStep(3)">
          <i class="bi bi-arrow-left me-1"></i>Back
        </button>
      </div>
    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  let currentStep = 1;

  function goStep(n) {
    document.getElementById('step' + currentStep).style.display = 'none';
    document.getElementById('step' + n).style.display = '';

    for (let i = 1; i <= 4; i++) {
      let el = document.getElementById('step-ind-' + i);
      if (el) {
        el.classList.remove('active', 'done');
        if (i < n) el.classList.add('done');
        if (i === n) el.classList.add('active');
      }

      let ln = document.getElementById('line-' + i);
      if (ln) ln.style.background = i < n ? '#10b981' : 'var(--border)';
    }

    currentStep = n;
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }

  function saveDraft() {
    showToast('Draft saved successfully', 'success');
  }

  function publishCourse() {
    let t = document.getElementById('courseTitle');
    if (t && !t.value.trim()) {
      showToast('Please enter a course title', 'error');
      goStep(1);
      return;
    }

    showToast('Course published successfully! 🎉', 'success');
    setTimeout(function() {
      window.location.href = "{{ route('courses') }}";
    }, 1800);
  }

  function addTag() {
    let inp = document.getElementById('tagInput');
    let val = inp.value.trim();
    if (!val) return;

    let chip = document.createElement('span');
    chip.className = 'tag-chip';
    chip.innerHTML = val + '<button type="button" onclick="this.parentElement.remove()" title="Remove">×</button>';
    document.getElementById('tagsContainer').appendChild(chip);
    inp.value = '';
  }

  document.addEventListener('DOMContentLoaded', function() {
    let tagInput = document.getElementById('tagInput');
    if (tagInput) {
      tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          addTag();
        }
      });
    }
  });
</script>
@endpush