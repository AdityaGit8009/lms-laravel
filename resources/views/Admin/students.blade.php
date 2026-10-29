@extends('Admin.layouts.app')

@section('title','Students')

@section('content')

@php
$students = [
[8,'Ankit Verma','ankit@email.com','Data Science Pro','Active','92%','9,840','24 Mar','batch-A'],
[5,'Priya Sharma','priya@email.com','React Masterclass','Active','78%','8,210','22 Mar','batch-B'],
[5,'Priya Sharma','priya@email.com','React Masterclass','Active','78%','8,210','22 Mar','batch-B'],
[9,'Sita Nair','sita@email.com','Python Beginners','Active','65%','7,560','20 Mar','batch-A'],
[15,'Rahul Tiwari','rahul@email.com','AWS Cloud Essentials','Active','54%','6,900','19 Mar','batch-C'],
];

$sm = [
'Active'=>'badge-green',
'Inactive'=>'badge-muted',
'Pending'=>'badge-amber'
];
@endphp

<main class="main-content">
  <div class="page-body">

    <!-- HEADER -->
    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span><span>Students</span>
        </div>
        <h1>Students</h1>
        <p>Manage enrolled students</p>
      </div>

      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm" data-bs-toggle="modal" data-bs-target="#bulkModal">
          Bulk Action
        </button>

        <a href="{{ route('admin.add_student') }}" class="btn btn-primary btn-sm">
          Add Student
        </a>
      </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-row">
      @foreach([
      ['8,472','Total Students'],
      ['7,210','Active'],
      ['892','Inactive'],
      ['370','Pending']
      ] as $s)
      <div class="summary-box">
        <div class="sb-val">{{ $s[0] }}</div>
        <div class="sb-lbl">{{ $s[1] }}</div>
      </div>
      @endforeach
    </div>

    <!-- TABLE -->
    <div class="card">
      <div class="card-header">
        <div class="card-title">All Students</div>
      </div>

      <div class="card-body">

        <table id="studentsTable" class="table">

          <thead>
            <tr>
              <th>Student</th>
              <th>Email</th>
              <th>Course</th>
              <th>Progress</th>
              <th>XP</th>
              <th>Status</th>
              <th>Joined</th>
              <th>Batch</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            @foreach($students as $s)
            <tr>

              <td>{{ $s[1] }}</td>
              <td>{{ $s[2] }}</td>
              <td>{{ $s[3] }}</td>

              <td>{{ $s[5] }}</td>

              <td>{{ $s[6] }}</td>

              <td>
                <span class="badge {{ $sm[$s[4]] }}">
                  {{ $s[4] }}
                </span>
              </td>

              <td>{{ $s[7] }}</td>

              <td>{{ $s[8] }}</td>

              <td>
                <button class="btn btn-sm">View</button>
              </td>

            </tr>
            @endforeach
          </tbody>

        </table>

      </div>
    </div>

  </div>
</main>

@endsection

@push('scripts')
<script>
  initDataTable('studentsTable');
</script>
@endpush