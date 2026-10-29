@extends('Admin.layouts.app')

@section('title','Settings')

@section('content')

@php
$navItems = [
['bi-person-fill','Profile',true],
['bi-building','Organization',false],
['bi-shield-lock-fill','Security',false],
['bi-bell-fill','Notifications',false],
['bi-credit-card-fill','Billing',false],
['bi-palette-fill','Appearance',false],
['bi-plug-fill','Integrations',false],
['bi-people-fill','Team & Roles',false],
['bi-envelope-fill','Email Templates',false],
['bi-gear-wide-connected','Advanced',false],
];
@endphp

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span><span>Settings</span>
        </div>
        <h1>Settings</h1>
        <p>Manage your account, platform preferences and integrations.</p>
      </div>

      <div class="page-hdr-right">
        <button class="btn btn-ghost btn-sm">
          <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Theme
        </button>

        <button class="btn btn-primary btn-sm">
          <i class="bi bi-check2 me-1"></i>Save Changes
        </button>
      </div>
    </div>

    <div class="settings-layout">

      <!-- NAV -->
      <div class="settings-nav">
        @foreach($navItems as $i=>$n)
        <div class="settings-nav-item {{ $n[2] ? 'active' : '' }}"
          onclick="activateTab(this,'sec{{ $i }}')">
          <i class="bi {{ $n[0] }}"></i> {{ $n[1] }}
        </div>
        @endforeach
      </div>

      <!-- CONTENT -->
      <div>

        <!-- PROFILE -->
        <div class="settings-section" id="sec0">
          <div class="settings-section-title">Profile Information</div>

          <div class="row g-3">
            <div class="col-md-6">
              <input type="text" class="form-control" value="Arjun">
            </div>

            <div class="col-md-6">
              <input type="text" class="form-control" value="Mehta">
            </div>
          </div>
        </div>

        <!-- ORGANIZATION -->
        <div class="settings-section" id="sec1" style="display:none">
          <input type="text" class="form-control" value="EduCore Academy">
        </div>

        <!-- SECURITY -->
        <div class="settings-section" id="sec2" style="display:none">
          <button class="btn btn-danger btn-sm">Revoke All</button>
        </div>

        <!-- NOTIFICATIONS -->
        <div class="settings-section" id="sec3" style="display:none">

          @php
          $notifs = [
          ['New Enrollment','When student enrolls',true,true],
          ['Payment Received','Payment processed',true,true],
          ];
          @endphp

          @foreach($notifs as $n)
          <div>{{ $n[0] }}</div>
          @endforeach

        </div>

        <!-- BILLING -->
        <div class="settings-section" id="sec4" style="display:none">

          @php
          $plans = [
          ['Starter','₹999/mo',false],
          ['Professional','₹2499/mo',true],
          ];
          @endphp

          @foreach($plans as $p)
          <div>{{ $p[0] }} - {{ $p[1] }}</div>
          @endforeach

        </div>

        <!-- TEAM -->
        <div class="settings-section" id="sec7" style="display:none">

          @php
          $team = [
          [68,'Arjun Mehta','Super Admin','Active'],
          [25,'Neha Joshi','Manager','Active'],
          ];
          @endphp

          <table class="table">
            @foreach($team as $m)
            <tr>
              <td>{{ $m[1] }}</td>
              <td>{{ $m[2] }}</td>
              <td>{{ $m[3] }}</td>
            </tr>
            @endforeach
          </table>

        </div>

      </div>
    </div>

  </div>
</main>

@endsection

@push('scripts')
<script>
  function activateTab(el, sec) {
    document.querySelectorAll('.settings-section').forEach(s => s.style.display = 'none');
    document.getElementById(sec).style.display = 'block';
  }
</script>
@endpush