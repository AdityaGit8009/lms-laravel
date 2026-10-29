@extends('Student.layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('breadcrumb', 'Announcements')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Notice Board</div>
    <div class="hero-title">Announcements</div>
    <div class="hero-sub">
      Latest updates, mentor notices aur important communication yahin milega.
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Latest Announcements</div>
    <div class="list-stack">
      @forelse($announcements as $item)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $item['title'] }}</div>
          <div class="list-item-meta">Published on: {{ $item['date'] }}</div>
        </div>
        <span class="tag-badge tag-purple">New</span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">No announcements available</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection