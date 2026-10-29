@extends('Student.layouts.app')

@section('title', 'Password Badlein')
@section('page-title', 'Password Badlein')
@section('breadcrumb', 'Password Badlein')

@push('styles')
@include('Student.partials.page_theme_styles')
@endpush

@section('content')
<div class="page-shell">
  <div class="hero-card">
    <div class="hero-kicker">Security</div>
    <div class="hero-title">Password Badlein</div>
    <div class="hero-sub">
      Account security ko strong banaye rakhne ke liye apna password regularly update karein.
    </div>
  </div>

  <div class="soft-card">
    <div class="soft-card-title">Update Password</div>

    <form method="POST" action="#" class="form-grid">
      @csrf

      <div class="field-group">
        <label>Current Password</label>
        <input type="password" name="current_password" placeholder="Current password enter karein">
      </div>

      <div class="field-group">
        <label>New Password</label>
        <input type="password" name="new_password" placeholder="New password enter karein">
      </div>

      <div class="field-group">
        <label>Confirm Password</label>
        <input type="password" name="new_password_confirmation" placeholder="Password dobara enter karein">
      </div>

      <div class="page-actions">
        <button type="submit" class="btn-soft">Update Password</button>
      </div>
    </form>
  </div>
</div>
@endsection