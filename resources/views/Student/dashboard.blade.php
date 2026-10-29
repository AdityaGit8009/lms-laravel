@extends('Student.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
  /* ── Greeting ──────────────────────────────── */
  .greet-box {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px 28px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    position: relative;
    overflow: hidden;
  }

  .greet-box::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(110, 231, 183, .07) 0%, transparent 70%);
    pointer-events: none;
  }

  .greet-box::after {
    content: '';
    position: absolute;
    bottom: -80px;
    right: 120px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(129, 140, 248, .06) 0%, transparent 70%);
    pointer-events: none;
  }

  .greet-tag {
    font-size: 10.5px;
    font-weight: 500;
    color: var(--accent);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 6px;
  }

  .greet-name {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.2;
  }

  .greet-sub {
    font-size: 13px;
    color: var(--text-2);
    line-height: 1.65;
    max-width: 420px;
  }

  .greet-sub strong {
    color: var(--text-1);
  }

  /* ── Stats Grid ────────────────────────────── */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 22px;
  }

  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px;
    transition: border-color .2s, transform .2s;
    cursor: default;
  }

  .stat-card:hover {
    border-color: rgba(255, 255, 255, .11);
    transform: translateY(-2px);
  }

  .stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
  }

  .ic-green {
    background: rgba(110, 231, 183, .12);
    color: var(--accent);
  }

  .ic-purple {
    background: rgba(129, 140, 248, .12);
    color: var(--accent-2);
  }

  .ic-pink {
    background: rgba(244, 114, 182, .12);
    color: var(--accent-3);
  }

  .ic-amber {
    background: rgba(251, 191, 36, .12);
    color: var(--accent-w);
  }

  .stat-lbl {
    font-size: 11px;
    color: var(--text-2);
    margin-bottom: 3px;
  }

  .stat-val {
    font-size: 26px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    margin-bottom: 4px;
    line-height: 1;
  }

  .stat-sub {
    font-size: 10.5px;
    color: var(--text-3);
  }

  .stat-sub span {
    color: var(--accent);
  }

  /* ── Streak ────────────────────────────────── */
  .streak-bar {
    background: linear-gradient(135deg, rgba(110, 231, 183, .07), rgba(129, 140, 248, .07));
    border: 1px solid rgba(110, 231, 183, .14);
    border-radius: var(--radius);
    padding: 14px 20px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .streak-num {
    font-size: 28px;
    font-weight: 700;
    font-family: 'JetBrains Mono', monospace;
    color: var(--accent);
    line-height: 1;
  }

  .streak-lbl {
    font-size: 10.5px;
    color: var(--text-2);
    margin-top: 2px;
  }

  .sday-wrap {
    display: flex;
    gap: 4px;
    margin-left: auto;
  }

  .sday {
    width: 26px;
    height: 26px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9.5px;
    font-weight: 600;
    background: var(--bg-panel);
    border: 1px solid var(--border);
    color: var(--text-3);
  }

  .sday.done {
    background: rgba(110, 231, 183, .13);
    border-color: var(--accent);
    color: var(--accent);
  }

  .sday.today {
    background: var(--accent);
    color: #0d0f14;
  }

  /* ── Two Col ───────────────────────────────── */
  .two-col {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 18px;
  }

  /* ── Course List ───────────────────────────── */
  .citem {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 13px 0;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: opacity .18s;
    text-decoration: none;
    color: inherit;
  }

  .citem:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .citem:first-child {
    padding-top: 0;
  }

  .citem:hover {
    opacity: .8;
  }

  .cthumb {
    width: 48px;
    height: 48px;
    border-radius: 9px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .cname {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .cmeta {
    font-size: 11px;
    color: var(--text-2);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
  }

  .cprog-lbl {
    font-size: 10px;
    color: var(--text-3);
    display: flex;
    justify-content: space-between;
    margin-bottom: 3px;
  }

  .cprog-lbl span {
    color: var(--text-2);
    font-weight: 500;
  }

  /* ── Deadline ──────────────────────────────── */
  .ditem {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 10px 12px;
    background: var(--bg-panel);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    margin-bottom: 8px;
  }

  .ditem:last-child {
    margin-bottom: 0;
  }

  .ddate {
    width: 38px;
    height: 42px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--bg-card);
    border-radius: 7px;
    border: 1px solid var(--border);
    flex-shrink: 0;
  }

  .dday {
    font-size: 14px;
    font-weight: 700;
    line-height: 1;
    font-family: 'JetBrains Mono', monospace;
  }

  .dmon {
    font-size: 9px;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
  }

  .dtask {
    font-size: 12.5px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .dcrs {
    font-size: 11px;
    color: var(--text-2);
  }

  /* ── Activity ──────────────────────────────── */
  .aitem {
    display: flex;
    gap: 11px;
    padding: 11px 0;
    border-bottom: 1px solid var(--border);
    font-size: 12.5px;
  }

  .aitem:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .aitem:first-child {
    padding-top: 0;
  }

  .adot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    margin-top: 5px;
    flex-shrink: 0;
  }

  .atxt {
    color: var(--text-1);
    line-height: 1.4;
    margin-bottom: 2px;
  }

  .atime {
    font-size: 10.5px;
    color: var(--text-3);
  }

  @media (max-width: 1100px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .two-col {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 600px) {
    .greet-box {
      flex-direction: column;
      align-items: flex-start;
    }

    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .sday-wrap {
      display: none;
    }
  }
</style>
@endpush

@section('content')

{{-- ── Greeting ─────────────────────────────── --}}
<div class="greet-box">
  <div style="position:relative;z-index:1;">
    <div class="greet-tag">
      @php
      $h = (int) now()->format('H');
      echo $h < 12 ? 'Subah Aapka Swagat Hai' : ($h < 17 ? 'Dopahar Aapka Swagat Hai' : 'Shaam Aapka Swagat Hai' );
        @endphp
        </div>
        <h1 class="greet-name">{{ $student->name }} 👋</h1>
        <p class="greet-sub">
          Aapke paas
          <strong>{{ $stats['assignments'] }} assignments</strong> pending hain.
          @if($stats['enrolled'] > 0)
          Aap {{ $stats['enrolled'] }} course(s) mein enrolled hain — aaj kuch naya seekhein!
          @else
          Koi course enroll karein aur apni learning journey shuru karein!
          @endif
        </p>
    </div>
    <a href="{{ route('student.courses') }}" class="btn-primary" style="flex-shrink:0;position:relative;z-index:1;">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
        <polygon points="5 3 19 12 5 21 5 3" />
      </svg>
      Seekhna Jaari Rakhein
    </a>
  </div>

  {{-- ── Stats ────────────────────────────────── --}}
  <div class="stats-grid">

    <div class="stat-card">
      <div class="stat-icon ic-green">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div class="stat-lbl">Enrolled Courses</div>
      <div class="stat-val">{{ $stats['enrolled'] }}</div>
      <div class="stat-sub">Kul enrolled courses</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon ic-purple">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div class="stat-lbl">Completed</div>
      <div class="stat-val">{{ $stats['completed'] }}</div>
      <div class="stat-sub">
        <span>{{ $stats['enrolled'] > 0 ? round(($stats['completed'] / $stats['enrolled']) * 100) : 0 }}%</span>
        completion rate
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon ic-pink">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div>
      <div class="stat-lbl">Pending Assignments</div>
      <div class="stat-val">{{ $stats['assignments'] }}</div>
      <div class="stat-sub">Is hafte due hain</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon ic-amber">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="6" />
          <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
        </svg>
      </div>
      <div class="stat-lbl">Certificates</div>
      <div class="stat-val">{{ $stats['certificates'] }}</div>
      <div class="stat-sub">Certificates earn kiye</div>
    </div>
  </div>

  {{-- ── Streak ───────────────────────────────── --}}
  <div class="streak-bar">
    <div style="font-size:32px;line-height:1;">🔥</div>
    <div>
      <div class="streak-num">{{ $streak }}</div>
      <div class="streak-lbl">day learning streak</div>
    </div>
    <div style="color:var(--text-2);font-size:12px;">
      Harek roz seekhte rehne par badge milega!
    </div>
    <div class="sday-wrap">
      @php $today = now()->dayOfWeek; $days = ['S','M','T','W','T','F','S']; @endphp
      @foreach($days as $i => $d)
      <div class="sday {{ $i < $today ? 'done' : ($i == $today ? 'today' : '') }}">{{ $d }}</div>
      @endforeach
    </div>
  </div>

  {{-- ── Two Column ───────────────────────────── --}}
  <div class="two-col">

    {{-- My Courses --}}
    <div class="s-card">
      <div class="s-card-title">
        Mere Courses
        <a href="{{ route('student.courses') }}">Sab dekho →</a>
      </div>

      @forelse($enrolledCourses as $course)
      <a href="{{ route('student.courses') }}" class="citem">
        <div class="cthumb" style="background:{{ $course['bg'] }};color:{{ $course['color'] }};">
          {{ $course['emoji'] }}
        </div>
        <div style="flex:1;min-width:0;">
          <div class="cname">{{ $course['name'] }}</div>
          <div class="cmeta">
            <span>{{ $course['instructor'] }}</span>
            <span style="color:var(--text-3)">•</span>
            <span>{{ $course['lessons'] }} lessons</span>
          </div>
          <div class="cprog-lbl">
            <span>Progress</span>
            <span>{{ $course['progress'] }}%</span>
          </div>
          <div class="s-progress">
            <div class="s-progress-fill" style="width:{{ $course['progress'] }}%;background:{{ $course['color'] }};"></div>
          </div>
        </div>
      </a>
      @empty
      <div style="text-align:center;padding:40px 0;color:var(--text-3);">
        <div style="font-size:32px;margin-bottom:10px;">📚</div>
        <div style="font-size:13px;">Abhi koi course enroll nahi kiya</div>
        <a href="{{ route('student.courses') }}" class="btn-primary" style="margin-top:14px;display:inline-flex;">Course Enroll Karein</a>
      </div>
      @endforelse
    </div>

    {{-- Right column --}}
    <div>

      {{-- Upcoming Deadlines --}}
      <div class="s-card" style="margin-bottom:16px;">
        <div class="s-card-title">
          Upcoming Deadlines
          <a href="{{ route('student.assignments') }}">Sab dekho →</a>
        </div>

        @forelse($upcomingDeadlines as $dl)
        <div class="ditem">
          <div class="ddate">
            <div class="dday">{{ $dl['day'] }}</div>
            <div class="dmon">{{ $dl['month'] }}</div>
          </div>
          <div style="flex:1;min-width:0;">
            <div class="dtask">{{ $dl['task'] }}</div>
            <div class="dcrs">{{ $dl['course'] }}</div>
          </div>
          @if($dl['urgency'] === 'pink')
          <span class="s-badge badge-pink">Urgent</span>
          @elseif($dl['urgency'] === 'amber')
          <span class="s-badge badge-amber">Jaldi</span>
          @else
          <span class="s-badge badge-purple">Aane wala</span>
          @endif
        </div>
        @empty
        <div style="text-align:center;padding:20px 0;color:var(--text-3);font-size:12px;">
          🎉 Koi pending deadline nahi hai!
        </div>
        @endforelse
      </div>

      {{-- Recent Activity --}}
      <div class="s-card">
        <div class="s-card-title">Haaliya Gatividi</div>

        @forelse($recentActivities as $act)
        <div class="aitem">
          <div class="adot" style="background:{{ $act['color'] }};"></div>
          <div>
            <div class="atxt">{{ $act['title'] }}</div>
            <div class="atime">{{ $act['time'] }}</div>
          </div>
        </div>
        @empty
        <div style="text-align:center;padding:20px 0;color:var(--text-3);font-size:12px;">
          Abhi koi activity nahi hai.
        </div>
        @endforelse
      </div>

    </div>
  </div>

  @endsection