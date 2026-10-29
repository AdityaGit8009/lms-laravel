@extends('Student.layouts.app')

@section('title', 'Quizzes')
@section('page-title', 'Quizzes')
@section('breadcrumb', 'Quizzes')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Assessment Zone</div>
    <div class="hero-title">Quizzes</div>
    <div class="hero-sub">
      Quiz performance, upcoming tests aur subject-wise marks yahin track honge.
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Quiz Overview</div>
    <div class="list-stack">
      @forelse($quizzes as $quiz)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $quiz['title'] }}</div>
          <div class="list-item-meta">{{ $quiz['course'] }} • Marks: {{ $quiz['marks'] }}</div>
        </div>
        <span class="tag-badge {{ $quiz['status'] === 'Completed' ? 'tag-green' : 'tag-purple' }}">
          {{ $quiz['status'] }}
        </span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">Abhi koi quiz data nahi hai</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection