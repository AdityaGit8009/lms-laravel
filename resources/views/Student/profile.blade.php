@extends('Student.layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('breadcrumb', 'Profile')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Identity</div>
    <div class="hero-title">Student Profile</div>
    <div class="hero-sub">
      Aapki personal, academic aur program details yahan available hain.
    </div>
  </div>

  <div class="profile-grid">
    <div class="profile-panel">
      <div class="avatar-lg">{{ strtoupper(substr($student->name ?? 'S', 0, 2)) }}</div>
      <div style="font-size:18px;font-weight:700;margin-bottom:6px;">{{ $student->name }}</div>
      <div style="font-size:12px;color:var(--text-2);margin-bottom:14px;">Student</div>
      <div class="list-item-meta">
        {{ $student->email }}<br>
        {{ $student->mobile }}
      </div>
    </div>

    <div class="profile-panel">
      <div class="soft-card-title">Profile Details</div>
      <div class="kv-grid">
        <div class="kv-box">
          <div class="kv-label">College</div>
          <div class="kv-value">{{ $student->college }}</div>
        </div>
        <div class="kv-box">
          <div class="kv-label">Registration No.</div>
          <div class="kv-value">{{ $student->regno }}</div>
        </div>
        <div class="kv-box">
          <div class="kv-label">Course</div>
          <div class="kv-value">{{ $student->course_name }}</div>
        </div>
        <div class="kv-box">
          <div class="kv-label">Year</div>
          <div class="kv-value">{{ $student->year_name }}</div>
        </div>
        <div class="kv-box">
          <div class="kv-label">Program</div>
          <div class="kv-value">{{ $student->program_name }}</div>
        </div>
        <div class="kv-box">
          <div class="kv-label">Location</div>
          <div class="kv-value">{{ $student->location_name }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection