@extends('Admin.layouts.app')

@section('title', 'Dashboard')

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
'revenue' => '₹3.2L',
'status' => 'active',
],
[
'emoji' => '🎨',
'title' => 'UX Design Basics',
'bg' => 'rgba(244,63,94,.15)',
'color' => '#f43f5e',
'instructor' => 'Neha Joshi',
'students' => 876,
'completion' => 54,
'revenue' => '₹1.8L',
'status' => 'active',
],
[
'emoji' => '📊',
'title' => 'Data Science Pro',
'bg' => 'rgba(14,165,233,.15)',
'color' => '#0ea5e9',
'instructor' => 'Amit Patel',
'students' => 2103,
'completion' => 91,
'revenue' => '₹5.6L',
'status' => 'active',
],
[
'emoji' => '📣',
'title' => 'Digital Marketing',
'bg' => 'rgba(245,158,11,.15)',
'color' => '#f59e0b',
'instructor' => 'Sunita Rao',
'students' => 567,
'completion' => 30,
'revenue' => '₹0.9L',
'status' => 'draft',
],
[
'emoji' => '🐍',
'title' => 'Python Beginners',
'bg' => 'rgba(139,92,246,.15)',
'color' => '#8b5cf6',
'instructor' => 'Karan Gupta',
'students' => 3418,
'completion' => 65,
'revenue' => '₹7.1L',
'status' => 'active',
],
[
'emoji' => '☁️',
'title' => 'AWS Cloud',
'bg' => 'rgba(14,165,233,.15)',
'color' => '#0ea5e9',
'instructor' => 'Deepa Menon',
'students' => 734,
'completion' => 42,
'revenue' => '₹2.2L',
'status' => 'review',
],
];

$badgeMap = [
'active' => 'badge-green',
'draft' => 'badge-muted',
'review' => 'badge-amber',
];

$activities = [
['img' => '11', 'text' => '<strong>Priya Sharma</strong> enrolled in React Masterclass', 'time' => '2 min ago'],
['img' => '22', 'text' => '<strong>Ankit Verma</strong> submitted Assignment 4', 'time' => '17 min ago'],
['img' => '33', 'text' => '<strong>Neha Joshi</strong> published a new module', 'time' => '1 hr ago'],
['img' => '44', 'text' => '<strong>Ravi Kumar</strong> replied to a forum post', 'time' => '2 hrs ago'],
['img' => '55', 'text' => '<strong>Sita Nair</strong> completed Python Beginners 🎉', 'time' => '3 hrs ago'],
];

$students = [
['img' => '8', 'name' => 'Ankit Verma', 'course' => 'Data Science Pro', 'xp' => '9,840 XP'],
['img' => '5', 'name' => 'Priya Sharma', 'course' => 'React Masterclass', 'xp' => '8,210 XP'],
['img' => '9', 'name' => 'Sita Nair', 'course' => 'Python Beginners', 'xp' => '7,560 XP'],
['img' => '15', 'name' => 'Rahul Tiwari', 'course' => 'AWS Cloud Essentials', 'xp' => '6,900 XP'],
['img' => '16', 'name' => 'Pooja Singh', 'course' => 'UX Design Basics', 'xp' => '5,430 XP'],
];

$announcements = [
['tag' => 'NEW', 'class' => 'atag-new', 'title' => 'Platform upgrade scheduled for 30 March', 'date' => '22 Mar 2026'],
['tag' => 'IMP', 'class' => 'atag-imp', 'title' => 'Instructor payouts processed on 25 March', 'date' => '20 Mar 2026'],
['tag' => 'INFO', 'class' => 'atag-info', 'title' => 'New quiz template in course builder', 'date' => '18 Mar 2026'],
];

$deadlines = [
['icon' => '📝', 'title' => 'Assignment Review — React Masterclass', 'date' => '24 Mar', 'urgent' => true],
['icon' => '🎤', 'title' => 'Webinar: AI in Education', 'date' => '26 Mar', 'urgent' => false],
['icon' => '💳', 'title' => 'Payout Deadline', 'date' => '25 Mar', 'urgent' => true],
['icon' => '📦', 'title' => 'Content Upload — AWS Module 5', 'date' => '28 Mar', 'urgent' => false],
['icon' => '🏆', 'title' => 'Monthly Awards', 'date' => '31 Mar', 'urgent' => false],
];

$stats = [
['ic-accent','bi-people-fill','c1','8,472','Total Students','+8.4%',true,'👨‍🎓'],
['ic-amber','bi-play-btn-fill','c2','47','Active Courses','+3 new',true,'📚'],
['ic-rose','bi-currency-rupee','c3','212,400','Monthly Revenue','+12.1%',true,'💰'],
['ic-sky','bi-patch-check-fill','c4','1,389','Completions','−2.3%',false,'🏆']
];

$categoryLegend = [
['#00d4aa','Technology','38%'],
['#f59e0b','Business','24%'],
['#f43f5e','Design','18%'],
['#0ea5e9','Marketing','12%'],
['#8b5cf6','Others','8%']
];

$quickMetrics = [
['⏱️','4.2h','Avg Watch Time'],
['⭐','4.72','Avg Rating'],
['💬','1.2K','Forum Posts'],
['🎯','73%','Pass Rate']
];
@endphp

<main class="main-content">
  <div class="page-body">
    <div class="page-hdr">
      <div>
        <h1>Good morning, {{ $adminName ?? 'Admin' }} 👋</h1>
        <p>Here's what's happening at EduCore today — {{ now()->format('l, d F Y') }}.</p>
      </div>
      <div class="page-hdr-right d-flex gap-2 align-items-center">
        <a href="{{ route('admin.add_course') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-lg"></i> Add Course
        </a>

        <a href="{{ route('admin.add_student') }}" class="btn btn-ghost btn-sm">
          <i class="bi bi-person-plus-fill"></i> Add Student
        </a>
      </div>
    </div>

    <div class="row g-3 mb-4">
      @foreach($stats as $i => $stat)
      <div class="col-6 col-xl-3 d{{ $i + 1 }}">
        <div class="stat-card">
          <div class="icon-circle ic-44 {{ $stat[0] }}" style="margin-bottom:12px">
            <i class="bi {{ $stat[1] }}"></i>
          </div>
          <div class="stat-value" id="{{ $stat[2] }}">{{ $stat[3] }}</div>
          <div class="stat-label">{{ $stat[4] }}</div>
          <div class="stat-delta {{ $stat[6] ? 'delta-up' : 'delta-down' }}">
            <i class="bi bi-arrow-{{ $stat[6] ? 'up' : 'down' }}-short"></i>{{ $stat[5] }}
          </div>
          <div class="stat-corner">{{ $stat[7] }}</div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 col-xl-8 d1">
        <div class="card h-100">
          <div class="card-header">
            <div>
              <div class="card-title">Enrollment Overview</div>
              <div class="card-subtitle">New &amp; returning students per month</div>
            </div>
            <div class="chart-tabs">
              <button class="chart-tab active" onclick="switchChart(this,'6m')">6M</button>
              <button class="chart-tab" onclick="switchChart(this,'1y')">1Y</button>
              <button class="chart-tab" onclick="switchChart(this,'all')">All</button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="enrollChart" height="230"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-4 d2">
        <div class="card h-100">
          <div class="card-header">
            <div>
              <div class="card-title">By Category</div>
              <div class="card-subtitle">Course distribution</div>
            </div>
          </div>
          <div class="card-body">
            <div style="max-width:150px;margin:0 auto 14px">
              <canvas id="donutChart"></canvas>
            </div>
            <ul class="donut-legend">
              @foreach($categoryLegend as $legend)
              <li>
                <span class="dot" style="background:{{ $legend[0] }}"></span>{{ $legend[1] }}
                <span class="pct">{{ $legend[2] }}</span>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 d1">
        <div class="card">
          <div class="card-header mb-3">
            <div>
              <div class="card-title">Course Management</div>
              <div class="card-subtitle">Live courses, drafts and reviews</div>
            </div>
            <div class="d-flex gap-2">
              <a href="{{ route('admin.courses') }}" class="btn btn-ghost btn-sm">
                <i class="bi bi-grid-2x2"></i> All
              </a>
              <a href="{{ route('admin.add_course') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> New
              </a>
            </div>
          </div>

          <div class="card-body pt-0">
            <div class="tbl-wrap">
              <table id="dashCourseTable" class="tbl" style="width:100%">
                <thead>
                  <tr>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Students</th>
                    <th>Completion</th>
                    <th>Revenue</th>
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
                    <td><span class="text-sm">{{ $course['instructor'] }}</span></td>
                    <td class="fw-600">{{ number_format($course['students']) }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-2" style="min-width:100px">
                        <div class="progress" style="flex:1">
                          <div class="progress-fill" style="width:{{ $course['completion'] }}%"></div>
                        </div>
                        <span class="text-xs text-muted">{{ $course['completion'] }}%</span>
                      </div>
                    </td>
                    <td class="fw-700 text-accent">{{ $course['revenue'] }}</td>
                    <td>
                      <span class="badge {{ $badgeMap[$course['status']] }}">{{ ucfirst($course['status']) }}</span>
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem"
                          onclick="showToast('Editing {{ addslashes($course['title']) }}','info')">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem">
                          <i class="bi bi-eye-fill"></i>
                        </button>
                        <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem;color:#f43f5e"
                          onclick="showToast('Course deleted','error')">
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
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 col-lg-4 d1">
        <div class="card h-100">
          <div class="card-header">
            <div class="card-title">Recent Activity</div>
            <a href="#" class="text-accent text-xs">View all</a>
          </div>
          <div class="card-body">
            @foreach($activities as $activity)
            <div class="activity-item">
              <img src="https://i.pravatar.cc/60?img={{ $activity['img'] }}" class="avatar av-32" alt="">
              <div>
                <div class="activity-text">{!! $activity['text'] !!}</div>
                <div class="activity-time">
                  <i class="bi bi-clock me-1"></i>{{ $activity['time'] }}
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 d2">
        <div class="card h-100">
          <div class="card-header">
            <div class="card-title">Top Students</div>
            <a href="{{ route('admin.students') }}" class="text-accent text-xs">Leaderboard</a>
          </div>
          <div class="card-body">
            @foreach($students as $i => $student)
            <div class="student-row">
              <div class="rank-num">{{ $i + 1 }}</div>
              <img src="https://i.pravatar.cc/60?img={{ $student['img'] }}" class="avatar av-32" alt="">
              <div style="flex:1;min-width:0">
                <div class="sname">{{ $student['name'] }}</div>
                <div class="scourse">{{ $student['course'] }}</div>
              </div>
              <div class="student-xp">{{ $student['xp'] }}</div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 d3">
        <div class="card h-100">
          <div class="card-header">
            <div class="card-title">Announcements</div>
            <a href="{{ route('admin.announcements') }}" class="btn btn-ghost btn-xs">
              <i class="bi bi-arrow-right"></i>
            </a>
          </div>
          <div class="card-body">
            @foreach($announcements as $announcement)
            <div class="announce-item">
              <span class="announce-tag {{ $announcement['class'] }}">{{ $announcement['tag'] }}</span>
              <div class="announce-title">{{ $announcement['title'] }}</div>
              <div class="announce-date">
                <i class="bi bi-calendar3 me-1"></i>{{ $announcement['date'] }}
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-lg-4 d1">
        <div class="card">
          <div class="card-header mb-3">
            <div class="card-title">Quick Metrics</div>
          </div>
          <div class="card-body pt-0">
            <div class="row g-2">
              @foreach($quickMetrics as $metric)
              <div class="col-6">
                <div class="mini-stat">
                  <span style="font-size:1.2rem">{{ $metric[0] }}</span>
                  <div>
                    <div class="mini-stat-val">{{ $metric[1] }}</div>
                    <div class="mini-stat-lbl">{{ $metric[2] }}</div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 d2">
        <div class="card">
          <div class="card-header mb-3">
            <div>
              <div class="card-title">Weekly Revenue</div>
              <div class="card-subtitle">This week vs last week</div>
            </div>
          </div>
          <div class="card-body pt-0">
            <canvas id="revBar" height="148"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4 d3">
        <div class="card">
          <div class="card-header mb-2">
            <div class="card-title">Upcoming Deadlines</div>
          </div>
          <div class="card-body">
            @foreach($deadlines as $deadline)
            <div class="deadline-item">
              <span style="font-size:1rem">{{ $deadline['icon'] }}</span>
              <div style="flex:1;min-width:0;font-size:.8rem;font-weight:500;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $deadline['title'] }}
              </div>
              <span style="font-size:.7rem;padding:3px 9px;border-radius:99px;font-weight:600;flex-shrink:0;background:{{ $deadline['urgent'] ? 'rgba(244,63,94,.12)' : 'var(--surface2)' }};color:{{ $deadline['urgent'] ? '#f43f5e' : 'var(--muted)' }}">
                {{ $deadline['date'] }}
              </span>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  var D6 = {
    l: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
    n: [320, 450, 390, 520, 480, 610],
    r: [180, 220, 190, 260, 240, 310]
  };
  var D1Y = {
    l: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
    n: [210, 280, 300, 340, 310, 380, 320, 450, 390, 520, 480, 610],
    r: [120, 150, 160, 180, 160, 200, 180, 220, 190, 260, 240, 310]
  };
  var DA = {
    l: ['2023', '2024', '2025', '2026'],
    n: [2400, 3800, 5200, 1620],
    r: [1100, 1900, 2800, 860]
  };
  var ec, dc, rb;
  var TT = {
    backgroundColor: 'var(--surface2)',
    borderColor: 'var(--border)',
    borderWidth: 1,
    titleColor: 'var(--text)',
    bodyColor: 'var(--muted)',
    padding: 12,
    cornerRadius: 10
  };

  function acc() {
    return getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#00d4aa';
  }

  function buildE(d) {
    if (ec) ec.destroy();
    var a = acc();
    ec = new Chart(document.getElementById('enrollChart'), {
      type: 'line',
      data: {
        labels: d.l,
        datasets: [{
            label: 'New Students',
            data: d.n,
            borderColor: a,
            backgroundColor: a + '22',
            borderWidth: 2.5,
            tension: .42,
            fill: true,
            pointBackgroundColor: a,
            pointRadius: 4
          },
          {
            label: 'Returning',
            data: d.r,
            borderColor: '#f59e0b',
            backgroundColor: '#f59e0b18',
            borderWidth: 2,
            tension: .42,
            fill: true,
            pointBackgroundColor: '#f59e0b',
            pointRadius: 4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          intersect: false,
          mode: 'index'
        },
        plugins: {
          legend: {
            labels: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans',
                size: 12
              },
              boxWidth: 10,
              usePointStyle: true
            }
          },
          tooltip: TT
        },
        scales: {
          x: {
            grid: {
              color: 'rgba(128,128,128,.07)'
            },
            ticks: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans'
              }
            }
          },
          y: {
            grid: {
              color: 'rgba(128,128,128,.07)'
            },
            ticks: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans'
              }
            }
          }
        }
      }
    });
  }

  function buildD() {
    if (dc) dc.destroy();
    dc = new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: ['Technology', 'Business', 'Design', 'Marketing', 'Others'],
        datasets: [{
          data: [38, 24, 18, 12, 8],
          backgroundColor: ['#00d4aa', '#f59e0b', '#f43f5e', '#0ea5e9', '#8b5cf6'],
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        cutout: '72%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: TT
        }
      }
    });
  }

  function buildR() {
    if (rb) rb.destroy();
    var a = acc();
    rb = new Chart(document.getElementById('revBar'), {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'This Week',
            data: [18200, 24500, 16800, 31200, 27400, 8600, 12100],
            backgroundColor: a + 'bb',
            borderRadius: 5,
            borderSkipped: false
          },
          {
            label: 'Last Week',
            data: [14200, 19800, 15300, 27600, 22100, 9200, 10800],
            backgroundColor: 'rgba(100,116,139,.2)',
            borderRadius: 5,
            borderSkipped: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            labels: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans',
                size: 11
              },
              boxWidth: 9,
              usePointStyle: true
            }
          },
          tooltip: {
            ...TT,
            callbacks: {
              label: function(c) {
                return ' ₹' + c.raw.toLocaleString();
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans',
                size: 11
              }
            }
          },
          y: {
            grid: {
              color: 'rgba(128,128,128,.07)'
            },
            ticks: {
              color: 'var(--muted)',
              font: {
                family: 'DM Sans',
                size: 11
              },
              callback: function(v) {
                return '₹' + Math.round(v / 1000) + 'k';
              }
            }
          }
        }
      }
    });
  }

  function switchChart(btn, r) {
    document.querySelectorAll('.chart-tab').forEach(function(b) {
      b.classList.remove('active')
    });
    btn.classList.add('active');
    buildE({
      '6m': D6,
      '1y': D1Y,
      'all': DA
    } [r]);
  }

  function rebuildCharts() {
    buildE(D6);
    buildR();
  }

  document.addEventListener('DOMContentLoaded', function() {
    buildE(D6);
    buildD();
    buildR();

    if (typeof initDataTable === 'function') {
      initDataTable('dashCourseTable', {
        searching: false,
        paging: false,
        info: false,
        dom: '<"dt-buttons"B>t',
        ordering: true,
        order: [
          [2, 'desc']
        ]
      });
    }
  });
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  function logoutUser() {
    const token = localStorage.getItem('auth_token');

    $.ajax({
      url: '/api/auth/logout',
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token
      },
      success: function(response) {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_role');
        window.location.href = '/login';
      },
      error: function() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_role');
        window.location.href = '/login';
      }
    });
  }
</script>
<script>
  // const token = localStorage.getItem('auth_token');

  $.ajax({
    url: '/api/auth/me',
    method: 'GET',
    headers: {
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    success: function(response) {
      console.log(response);
    },
    error: function(xhr) {
      console.log(xhr.responseText);
    }
  });
</script>
@endpush