@extends('Student.layouts.app')

@section('title', 'Feedback Forms')

@section('content')
<div class="container-fluid py-3">
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
      <div class="mb-4">
        <h3 class="mb-1">Available Feedback Forms</h3>
        <p class="text-muted mb-0">
          Session: {{ $student->stu_session ?? '-' }} |
          Programme: {{ $student->stu_course ?? '-' }} |
          Technology: {{ $student->stu_branch ?? '-' }} |
          Batch: {{ $student->stu_pro ?? '-' }}
        </p>
      </div>

      @if(session('success'))
      <div class="alert alert-success rounded-3">{{ session('success') }}</div>
      @endif

      @if(session('info'))
      <div class="alert alert-info rounded-3">{{ session('info') }}</div>
      @endif

      @if($forms->count())
      <div class="row g-3">
        @foreach($forms as $form)
        <div class="col-md-6 col-xl-4">
          <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="mb-2">{{ $form->title }}</h5>
              <p class="text-muted small mb-3">
                {{ $form->description ?: 'No description available.' }}
              </p>

              <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="badge {{ $form->submitted ? 'bg-success' : 'bg-warning text-dark' }}">
                  {{ $form->submitted ? 'Submitted' : 'Pending' }}
                </span>

                @if($form->submitted)
                <button class="btn btn-sm btn-outline-secondary" disabled>
                  Already Submitted
                </button>
                @else
                <a href="{{ route('student.feedback.show', $form->form_id) }}" class="btn btn-sm btn-primary">
                  Fill Form
                </a>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @else
      <div class="alert alert-info rounded-3 mb-0">
        No feedback forms available for your session / programme / technology / batch.
      </div>
      @endif
    </div>
  </div>
</div>
@endsection