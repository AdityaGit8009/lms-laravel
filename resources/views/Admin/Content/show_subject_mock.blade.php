@extends('Admin.layouts.app')

@section('title', 'View Subject Mock Test')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="mb-1 h4">View Mock Test</h1>
        <p class="mb-0 text-muted">Single row stored mock test content.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.subject-mocktest.edit', $mockTest->subject_mock_test_id) }}" class="btn btn-warning text-dark">Edit</a>
        <a href="{{ route('admin.subject-mocktest.index') }}" class="btn btn-outline-secondary">Back</a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="row g-3 mb-4">
          <div class="col-md-4"><strong>Subject:</strong> {{ $mockTest->subject_name ?? '-' }}</div>
          <div class="col-md-4"><strong>Title:</strong> {{ $mockTest->title }}</div>
          <div class="col-md-4"><strong>Max Marks:</strong> {{ $mockTest->max_marks }}</div>
        </div>

        @if($mockTest->upload_file)
        <div class="mb-3">
          <strong>Attachment:</strong>
          <a href="{{ asset('uploads/mock-tests/' . $mockTest->upload_file) }}" target="_blank">Open File</a>
        </div>
        @endif

        <div class="border rounded-4 p-3 bg-white">
          {!! $mockTest->mock_test !!}
        </div>
      </div>
    </div>
  </div>
</main>
@endsection