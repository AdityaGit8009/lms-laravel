@extends('Student.layouts.app')

@section('title', 'Mere Courses')
@section('page-title', 'Mere Courses')
@section('breadcrumb', 'Mere Courses')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Learning Hub</div>
    <div class="hero-title">Mere Courses</div>
    <div class="hero-sub">
      Yahan aapke enrolled courses, progress aur mentor details ek jagah mil jayengi.
    </div>
  </div>

  <div class="stat-strip">
    <div class="mini-stat">
      <div class="mini-stat-label">Total Courses</div>
      <div class="mini-stat-value">{{ count($courses ?? []) }}</div>
      <div class="mini-stat-sub">Active learning pipeline</div>
    </div>
    <div class="mini-stat">
      <div class="mini-stat-label">Ongoing</div>
      <div class="mini-stat-value">{{ collect($courses ?? [])->where('status','Ongoing')->count() }}</div>
      <div class="mini-stat-sub">Currently in progress</div>
    </div>
    <div class="mini-stat">
      <div class="mini-stat-label">Avg Progress</div>
      <div class="mini-stat-value">
        {{ count($courses ?? []) ? round(collect($courses)->avg('progress')) : 0 }}%
      </div>
      <div class="mini-stat-sub">Performance snapshot</div>
    </div>
    <div class="mini-stat">
      <div class="mini-stat-label">Mentors</div>
      <div class="mini-stat-value">{{ collect($courses ?? [])->pluck('instructor')->unique()->count() }}</div>
      <div class="mini-stat-sub">Guiding your journey</div>
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Course Portfolio</div>

    <div class="list-stack">
      @forelse($courses as $course)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $course['name'] }}</div>
          <div class="list-item-meta">
            Mentor: {{ $course['instructor'] }} • Duration: {{ $course['duration'] }} • Status: {{ $course['status'] }}
          </div>
          <div class="progress-shell">
            <div class="progress-bar" style="width: {{ $course['progress'] }}%;"></div>
          </div>
        </div>

        <span class="tag-badge {{ $course['progress'] >= 80 ? 'tag-green' : ($course['progress'] >= 50 ? 'tag-purple' : 'tag-amber') }}">
          {{ $course['progress'] }}%
        </span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">Abhi koi course available nahi hai</div>
          <div class="list-item-meta">Jaise hi enrollment hoga, yahan courses dikhne lagenge.</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection