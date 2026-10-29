<aside class="s-sidebar" id="studentSidebar">

  {{-- Logo --}}
  <div style="padding:18px 16px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px;">
    <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent-2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#0d0f14;flex-shrink:0;">E</div>
    <span style="font-size:16px;font-weight:600;background:linear-gradient(90deg,var(--accent),var(--accent-2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">EduPath LMS</span>
  </div>

  {{-- Nav --}}
  <nav style="flex:1; padding:12px 10px; overflow-y:auto;">

    {{-- Main --}}
    <div style="font-size:9.5px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text-3);padding:10px 8px 5px;">Main</div>

    <a href="{{ route('student.dashboard') }}" class="s-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      Dashboard
    </a>

    <a href="{{ route('student.courses') }}" class="s-nav-item {{ request()->routeIs('student.courses*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
      </svg>
      Mere Courses
      <span class="s-nav-badge">{{ $sidebarEnrolled ?? 0 }}</span>
    </a>

    <a href="{{ route('student.assignments') }}" class="s-nav-item {{ request()->routeIs('student.assignments*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
      </svg>
      Assignments
      @if(($sidebarPending ?? 0) > 0)
      <span class="s-nav-badge red">{{ $sidebarPending }}</span>
      @endif
    </a>

    <a href="{{ route('student.quiz') }}" class="s-nav-item {{ request()->routeIs('student.quiz*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
      </svg>
      Quizzes
    </a>

    {{-- Learning --}}
    <div style="font-size:9.5px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text-3);padding:14px 8px 5px;">Seekhna</div>

    <a href="{{ route('student.schedule') }}" class="s-nav-item {{ request()->routeIs('student.schedule*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
        <line x1="16" y1="2" x2="16" y2="6" />
        <line x1="8" y1="2" x2="8" y2="6" />
        <line x1="3" y1="10" x2="21" y2="10" />
      </svg>
      Schedule
    </a>

    <a href="{{ route('student.results') }}" class="s-nav-item {{ request()->routeIs('student.results*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
      </svg>
      Results / Marks
    </a>

    <a href="{{ route('student.certificates') }}" class="s-nav-item {{ request()->routeIs('student.certificates*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <circle cx="12" cy="8" r="6" />
        <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11" />
      </svg>
      Certificates
    </a>

    <a href="{{ route('student.announcements') }}" class="s-nav-item {{ request()->routeIs('student.announcements*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
      </svg>
      Announcements
    </a>

    {{-- Account --}}
    <div style="font-size:9.5px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--text-3);padding:14px 8px 5px;">Account</div>

    <a href="{{ route('student.profile') }}" class="s-nav-item {{ request()->routeIs('student.profile*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
      Profile
    </a>

    <a href="{{ route('student.change_password') }}" class="s-nav-item {{ request()->routeIs('student.change_password*') ? 'active' : '' }}">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
        <path d="M7 11V7a5 5 0 0110 0v4" />
      </svg>
      Password Badlein
    </a>

  </nav>

  {{-- User Info --}}
  <div style="padding:14px 14px; border-top:1px solid var(--border); display:flex; align-items:center; gap:10px;">
    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent-2),var(--accent-3));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:white;flex-shrink:0;">
      {{ strtoupper(substr($student->name ?? 'S', 0, 2)) }}
    </div>

    <div style="flex:1;min-width:0;">
      <div style="font-size:12.5px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        {{ $student->name ?? 'Student' }}
      </div>
      <div style="font-size:10.5px;color:var(--text-2);">Student</div>
    </div>


    <a href="#" id="logoutBtn" class="dropdown-item">
      <i class="bi bi-box-arrow-right me-2 text-light"></i> Logout
    </a>

  </div>

</aside>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function() {
    $('#logoutBtn').on('click', function(e) {
      e.preventDefault();

      if (!confirm('Are you sure you want to logout?')) {
        return;
      }

      $.ajax({
        url: '/api/auth/logout',
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Accept': 'application/json'
        },
        success: function(response) {
          window.location.href = response.redirect || '/login';
        },
        error: function(xhr) {
          let msg = 'Logout failed.';

          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }

          alert(msg);
        }
      });
    });
  });
</script>

<style>
  .s-nav-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 11px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 430;
    color: var(--text-2);
    text-decoration: none;
    transition: all .18s;
    margin-bottom: 2px;
    position: relative;
  }

  .s-nav-item:hover {
    background: var(--bg-hover);
    color: var(--text-1);
  }

  .s-nav-item.active {
    background: rgba(110, 231, 183, .09);
    color: var(--accent);
    font-weight: 500;
  }

  .s-nav-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: var(--accent);
    border-radius: 0 3px 3px 0;
  }

  .s-nav-badge {
    margin-left: auto;
    background: var(--accent);
    color: #0d0f14;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 99px;
  }

  .s-nav-badge.red {
    background: var(--accent-3);
    color: white;
  }
</style>