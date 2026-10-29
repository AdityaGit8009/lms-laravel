@extends('Admin.layouts.app')

@section('title', 'Announcements')

@section('content')
@php
$announcements = [
[
'title' => 'Platform Upgrade Scheduled — 30 March',
'tag_class' => 'atag-urgent',
'tag' => 'URGENT',
'audience' => 'All Users',
'author' => 'Arjun Mehta',
'date' => '22 Mar 2026',
'views' => '2.4K',
'likes' => '128',
'comments' => '15',
'pinned' => true,
'body' => 'The EduCore platform will undergo a major upgrade on 30 March 2026 from 11 PM to 3 AM. All services will be temporarily unavailable. Students and instructors should save their progress beforehand.',
],
[
'title' => 'New Quiz Template Available in Course Builder',
'tag_class' => 'atag-general',
'tag' => 'NEW',
'audience' => 'Instructors',
'author' => 'Neha Joshi',
'date' => '20 Mar 2026',
'views' => '1.1K',
'likes' => '64',
'comments' => '8',
'pinned' => false,
'body' => 'We have added a brand new adaptive quiz template to the Course Builder with MCQ, True/False and short answer question types.',
],
[
'title' => 'Instructor Payout Processing — 25 March',
'tag_class' => 'atag-update',
'tag' => 'UPDATE',
'audience' => 'Instructors',
'author' => 'Finance Team',
'date' => '19 Mar 2026',
'views' => '876',
'likes' => '52',
'comments' => '6',
'pinned' => false,
'body' => 'All instructor payouts for March 2026 will be processed on 25 March. Ensure your bank details are updated in the payment section.',
],
[
'title' => 'Discussion Boards Now Active for All Courses',
'tag_class' => 'atag-info',
'tag' => 'INFO',
'audience' => 'All Users',
'author' => 'Arjun Mehta',
'date' => '15 Mar 2026',
'views' => '3.2K',
'likes' => '210',
'comments' => '34',
'pinned' => false,
'body' => 'Discussion boards have been enabled for all active courses. Students can ask questions, share resources and collaborate with peers.',
],
[
'title' => 'March Batch Orientation — Recording Available',
'tag_class' => 'atag-event',
'tag' => 'EVENT',
'audience' => 'New Students',
'author' => 'Ravi Kumar',
'date' => '10 Mar 2026',
'views' => '640',
'likes' => '45',
'comments' => '9',
'pinned' => false,
'body' => 'The March 2026 batch orientation recording is now available in the Library. All new students are advised to watch the full orientation video.',
],
[
'title' => 'Monthly Leaderboard Awards — 31 March',
'tag_class' => 'atag-event',
'tag' => 'EVENT',
'audience' => 'All Students',
'author' => 'Arjun Mehta',
'date' => '08 Mar 2026',
'views' => '1.8K',
'likes' => '92',
'comments' => '22',
'pinned' => false,
'body' => 'The monthly leaderboard awards ceremony will be held on 31 March 2026 at 4 PM. Top 10 students in each course receive certificates and rewards.',
],
];

$filters = ['All', 'General', 'Urgent', 'Event', 'Update', 'Info'];

$stats = [
['24', 'Total Posted'],
['3', 'This Week'],
['9.2K', 'Total Reach'],
['87%', 'Open Rate'],
];

$templates = [
'Maintenance Notice',
'New Course Launch',
'Payout Reminder',
'Holiday Notice',
'Exam Schedule',
'Policy Update',
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Announcements</span>
        </div>
        <h1>Announcements</h1>
        <p>Broadcast messages to students, instructors and staff.</p>
      </div>
      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm" onclick="showToast('Showing archived','info')">
          <i class="bi bi-archive me-1"></i>Archived
        </button>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
          <i class="bi bi-plus-lg me-1"></i>New Announcement
        </button>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-xl-8 d1">

        <div class="ann-compose">
          <div class="ann-compose-title">
            <i class="bi bi-pencil-square me-2" style="color:var(--accent)"></i>Quick Compose
          </div>

          <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" id="quickTitle" placeholder="Announcement title…">
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-group mb-0">
                <label class="form-label">Type</label>
                <select class="form-control" id="quickType">
                  <option>General</option>
                  <option>Urgent</option>
                  <option>Event</option>
                  <option>Update</option>
                  <option>Info</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group mb-0">
                <label class="form-label">Audience</label>
                <select class="form-control">
                  <option>All Users</option>
                  <option>All Students</option>
                  <option>Instructors</option>
                  <option>New Students</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group mb-0">
                <label class="form-label">Schedule</label>
                <input type="date" class="form-control" value="2026-03-24">
              </div>
            </div>
          </div>

          <div class="form-group mt-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" id="quickMsg" placeholder="Write your announcement message here…" style="min-height:90px;"></textarea>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-ghost btn-sm" onclick="showToast('Saved as draft','info')">
              <i class="bi bi-floppy me-1"></i>Save Draft
            </button>
            <button class="btn btn-ghost btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
              <i class="bi bi-arrows-fullscreen me-1"></i>Full Form
            </button>
            <button class="btn btn-primary btn-sm" onclick="publishAnn()">
              <i class="bi bi-send-fill me-1"></i>Publish Now
            </button>
          </div>
        </div>

        <div class="d-flex gap-2 mb-3 flex-wrap">
          @foreach($filters as $filter)
          <button class="filter-tab {{ $filter === 'All' ? 'active' : '' }}"
            onclick="document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active')); this.classList.add('active')">
            {{ $filter }}
          </button>
          @endforeach
        </div>

        @foreach($announcements as $i => $announcement)
        <div class="ann-card d{{ ($i % 5) + 1 }}" style="position:relative;">
          @if($announcement['pinned'])
          <i class="bi bi-pin-angle-fill ann-pin"></i>
          @endif

          <div class="ann-card-header">
            <span class="ann-tag {{ $announcement['tag_class'] }}">{{ $announcement['tag'] }}</span>
            <div class="ann-card-title">{{ $announcement['title'] }}</div>
            <span class="audience-badge">
              <i class="bi bi-people me-1"></i>{{ $announcement['audience'] }}
            </span>
          </div>

          <div class="ann-body">{{ $announcement['body'] }}</div>

          <div class="ann-footer">
            <span><i class="bi bi-person-circle"></i>{{ $announcement['author'] }}</span>
            <span><i class="bi bi-calendar3"></i>{{ $announcement['date'] }}</span>
            <span><i class="bi bi-eye"></i>{{ $announcement['views'] }} views</span>
            <span><i class="bi bi-hand-thumbs-up"></i>{{ $announcement['likes'] }}</span>
            <span><i class="bi bi-chat"></i>{{ $announcement['comments'] }}</span>

            <div class="ann-actions">
              <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem"
                data-bs-toggle="modal" data-bs-target="#addAnnouncementModal" title="Edit">
                <i class="bi bi-pencil-fill"></i>
              </button>

              <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem"
                onclick="showToast('{{ $announcement['pinned'] ? 'Unpinned' : 'Pinned' }} announcement','info')" title="Pin">
                <i class="bi bi-pin-angle{{ $announcement['pinned'] ? '-fill' : '' }}"></i>
              </button>

              <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem;color:#f43f5e"
                onclick="showToast('Announcement deleted','error')" title="Delete">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </div>
        </div>
        @endforeach

        <div class="card mt-4">
          <div class="card-header mb-3">
            <div class="card-title">All Announcements — Table View</div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
              <i class="bi bi-plus-lg me-1"></i>Add
            </button>
          </div>

          <div class="card-body pt-0">
            <div class="tbl-wrap">
              <table id="annTable" class="tbl" style="width:100%">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Audience</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Views</th>
                    <th>Pinned</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($announcements as $announcement)
                  <tr>
                    <td class="fw-600 text-sm">{{ $announcement['title'] }}</td>
                    <td>
                      <span class="ann-tag {{ $announcement['tag_class'] }}">{{ $announcement['tag'] }}</span>
                    </td>
                    <td class="text-sm">{{ $announcement['audience'] }}</td>
                    <td class="text-sm">{{ $announcement['author'] }}</td>
                    <td class="text-xs text-muted">{{ $announcement['date'] }}</td>
                    <td class="fw-600">{{ $announcement['views'] }}</td>
                    <td>
                      @if($announcement['pinned'])
                      <i class="bi bi-pin-angle-fill text-accent"></i>
                      @else
                      <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem"
                          data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                          <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button class="icon-btn" style="width:28px;height:28px;font-size:.75rem;color:#f43f5e"
                          onclick="showToast('Deleted','error')">
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

      <div class="col-12 col-xl-4 d2">
        <div class="card mb-3">
          <div class="card-header mb-3">
            <div class="card-title">Announcement Stats</div>
          </div>
          <div class="card-body pt-0">
            <div class="row g-2">
              @foreach($stats as $stat)
              <div class="col-6">
                <div class="mini-stat" style="background:var(--surface2);border-radius:var(--card-r);padding:13px;">
                  <div>
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.2rem;color:var(--text)">
                      {{ $stat[0] }}
                    </div>
                    <div class="text-xs text-muted">{{ $stat[1] }}</div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header mb-3">
            <div class="card-title">Audience Reach</div>
          </div>
          <div class="card-body pt-0">
            <canvas id="reachChart" height="160"></canvas>
          </div>
        </div>

        <div class="card">
          <div class="card-header mb-3">
            <div class="card-title">Quick Templates</div>
          </div>
          <div class="card-body pt-0">
            @foreach($templates as $template)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--border);">
              <span class="text-sm">
                <i class="bi bi-file-text me-2 text-muted"></i>{{ $template }}
              </span>
              <button class="btn btn-ghost btn-xs"
                onclick="document.getElementById('quickTitle').value='{{ addslashes($template) }}'; showToast('Template loaded','success')">
                Use
              </button>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-megaphone-fill me-2" style="color:var(--accent)"></i>Add / Edit Announcement
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="form-group">
              <label class="form-label">Title <span class="req">*</span></label>
              <input type="text" class="form-control" placeholder="Announcement title…">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Type</label>
              <select class="form-control">
                <option>General</option>
                <option>Urgent</option>
                <option>Event</option>
                <option>Update</option>
                <option>Info</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Audience</label>
              <select class="form-control">
                <option>All Users</option>
                <option>All Students</option>
                <option>Instructors</option>
                <option>New Students</option>
                <option>Specific Course</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Publish Date</label>
              <input type="date" class="form-control" value="2026-03-24">
            </div>
          </div>

          <div class="col-12">
            <div class="form-group">
              <label class="form-label">Message <span class="req">*</span></label>
              <textarea class="form-control" placeholder="Full announcement text…" style="min-height:120px;"></textarea>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group mb-0">
              <label class="form-label">Send Email Notification</label>
              <div class="form-check mt-2">
                <input type="checkbox" checked>
                <span class="form-check-label ms-2">Yes, notify via email</span>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group mb-0">
              <label class="form-label">Pin to Top</label>
              <div class="form-check mt-2">
                <input type="checkbox">
                <span class="form-check-label ms-2">Pin this announcement</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-ghost btn-sm"
          onclick="showToast('Saved as draft','info'); bootstrap.Modal.getInstance(document.getElementById('addAnnouncementModal')).hide()">
          <i class="bi bi-floppy me-1"></i>Save Draft
        </button>
        <button class="btn btn-primary btn-sm"
          onclick="showToast('Announcement published!','success'); bootstrap.Modal.getInstance(document.getElementById('addAnnouncementModal')).hide()">
          <i class="bi bi-send-fill me-1"></i>Publish
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function publishAnn() {
    let t = document.getElementById('quickTitle').value.trim();
    let m = document.getElementById('quickMsg').value.trim();

    if (!t) {
      showToast('Please enter a title', 'error');
      return;
    }

    if (!m) {
      showToast('Please enter a message', 'error');
      return;
    }

    showToast('Announcement published!', 'success');
    document.getElementById('quickTitle').value = '';
    document.getElementById('quickMsg').value = '';
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

    new Chart(document.getElementById('reachChart'), {
      type: 'doughnut',
      data: {
        labels: ['All Users', 'Instructors', 'New Students'],
        datasets: [{
          data: [72, 18, 10],
          backgroundColor: [a, '#0ea5e9', '#f59e0b'],
          borderWidth: 0,
          hoverOffset: 5
        }]
      },
      options: {
        cutout: '68%',
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
      initDataTable('annTable', {
        columnDefs: [{
          targets: [7],
          orderable: false
        }],
        order: [
          [4, 'desc']
        ]
      });
    }
  });
</script>
@endpush