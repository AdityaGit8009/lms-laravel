@extends('Admin.layouts.app')

@section('title', 'Schedule')

@section('content')

@php
$eventsByDay = [
3 => [['ev-blue', 'React Webinar']],
5 => [['ev-green', 'Python Quiz']],
8 => [['ev-amber', 'Payout Day'], ['ev-rose', 'Deadline']],
10 => [['ev-accent', 'New Batch']],
12 => [['ev-blue', 'UX Workshop']],
15 => [['ev-green', 'Data Live']],
18 => [['ev-amber', 'Review Call']],
20 => [['ev-blue', 'AWS Webinar'], ['ev-green', 'ML Quiz']],
23 => [['ev-accent', 'TODAY']],
25 => [['ev-amber', 'Payout']],
26 => [['ev-blue', 'AI Webinar']],
28 => [['ev-rose', 'AWS Upload']],
30 => [['ev-accent', 'Platform Up']],
31 => [['ev-green', 'Awards']],
];

$upcoming = [
['24', 'Mar', 'Assignment Review — React Masterclass', 'ev-rose', '09:00 AM', 'Online', 'Ravi Kumar'],
['25', 'Mar', 'Instructor Payout Processing', 'ev-amber', '11:00 AM', 'Admin', 'Finance Team'],
['26', 'Mar', 'Webinar: AI in Education', 'ev-blue', '03:00 PM', 'Zoom', 'Amit Patel'],
['28', 'Mar', 'Content Upload — AWS Module 5', 'ev-green', '05:00 PM', 'Portal', 'Deepa Menon'],
['30', 'Mar', 'Platform Upgrade Window', 'ev-accent', '11:00 PM', 'System', 'IT Team'],
['31', 'Mar', 'Monthly Awards Announcement', 'ev-green', '04:00 PM', 'Online', 'Admin'],
];

$evColor = [
'ev-blue' => '#0ea5e9',
'ev-green' => '#10b981',
'ev-amber' => '#f59e0b',
'ev-rose' => '#f43f5e',
'ev-accent' => '#00d4aa',
];

$allEvents = [
['03 Mar', 'React Webinar', 'Webinar', 'Ravi Kumar', '03:00 PM', 'Online', 'badge-sky', 'Completed'],
['05 Mar', 'Python Quiz — Module 3', 'Quiz', 'Karan Gupta', '10:00 AM', 'Portal', 'badge-green', 'Completed'],
['08 Mar', 'Instructor Payout', 'Finance', 'Admin', '11:00 AM', 'Admin', 'badge-amber', 'Completed'],
['10 Mar', 'New Batch Orientation', 'Event', 'Arjun Mehta', '09:00 AM', 'Zoom', 'badge-accent', 'Completed'],
['12 Mar', 'UX Design Workshop', 'Workshop', 'Neha Joshi', '02:00 PM', 'Studio', 'badge-sky', 'Completed'],
['15 Mar', 'Data Science Live Class', 'Live', 'Amit Patel', '05:00 PM', 'Zoom', 'badge-green', 'Completed'],
['18 Mar', 'Q2 Review Call', 'Meeting', 'Admin', '04:00 PM', 'Meet', 'badge-amber', 'Completed'],
['20 Mar', 'AWS Certification Webinar', 'Webinar', 'Deepa Menon', '03:00 PM', 'Zoom', 'badge-sky', 'Completed'],
['23 Mar', 'Platform Maintenance', 'System', 'IT Team', '02:00 AM', 'System', 'badge-accent', 'Today'],
['24 Mar', 'Assignment Review — React', 'Review', 'Ravi Kumar', '09:00 AM', 'Online', 'badge-rose', 'Upcoming'],
['25 Mar', 'Instructor Payout Processing', 'Finance', 'Finance Team', '11:00 AM', 'Admin', 'badge-amber', 'Upcoming'],
['26 Mar', 'Webinar: AI in Education', 'Webinar', 'Amit Patel', '03:00 PM', 'Zoom', 'badge-sky', 'Upcoming'],
['28 Mar', 'Content Upload — AWS Module 5', 'Task', 'Deepa Menon', '05:00 PM', 'Portal', 'badge-green', 'Upcoming'],
['30 Mar', 'Platform Upgrade Window', 'System', 'IT Team', '11:00 PM', 'System', 'badge-accent', 'Upcoming'],
['31 Mar', 'Monthly Awards Announcement', 'Event', 'Admin', '04:00 PM', 'Online', 'badge-green', 'Upcoming'],
];

$statusColors = [
'Completed' => 'badge-muted',
'Today' => 'badge-accent',
'Upcoming' => 'badge-sky',
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Schedule</span>
        </div>
        <h1>Schedule &amp; Calendar</h1>
        <p>Track sessions, webinars, deadlines and important events.</p>
      </div>

      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm" type="button" onclick="showToast('Calendar exported','success')">
          <i class="bi bi-download me-1"></i>Export .ics
        </button>

        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addEventModal">
          <i class="bi bi-plus-lg me-1"></i>Add Event
        </button>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <!-- CALENDAR -->
      <div class="col-12 col-xl-8 d1">
        <div class="card h-100">
          <div class="card-header">
            <div class="calendar-nav" style="width:100%">
              <button class="icon-btn" type="button" style="width:32px;height:32px;" onclick="showToast('Previous month','info')">
                <i class="bi bi-chevron-left"></i>
              </button>

              <div class="cal-month">March 2026</div>

              <button class="icon-btn" type="button" style="width:32px;height:32px;" onclick="showToast('Next month','info')">
                <i class="bi bi-chevron-right"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="cal-grid mb-1">
              @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
              <div class="cal-day-hdr">{{ $d }}</div>
              @endforeach
            </div>

            <div class="cal-grid">
              @for ($d = 1; $d <= 31; $d++)
                @php
                $isToday=($d===23);
                $dayEvents=$eventsByDay[$d] ?? [];
                @endphp

                <div class="cal-cell {{ $isToday ? 'today' : '' }}" onclick="showToast('March {{ $d }} events','info')">
                <div class="cal-date">{{ $d }}</div>

                @foreach ($dayEvents as $ev)
                <div class="cal-event {{ $ev[0] }}">{{ $ev[1] }}</div>
                @endforeach
            </div>
            @endfor

            @for ($p = 1; $p <= 4; $p++)
              <div class="cal-cell other-month">
              <div class="cal-date">{{ $p }}</div>
          </div>
          @endfor
        </div>

        <div class="d-flex gap-3 mt-3 flex-wrap">
          @foreach ([
          'ev-blue' => 'Webinar',
          'ev-green' => 'Quiz/Class',
          'ev-amber' => 'Finance',
          'ev-rose' => 'Deadline',
          'ev-accent' => 'Important'
          ] as $cls => $lbl)
          <span style="display:flex;align-items:center;gap:5px;font-size:.7rem;color:var(--muted);">
            <span style="width:9px;height:9px;border-radius:50%;background:{{ $evColor[$cls] }};display:inline-block;"></span>
            {{ $lbl }}
          </span>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- UPCOMING -->
  <div class="col-12 col-xl-4 d2">
    <div class="card h-100">
      <div class="card-header">
        <div>
          <div class="card-title">Upcoming Events</div>
          <div class="card-subtitle">Next 10 days</div>
        </div>

        <button class="btn btn-ghost btn-xs" type="button" data-bs-toggle="modal" data-bs-target="#addEventModal">
          <i class="bi bi-plus"></i>
        </button>
      </div>

      <div class="card-body">
        @foreach ($upcoming as $ev)
        <div class="event-list-item">
          <div class="event-date-box">
            <div class="eday">{{ $ev[0] }}</div>
            <div class="emon">{{ $ev[1] }}</div>
          </div>

          <div style="flex:1;min-width:0;">
            <div class="etitle">{{ $ev[2] }}</div>
            <div class="emeta">
              <i class="bi bi-clock me-1"></i>{{ $ev[4] }}
              &nbsp;·&nbsp;
              <i class="bi bi-geo-alt me-1"></i>{{ $ev[5] }}
              &nbsp;·&nbsp;
              {{ $ev[6] }}
            </div>
          </div>

          <span class="event-time-pill"
            style="color:{{ $evColor[$ev[3]] }};background:{{ $evColor[$ev[3]] }}22;border-color:{{ $evColor[$ev[3]] }}44;">
            {{ $ev[4] }}
          </span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  </div>

  <!-- ALL EVENTS DATATABLE -->
  <div class="card d1">
    <div class="card-header mb-3">
      <div>
        <div class="card-title">All Events — March 2026</div>
      </div>

      <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="bi bi-plus-lg me-1"></i>Add Event
      </button>
    </div>

    <div class="card-body pt-0">
      <div class="tbl-wrap">
        <table id="eventsTable" class="tbl" style="width:100%">
          <thead>
            <tr>
              <th>Date</th>
              <th>Event</th>
              <th>Type</th>
              <th>By / For</th>
              <th>Time</th>
              <th>Platform</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($allEvents as $ev)
            @php
            $sc = $statusColors[$ev[7]] ?? 'badge-muted';

            $typeBadge = $ev[5] === 'Zoom'
            ? 'badge-sky'
            : ($ev[5] === 'Admin' ? 'badge-amber' : 'badge-muted');
            @endphp
            <tr>
              <td class="fw-600 text-sm">{{ $ev[0] }}</td>
              <td class="fw-600 text-sm">{{ $ev[1] }}</td>
              <td>
                <span class="badge {{ $typeBadge }}">{{ $ev[2] }}</span>
              </td>
              <td class="text-sm">{{ $ev[3] }}</td>
              <td class="text-sm text-muted">{{ $ev[4] }}</td>
              <td class="text-sm">{{ $ev[5] }}</td>
              <td>
                <span class="badge {{ $sc }}">{{ $ev[7] }}</span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <button class="icon-btn" type="button" style="width:28px;height:28px;font-size:.75rem"
                    onclick="showToast('View event','info')">
                    <i class="bi bi-eye-fill"></i>
                  </button>

                  <button class="icon-btn" type="button" style="width:28px;height:28px;font-size:.75rem"
                    data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="bi bi-pencil-fill"></i>
                  </button>

                  <button class="icon-btn" type="button" style="width:28px;height:28px;font-size:.75rem;color:#f43f5e"
                    onclick="showToast('Event deleted','error')">
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
</main>

<!-- ADD EVENT MODAL -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-calendar-plus-fill me-2" style="color:var(--accent)"></i>Add / Edit Event
        </h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="form-group">
              <label class="form-label">Event Title <span class="req">*</span></label>
              <input type="text" class="form-control" placeholder="e.g. React Masterclass Webinar">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Event Type</label>
              <select class="form-control">
                <option>Webinar</option>
                <option>Live Class</option>
                <option>Quiz</option>
                <option>Workshop</option>
                <option>Finance</option>
                <option>System</option>
                <option>Meeting</option>
                <option>Event</option>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Assigned To</label>
              <select class="form-control">
                <option>Admin</option>
                <option>Ravi Kumar</option>
                <option>Neha Joshi</option>
                <option>Amit Patel</option>
                <option>Deepa Menon</option>
                <option>Finance Team</option>
                <option>IT Team</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Date <span class="req">*</span></label>
              <input type="date" class="form-control" value="2026-03-25">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Start Time</label>
              <input type="time" class="form-control" value="15:00">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">End Time</label>
              <input type="time" class="form-control" value="16:30">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Platform / Venue</label>
              <select class="form-control">
                <option>Zoom</option>
                <option>Online</option>
                <option>Portal</option>
                <option>Admin</option>
                <option>System</option>
                <option>Offline</option>
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Meeting Link</label>
              <input type="url" class="form-control" placeholder="https://zoom.us/j/...">
            </div>
          </div>

          <div class="col-12">
            <div class="form-group mb-0">
              <label class="form-label">Description</label>
              <textarea class="form-control" placeholder="Event details, agenda, notes..." style="min-height:80px;"></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-ghost btn-sm" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-primary btn-sm" type="button"
          onclick="showToast('Event saved!','success'); bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide()">
          <i class="bi bi-check2 me-1"></i>Save Event
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof initDataTable === 'function') {
      initDataTable('eventsTable', {
        columnDefs: [{
          targets: [7],
          orderable: false
        }],
        order: [
          [0, 'asc']
        ]
      });
    }
  });
</script>
@endpush