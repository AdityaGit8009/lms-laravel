@extends('Student.layouts.app')

@section('title', 'Assignments')
@section('page-title', 'Assignments')
@section('breadcrumb', 'Assignments')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Work Queue</div>
    <div class="hero-title">Assignments</div>
    <div class="hero-sub">
      Yahan se aap upcoming submissions, completed work aur due dates track kar sakte hain.
    </div>
  </div>

  <div class="cards-grid-2">
    <div class="soft-card">
      <div class="soft-card-title">Pending Work</div>
      <div class="list-stack">
        @foreach(collect($assignments ?? [])->where('status', 'Pending') as $item)
        <div class="list-item">
          <div class="list-item-left">
            <div class="list-item-title">{{ $item['title'] }}</div>
            <div class="list-item-meta">{{ $item['course'] }} • Due: {{ $item['due_date'] }}</div>
          </div>
          <span class="tag-badge tag-pink">Pending</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="soft-card">
      <div class="soft-card-title">Submitted Work</div>
      <div class="list-stack">
        @foreach(collect($assignments ?? [])->where('status', 'Submitted') as $item)
        <div class="list-item">
          <div class="list-item-left">
            <div class="list-item-title">{{ $item['title'] }}</div>
            <div class="list-item-meta">{{ $item['course'] }} • Submitted</div>
          </div>
          <span class="tag-badge tag-green">Submitted</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">All Assignments</div>
    <div class="list-stack">
      @forelse($assignments as $item)
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">{{ $item['title'] }}</div>
          <div class="list-item-meta">{{ $item['course'] }} • Due Date: {{ $item['due_date'] }}</div>
        </div>
        <span class="tag-badge {{ $item['status'] === 'Submitted' ? 'tag-green' : 'tag-pink' }}">
          {{ $item['status'] }}
        </span>
      </div>
      @empty
      <div class="list-item">
        <div class="list-item-left">
          <div class="list-item-title">No assignments found</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection