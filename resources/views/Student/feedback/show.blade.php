@extends('Student.layouts.app')

@section('title', $form->title)

@section('content')
<div class="container-fluid py-3">
  <div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body">
      <h3 class="mb-1">{{ $form->title }}</h3>
      <p class="text-muted mb-0">{{ $form->description ?: 'No description available.' }}</p>
    </div>
  </div>

  @if(session('error'))
  <div class="alert alert-danger rounded-3">{{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('student.feedback.store', $form->form_id) }}">
    @csrf

    @foreach(($form->questions_json ?? []) as $index => $question)
    @php
    $field = 'question_' . $index;
    $type = $question['type'] ?? 'short_answer';
    $required = !empty($question['required']);
    $options = $question['options'] ?? [];
    @endphp

    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body">
        <label class="form-label fw-bold">
          {{ $index + 1 }}. {{ $question['text'] ?? 'Question' }}
          @if($required)
          <span class="text-danger">*</span>
          @endif
        </label>

        @if($type === 'short_answer')
        <input type="text" name="{{ $field }}" class="form-control" value="{{ old($field) }}">

        @elseif($type === 'paragraph')
        <textarea name="{{ $field }}" class="form-control" rows="4">{{ old($field) }}</textarea>

        @elseif($type === 'dropdown')
        <select name="{{ $field }}" class="form-select">
          <option value="">Select option</option>
          @foreach($options as $option)
          <option value="{{ $option }}" {{ old($field) == $option ? 'selected' : '' }}>
            {{ $option }}
          </option>
          @endforeach
        </select>

        @elseif($type === 'radio')
        @foreach($options as $option)
        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" name="{{ $field }}" value="{{ $option }}" id="{{ $field }}_{{ $loop->index }}"
            {{ old($field) == $option ? 'checked' : '' }}>
          <label class="form-check-label" for="{{ $field }}_{{ $loop->index }}">
            {{ $option }}
          </label>
        </div>
        @endforeach

        @elseif($type === 'checkbox')
        @foreach($options as $option)
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="{{ $field }}[]" value="{{ $option }}" id="{{ $field }}_{{ $loop->index }}"
            {{ is_array(old($field)) && in_array($option, old($field)) ? 'checked' : '' }}>
          <label class="form-check-label" for="{{ $field }}_{{ $loop->index }}">
            {{ $option }}
          </label>
        </div>
        @endforeach

        @elseif($type === 'rating')
        <select name="{{ $field }}" class="form-select">
          <option value="">Select rating</option>
          @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" {{ old($field) == $i ? 'selected' : '' }}>
            {{ $i }} Star
            </option>
            @endfor
        </select>
        @endif
      </div>
    </div>
    @endforeach

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Submit Feedback</button>
      <a href="{{ route('student.feedback.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
  </form>
</div>
@endsection