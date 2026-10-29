@extends('Admin.layouts.app')

@section('title', 'Feedback Form Details')

@section('content')
<main class="main-content">
  <div class="page-body p-3">
    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
          <div>
            <h2 class="mb-1">{{ $form->title }}</h2>
            <p class="text-muted mb-2">{{ $form->description }}</p>
            <div><strong>Session:</strong> {{ $form->session_names }}</div>
            <div><strong>Center:</strong> {{ $form->center_names }}</div>
            <div><strong>Programme:</strong> {{ $form->programme_names }}</div>
            <div><strong>Technology:</strong> {{ $form->technology_names }}</div>
            <div><strong>Batch:</strong> {{ $form->batch_names }}</div>
          </div>

          <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.feedback.edit', $form->form_id) }}" class="btn btn-warning">Edit Form</a>
            <a href="{{ route('admin.feedback.overall.analytics') }}" class="btn btn-success">Overall Analytics</a>
            <a href="{{ route('admin.feedback.manage') }}" class="btn btn-outline-dark">Back</a>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body">
        <h4 class="mb-3">Questions</h4>
        @foreach(($form->questions_json ?? []) as $index => $question)
        <div class="border rounded-4 p-3 mb-3">
          <div class="fw-bold">{{ $index + 1 }}. {{ $question['text'] ?? '' }}</div>
          <div class="text-muted small">
            Type: {{ $question['type'] ?? '' }}
            @if(!empty($question['required'])) | Required @endif
          </div>

          @if(!empty($question['options']) && is_array($question['options']))
          <div class="mt-2">
            @foreach($question['options'] as $option)
            <span class="badge bg-light text-dark border me-1">{{ $option }}</span>
            @endforeach
          </div>
          @endif
        </div>
        @endforeach
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <h4 class="mb-3">Responses</h4>

        @forelse($form->responses as $response)
        <div class="border rounded-4 p-3 mb-3">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
              <div class="fw-bold">{{ $response->name ?: ($response->username ?: 'Student') }}</div>
              <div class="text-muted small">
                {{ $response->email ?: '-' }}
                @if(!empty($response->mobile)) | {{ $response->mobile }} @endif
              </div>
            </div>
            <span class="badge bg-primary">Response #{{ $response->response_id }}</span>
          </div>

          @foreach(($response->answers_json ?? []) as $answer)
          <div class="mb-2">
            <div class="fw-semibold">{{ $answer['question'] ?? 'Question' }}</div>
            <div>
              @if(isset($answer['value']) && is_array($answer['value']))
              {{ implode(', ', $answer['value']) }}
              @else
              {{ $answer['value'] ?? '-' }}
              @endif
            </div>
          </div>
          @endforeach
        </div>
        @empty
        <div class="alert alert-info mb-0">No responses found for this form.</div>
        @endforelse
      </div>
    </div>
  </div>
</main>
@endsection