@extends('Student.layouts.app')

@section('title', 'Schedule')
@section('page-title', 'Schedule')
@section('breadcrumb', 'Schedule')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Time Planner</div>
    <div class="hero-title">Class Schedule</div>
    <div class="hero-sub">
      Weekly sessions, mentor timings aur study slots ko yahan se manage kijiye.
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Weekly Schedule</div>
    <div class="list-stack">
      @forelse($schedule as $item)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $item['subject'] }}</div>
          <div class="list-item-meta">{{ $item['day'] }} • {{ $item['time'] }}</div>
        </div>
        <span class="tag-badge tag-purple">{{ $item['day'] }}</span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">No schedule available</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection