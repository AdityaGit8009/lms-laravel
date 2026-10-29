<div class="modal-header">
  <h5 class="modal-title">Login Logs - {{ $student->stu_name }}</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <h6 class="text-muted">Total Session</h6>
          <h4>{{ $totalSessions }}</h4>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <h6 class="text-muted">Total Active Time</h6>
          <h4>{{ $totalActiveTime }}</h4>
        </div>
      </div>
    </div>
  </div>

  <form id="studentLogFilterForm" action="{{ route('admin.student.login.logs', $student->stu_id) }}" method="GET">
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label d-block">&nbsp;</label>
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Login</th>
          <th>Logout</th>
          <th>Last Seen</th>
          <th>Session Time</th>
          <th>Status</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $i => $log)
        @php
        $start = $log->login_at ? \Carbon\Carbon::parse($log->login_at) : null;
        $end = $log->logout_at
        ? \Carbon\Carbon::parse($log->logout_at)
        : ($log->last_seen_at ? \Carbon\Carbon::parse($log->last_seen_at) : now());
        $minutes = $start ? $start->diffInMinutes($end) : 0;
        @endphp
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $log->login_at ? \Carbon\Carbon::parse($log->login_at)->format('d-m-Y h:i A') : '-' }}</td>
          <td>{{ $log->logout_at ? \Carbon\Carbon::parse($log->logout_at)->format('d-m-Y h:i A') : '-' }}</td>
          <td>{{ $log->last_seen_at ? \Carbon\Carbon::parse($log->last_seen_at)->format('d-m-Y h:i A') : '-' }}</td>
          <td>{{ floor($minutes/60) }}h {{ $minutes%60 }}m</td>
          <td>
            @if($log->status === 'online')
            <span class="badge bg-success">Online</span>
            @else
            <span class="badge bg-secondary">Offline</span>
            @endif
          </td>
          <td>{{ $log->ip_address ?? '-' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center">No logs found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>