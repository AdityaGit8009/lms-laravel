@extends('Admin.layouts.app')

@section('title', 'Subject Mock Tests')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="mb-1 h4">Subject Mock Tests</h1>
        <p class="mb-0 text-muted">All subject-wise mock tests.</p>
      </div>
      <a href="{{ route('admin.subject-mocktest.create') }}" class="btn btn-primary">+ Add Mock Test</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Title</th>
                <th>Max Marks</th>
                <th>File</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($mockTests as $key => $row)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row->subject_name ?? '-' }}</td>
                <td>{{ $row->title }}</td>
                <td>{{ $row->max_marks }}</td>
                <td>
                  @if($row->upload_file)
                  <a href="{{ asset('uploads/mock-tests/' . $row->upload_file) }}" target="_blank">View File</a>
                  @else
                  -
                  @endif
                </td>
                <td class="d-flex gap-2">
                  <a href="{{ route('admin.subject-mocktest.show', $row->subject_mock_test_id) }}" class="btn btn-sm btn-primary">View</a>
                  <a href="{{ route('admin.subject-mocktest.edit', $row->subject_mock_test_id) }}" class="btn btn-sm btn-warning text-dark">Edit</a>
                  <form action="{{ route('admin.subject-mocktest.delete', $row->subject_mock_test_id) }}" method="POST" onsubmit="return confirm('Delete this mock test?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted">No mock test found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection