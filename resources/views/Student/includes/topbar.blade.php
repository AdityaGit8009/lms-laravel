<header class="s-topbar">

  {{-- Mobile menu button --}}
  <button onclick="document.getElementById('studentSidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('show');"
    style="display:none;background:transparent;border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px;cursor:pointer;color:var(--text-2);" id="menuToggle">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M4 6h16M4 12h16M4 18h16" />
    </svg>
  </button>

  {{-- Page Title --}}
  <div style="font-size:15px;font-weight:600;flex:1;">@yield('page-title', 'Dashboard')</div>

  {{-- Breadcrumb --}}
  <div style="font-size:12px;color:var(--text-3);display:flex;align-items:center;gap:6px;margin-right:8px;">
    <span>Student</span>
    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M9 18l6-6-6-6" />
    </svg>
    <span style="color:var(--text-2);">@yield('breadcrumb', 'Dashboard')</span>
  </div>

  {{-- Search --}}
  <div style="display:flex;align-items:center;gap:7px;background:var(--bg-panel);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 13px;width:200px;">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-3);flex-shrink:0;">
      <circle cx="11" cy="11" r="8" />
      <path d="M21 21l-4.35-4.35" />
    </svg>
    <input type="text" placeholder="Courses khojein..."
      style="background:transparent;border:none;outline:none;font-family:'Sora',sans-serif;font-size:12.5px;color:var(--text-1);width:100%;">
  </div>

  {{-- Notification Bell --}}
  <div style="position:relative;">
    <button style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--bg-panel);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-2);">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
    </button>
    @if(($unreadNotifications ?? 0) > 0)
    <span style="position:absolute;top:6px;right:6px;width:7px;height:7px;background:var(--accent-3);border-radius:50%;border:2px solid var(--bg-card);"></span>
    @endif
  </div>

  {{-- Student Avatar --}}
  <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--accent-2),var(--accent-3));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:white;cursor:pointer;flex-shrink:0;">
    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
  </div>

</header>

<style>
  @media (max-width: 768px) {
    #menuToggle {
      display: flex !important;
    }
  }
</style>

{{-- Mobile overlay --}}
<div id="sidebarOverlay"
  onclick="document.getElementById('studentSidebar').classList.remove('open');this.classList.remove('show');"
  style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199;">
</div>
<style>
  #sidebarOverlay.show {
    display: block !important;
  }
</style>