@extends('Admin.layouts.app')

@section('title', 'Courses')

@section('content')
@php
$courses = [
[
'emoji' => '⚛️',
'title' => 'React Masterclass',
'bg' => 'rgba(0,212,170,.15)',
'color' => '#00d4aa',
'instructor' => 'Ravi Kumar',
'students' => 1284,
'completion' => 78,
'price' => '₹3,200',
'status' => 'active',
'category' => 'Technology',
'rating' => 4.8,
'duration' => '32h',
],
[
'emoji' => '🎨',
'title' => 'UX Design Basics',
'bg' => 'rgba(244,63,94,.15)',
'color' => '#f43f5e',
'instructor' => 'Neha Joshi',
'students' => 876,
'completion' => 54,
'price' => '₹1,800',
'status' => 'active',
'category' => 'Design',
'rating' => 4.6,
'duration' => '18h',
],
[
'emoji' => '📊',
'title' => 'Data Science Pro',
'bg' => 'rgba(14,165,233,.15)',
'color' => '#0ea5e9',
'instructor' => 'Amit Patel',
'students' => 2103,
'completion' => 91,
'price' => '₹5,600',
'status' => 'active',
'category' => 'Technology',
'rating' => 4.9,
'duration' => '48h',
],
[
'emoji' => '📣',
'title' => 'Digital Marketing',
'bg' => 'rgba(245,158,11,.15)',
'color' => '#f59e0b',
'instructor' => 'Sunita Rao',
'students' => 567,
'completion' => 30,
'price' => '₹900',
'status' => 'draft',
'category' => 'Marketing',
'rating' => 4.3,
'duration' => '22h',
],
[
'emoji' => '🐍',
'title' => 'Python Beginners',
'bg' => 'rgba(139,92,246,.15)',
'color' => '#8b5cf6',
'instructor' => 'Karan Gupta',
'students' => 3418,
'completion' => 65,
'price' => '₹4,999',
'status' => 'active',
'category' => 'Technology',
'rating' => 4.7,
'duration' => '40h',
],
[
'emoji' => '☁️',
'title' => 'AWS Cloud Essentials',
'bg' => 'rgba(14,165,233,.15)',
'color' => '#0ea5e9',
'instructor' => 'Deepa Menon',
'students' => 734,
'completion' => 42,
'price' => '₹2,200',
'status' => 'review',
'category' => 'Technology',
'rating' => 4.5,
'duration' => '28h',
],
[
'emoji' => '🎸',
'title' => 'Music Fundamentals',
'bg' => 'rgba(236,72,153,.15)',
'color' => '#ec4899',
'instructor' => 'Rahul Das',
'students' => 312,
'completion' => 20,
'price' => '₹1,200',
'status' => 'draft',
'category' => 'Arts',
'rating' => 4.2,
'duration' => '15h',
],
[
'emoji' => '💼',
'title' => 'Business Strategy',
'bg' => 'rgba(16,185,129,.15)',
'color' => '#10b981',
'instructor' => 'Anita Mehta',
'students' => 980,
'completion' => 60,
'price' => '₹2,800',
'status' => 'active',
'category' => 'Business',
'rating' => 4.6,
'duration' => '20h',
],
[
'emoji' => '📱',
'title' => 'React Native Apps',
'bg' => 'rgba(99,102,241,.15)',
'color' => '#6366f1',
'instructor' => 'Vikram Singh',
'students' => 654,
'completion' => 45,
'price' => '₹3,500',
'status' => 'active',
'category' => 'Technology',
'rating' => 4.7,
'duration' => '35h',
],
];

$badgeMap = [
'active' => 'badge-green',
'draft' => 'badge-muted',
'review' => 'badge-amber',
];

$stats = [
['ic-accent', 'bi-play-btn-fill', '47', 'Active Courses'],
['ic-amber', 'bi-clock-history', '6', 'Draft / Pending'],
['ic-sky', 'bi-people-fill', '12,847', 'Total Enrollments'],
['ic-green', 'bi-star-fill', '4.72', 'Avg Rating'],
];

$filters = ['All (9)', 'Active', 'Draft', 'Review', 'Archived'];
@endphp

