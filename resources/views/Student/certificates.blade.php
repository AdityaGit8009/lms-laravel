@extends('Student.layouts.app')

@section('title', 'Certificates')
@section('page-title', 'Certificates')
@section('breadcrumb', 'Certificates')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Achievements</div>
    <div class="hero-title">Certificates</div>
    <div class="hero-sub">
      Earned certificates aur completion recognitions ko yahan se access karein.
    </div>
  </div>

  <div class="cards-grid-2">
    @forelse($certificates as $item)
    <div class="soft-card">
      <div class="soft-card-title">
        <span>{{ $item['title'] }}</span>
        <span class="tag-badge tag-green">{{ $item['status'] }}</span>
      </div>
      <div class="list-item-meta">Issued On: {{ $item['issued_on'] }}</div>
      <div class="page-actions" style="margin-top:14px;">
        <a href="#" class="btn-soft">View</a>
        <a href="#" class="btn-soft">Download</a>
      </div>
    </div>
    @empty
    <div class="soft-card">
      <div class="soft-card-title">No certificates yet</div>
    </div>
    @endforelse
  </div>
</div>
@endsection