@extends('Admin.layouts.app')

@section('title', 'Student Approvals')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Student Approvals</span>
        </div>
        <h1>Student Approvals</h1>
        <p>Review and update student approval status from the registration pipeline.</p>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-3">
      {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mb-3">
      {{ $errors->first() }}
    </div>
    @endif

    <div class="row">
      <div class="col-12">
        <div class="card d1">
          <div class="card-header">
            <div>
              <div class="card-title">All Registered Students</div>
              <div class="card-subtitle">Admin can update approval workflow directly from this screen.</div>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle" id="approvalTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>College</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Program</th>
                    <th>Current Status</th>
                    <th style="min-width: 260px;">Update Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($students as $student)
                  @php
                  $status = $student->approval_status ?? 'Pending';
                  $badgeClass = 'bg-warning';

                  if ($status === 'Approved') {
                  $badgeClass = 'bg-success';
                  } elseif ($status === 'Rejected') {
                  $badgeClass = 'bg-danger';
                  }
                  @endphp

                  <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->college }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->mobile }}</td>
                    <td>{{ $student->course_name }}</td>
                    <td>{{ $student->year_name }}</td>
                    <td>{{ $student->program_name }}</td>
                    <td>
                      <span class="badge {{ $badgeClass }}">
                        {{ $status }}
                      </span>
                    </td>
                    <td>
                      <form action="{{ route('admin.approvals.updateStatus', $student->id) }}" method="POST" class="d-flex gap-2">
                        @csrf

                        <select name="approval_status" class="form-select form-select-sm">
                          <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                          <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                          <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm">
                          Update
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted">
                      No student records found.
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>
@endsection