<main class="main-content">
  <div class="page-body">
    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Courses</span>
        </div>
        <h1>Course Management</h1>
        <p>Manage all courses, modules and curriculum.</p>
      </div>
      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm" onclick="showToast('Exported!','success')">
          <i class="bi bi-download"></i> Export
        </button>
        <a href="{{ route('admin.add_course') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-lg"></i> New Course
        </a>
      </div>
    </div>

    <div class="row g-3 mb-4">
      @foreach($stats as $i => $stat)
      <div class="col-6 col-xl-3 d{{ $i + 1 }}">
        <div class="card d-flex flex-row align-items-center gap-3 p-3">
          <div class="icon-circle ic-48 {{ $stat[0] }}">
            <i class="bi {{ $stat[1] }}"></i>
          </div>
          <div>
            <div class="font-syne fw-700" style="font-size:1.5rem;color:var(--text)">
              {{ $stat[2] }}
            </div>
            <div class="text-xs text-muted">{{ $stat[3] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="filter-bar">
      <span class="text-xs fw-600 text-muted">Filter:</span>

      @foreach($filters as $i => $filter)
      <button class="filter-tab {{ $i === 0 ? 'active' : '' }}"
        onclick="document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active')); this.classList.add('active')">
        {{ $filter }}
      </button>
      @endforeach

      <div style="flex:1"></div>

      <select class="form-control" style="max-width:140px;font-size:.78rem;padding:5px 10px">
        <option>All Categories</option>
        <option>Technology</option>
        <option>Design</option>
        <option>Business</option>
        <option>Marketing</option>
      </select>

      <a href="{{ route('admin.add_course') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Add Course
      </a>
    </div>

    <div class="course-grid mb-4">
      @foreach($courses as $i => $course)
      <div class="course-card d{{ ($i % 6) + 1 }}">
        <div class="course-thumb" style="background:{{ $course['bg'] }}">
          <span>{{ $course['emoji'] }}</span>
          <span class="cat-pill">{{ $course['category'] }}</span>
        </div>

        <div class="course-body">
          <div class="course-title">{{ $course['title'] }}</div>

          <div class="course-meta">
            <span><i class="bi bi-people-fill"></i>{{ number_format($course['students']) }}</span>
            <span><i class="bi bi-clock"></i>{{ $course['duration'] }}</span>
            <span style="color:#f59e0b"><i class="bi bi-star-fill"></i>{{ $course['rating'] }}</span>
          </div>

          <div class="progress mb-2">
            <div class="progress-fill" style="width:{{ $course['completion'] }}%"></div>
          </div>

          <div class="d-flex justify-content-between text-xs text-muted mb-2">
            <span>{{ $course['completion'] }}% complete</span>
            <span class="badge {{ $badgeMap[$course['status']] }}">{{ ucfirst($course['status']) }}</span>
          </div>

          <div class="course-instructor">
            <img src="https://i.pravatar.cc/40?u={{ urlencode($course['instructor']) }}" class="avatar av-24" alt="">
            <span class="iname">{{ $course['instructor'] }}</span>
          </div>
        </div>

        <div class="course-footer">
          <div class="course-price">{{ $course['price'] }}</div>
          <div class="d-flex gap-1">
            <button class="icon-btn" style="width:26px;height:26px;font-size:.73rem" onclick="showToast('Editing course','info')">
              <i class="bi bi-pencil-fill"></i>
            </button>
            <button class="icon-btn" style="width:26px;height:26px;font-size:.73rem">
              <i class="bi bi-eye-fill"></i>
            </button>
            <button class="icon-btn" style="width:26px;height:26px;font-size:.73rem;color:#f43f5e" onclick="showToast('Deleted','error')">
              <i class="bi bi-trash3-fill"></i>
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="card d1">
      <div class="card-header mb-3">
        <div>
          <div class="card-title">All Courses — Table View</div>
        </div>
      </div>

      <div class="card-body pt-0">
        <div class="tbl-wrap">
          <table id="coursesTable" class="tbl" style="width:100%">
            <thead>
              <tr>
                <th>Course</th>
                <th>Category</th>
                <th>Instructor</th>
                <th>Students</th>
                <th>Rating</th>
                <th>Duration</th>
                <th>Completion</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($courses as $course)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="icon-circle ic-36" style="background:{{ $course['bg'] }};color:{{ $course['color'] }}">
                      {{ $course['emoji'] }}
                    </div>
                    <span class="fw-600 text-sm">{{ $course['title'] }}</span>
                  </div>
                </td>
                <td class="text-sm">{{ $course['category'] }}</td>
                <td class="text-sm">{{ $course['instructor'] }}</td>
                <td class="fw-600">{{ number_format($course['students']) }}</td>
                <td>
                  <span style="color:#f59e0b;font-weight:700;font-size:.82rem">
                    <i class="bi bi-star-fill"></i> {{ $course['rating'] }}
                  </span>
                </td>
                <td class="text-sm">{{ $course['duration'] }}</td>
                <td>
                  <div class="d-flex align-items-center gap-2" style="min-width:90px">
                    <div class="progress" style="flex:1">
                      <div class="progress-fill" style="width:{{ $course['completion'] }}%"></div>
                    </div>
                    <span class="text-xs">{{ $course['completion'] }}%</span>
                  </div>
                </td>
                <td class="fw-700 text-accent">{{ $course['price'] }}</td>
                <td>
                  <span class="badge {{ $badgeMap[$course['status']] }}">{{ ucfirst($course['status']) }}</span>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem" onclick="showToast('Edit','info')">
                      <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem">
                      <i class="bi bi-eye-fill"></i>
                    </button>
                    <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem;color:#f43f5e" onclick="showToast('Deleted','error')">
                      <i class="bi bi-trash3-fill"></i>
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

    <div class="d-flex align-items-center justify-content-between mt-3">
      <span class="text-xs text-muted">Showing 1–9 of 47 courses</span>
      <div class="d-flex gap-1">
        @for($p = 1; $p <= 5; $p++)
          <button class="pag-btn {{ $p === 1 ? 'active' : '' }}">{{ $p }}</button>
          @endfor
          <button class="pag-btn">…</button>
          <button class="pag-btn">8</button>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof initDataTable === 'function') {
      initDataTable('coursesTable', {
        columnDefs: [{
          targets: [6, 9],
          orderable: false
        }],
        order: [
          [3, 'desc']
        ]
      });
    }
  });
</script>
@endpush