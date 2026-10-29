@php
$notifs = [
['class' => 'ic-accent', 'icon' => 'bi-person-plus-fill', 'text' => '<strong>Priya Sharma</strong> enrolled in React Masterclass', 'time' => '2 min ago'],
['class' => 'ic-amber', 'icon' => 'bi-star-fill', 'text' => '<strong>Course Review</strong> submitted for UX Design Basics', 'time' => '18 min ago'],
['class' => 'ic-rose', 'icon' => 'bi-exclamation-triangle-fill', 'text' => '<strong>Ravi Kumar</strong> flagged an issue in Module 3', 'time' => '1 hr ago'],
['class' => 'ic-sky', 'icon' => 'bi-credit-card-fill', 'text' => 'New payment of <strong>₹4,999</strong> received', 'time' => '3 hrs ago'],
['class' => 'ic-purple', 'icon' => 'bi-trophy-fill', 'text' => '<strong>Ankit Verma</strong> completed Data Science 🎉', 'time' => 'Yesterday'],
['class' => 'ic-green', 'icon' => 'bi-patch-check-fill', 'text' => 'New instructor <strong>Deepa Menon</strong> joined', 'time' => 'Yesterday'],
];
@endphp

<header class="topbar" id="topbar">
  <button class="icon-btn mob-show" onclick="toggleMob()">
    <i class="bi bi-list"></i>
  </button>

  <button class="icon-btn" onclick="toggleCollapse()" title="Toggle Sidebar">
    <i class="bi bi-layout-sidebar"></i>
  </button>

  <div class="search-box">
    <i class="bi bi-search si"></i>
    <input type="text" placeholder="Search students, courses, reports…" />
  </div>

  <div class="topbar-end">
    <div class="icon-btn" onclick="toggleNotif()" title="Notifications">
      <i class="bi bi-bell-fill"></i>
      <span class="ndot"></span>
    </div>

    <div class="icon-btn" title="Messages">
      <i class="bi bi-chat-dots-fill"></i>
    </div>

    <div class="icon-btn" onclick="toggleFS()" title="Fullscreen">
      <i class="bi bi-arrows-fullscreen fs-icon"></i>
    </div>

    <div class="icon-btn" onclick="quickMode()" title="Toggle Theme" id="topModeBtn">
      <i class="bi bi-moon-stars-fill"></i>
    </div>

    <div class="icon-btn" onclick="openSettings()" title="Customizer" style="color:var(--accent);border-color:var(--accent)">
      <i class="bi bi-sliders2"></i>
    </div>

    <div class="profile-hover-box">
      <img
        src="{{ !empty(auth()->user()->image) && file_exists(public_path('uploads/' . auth()->user()->image))
      ? asset('uploads/' . auth()->user()->image)
      : asset('uploads/office-man.webp') }}"
        class="topbar-avatar ms-1"
        alt="Admin"
        id="profileAvatar" style="width:40px;height:40px;object-fit:center;border:4px solid rgb(0, 4, 255);" />
      <div class="logout-dropdown" id="logoutDropdown">
        <div>
          <a href="{{ route('admin.profile') }}" class="dropdown-item">
            <i class="bi bi-person-circle me-2"></i> Profile
          </a>
          <a href="#" id="logoutBtn1" class="dropdown-item">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
          <a href="{{ route('admin.change.password') }}" class="dropdown-item">
            <i class="bi bi-key-fill me-2"></i> Change Password
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- <button type="button" id="logoutBtn1" class="logout-hover-btn">
  <i class="bi bi-box-arrow-right"></i> Logout
</button> -->

<!-- NOTIFICATION PANEL -->
<div class="notif-panel" id="notifPanel">
  <div class="notif-hd">
    <span>Notifications</span>
    <i class="bi bi-x-lg" style="cursor:pointer;font-size:.85rem;" onclick="toggleNotif()"></i>
  </div>

  @foreach($notifs as $n)
  <div class="notif-item">
    <div class="icon-circle ic-40 {{ $n['class'] }}">
      <i class="bi {{ $n['icon'] }}"></i>
    </div>
    <div>
      <div class="notif-txt">{!! $n['text'] !!}</div>
      <div class="notif-time">{{ $n['time'] }}</div>
    </div>
  </div>
  @endforeach

  <div style="padding:12px 16px;border-top:1px solid var(--border);flex-shrink:0;">
    <button class="btn btn-ghost btn-sm w-100" onclick="showToast('All marked as read','success');toggleNotif()">
      <i class="bi bi-bell-slash me-1"></i>Mark all read
    </button>
  </div>
</div>

<!-- SETTINGS FAB + OVERLAY + PANEL -->
<div class="settings-fab" onclick="openSettings()">
  <i class="bi bi-sliders2"></i>
</div>

<div class="settings-overlay" id="settingsOverlay" onclick="closeSettings()"></div>

<div class="settings-panel" id="settingsPanel">
  <div class="sp-hd">
    <div class="sp-hd-title">
      <i class="bi bi-sliders2"></i> Customizer
    </div>
    <div class="icon-btn" onclick="closeSettings()" style="width:30px;height:30px;font-size:.85rem;">
      <i class="bi bi-x-lg"></i>
    </div>
  </div>

  <div class="sp-body">
    <div class="sp-sec">
      <div class="sp-sec-lbl">🌙 Mode</div>
      <div class="sp-mode-row">
        <button class="sp-mode-btn" data-mode-btn="dark" onclick="setMode('dark')">
          <i class="bi bi-moon-stars-fill"></i> Dark
        </button>
        <button class="sp-mode-btn" data-mode-btn="light" onclick="setMode('light')">
          <i class="bi bi-sun-fill"></i> Light
        </button>
      </div>
    </div>

    <div class="sp-sec">
      <div class="sp-sec-lbl">🎨 Accent Color</div>
      <div class="swatch-row">
        <div class="swatch" data-swatch="teal" style="background:#00d4aa" onclick="setAccent('teal',this)" title="Teal"></div>
        <div class="swatch" data-swatch="indigo" style="background:#6366f1" onclick="setAccent('indigo',this)" title="Indigo"></div>
        <div class="swatch" data-swatch="rose" style="background:#f43f5e" onclick="setAccent('rose',this)" title="Rose"></div>
        <div class="swatch" data-swatch="amber" style="background:#f59e0b" onclick="setAccent('amber',this)" title="Amber"></div>
        <div class="swatch" data-swatch="violet" style="background:#8b5cf6" onclick="setAccent('violet',this)" title="Violet"></div>
        <div class="swatch" data-swatch="sky" style="background:#0ea5e9" onclick="setAccent('sky',this)" title="Sky"></div>
        <div class="swatch" data-swatch="lime" style="background:#84cc16" onclick="setAccent('lime',this)" title="Lime"></div>
        <div class="swatch" data-swatch="pink" style="background:#ec4899" onclick="setAccent('pink',this)" title="Pink"></div>
      </div>
    </div>

    <div class="sp-sec">
      <div class="sp-sec-lbl">⬛ Border Radius</div>
      <div class="sp-opt-row c3">
        <button class="sp-opt-btn" data-radius-btn="sharp" onclick="setRadius('sharp',this)">Sharp</button>
        <button class="sp-opt-btn" data-radius-btn="medium" onclick="setRadius('medium',this)">Medium</button>
        <button class="sp-opt-btn" data-radius-btn="round" onclick="setRadius('round',this)">Round</button>
      </div>
    </div>

    <div class="sp-sec">
      <div class="sp-sec-lbl">📐 Sidebar</div>
      <div class="sp-opt-row c2">
        <button class="sp-opt-btn" data-sidebar-btn="expanded" onclick="setSidebar('expanded',this)">⬜ Expanded</button>
        <button class="sp-opt-btn" data-sidebar-btn="collapsed" onclick="setSidebar('collapsed',this)">◻ Mini</button>
      </div>
    </div>

    <div class="sp-sec">
      <div class="sp-sec-lbl">✨ Extras</div>
      <div class="toggle-row">
        <span class="toggle-lbl">
          <i class="bi bi-stars me-2" style="color:var(--accent)"></i>Card Shadows
        </span>
        <div class="toggle-sw on" id="shadowToggle" onclick="toggleShadows(this)"></div>
      </div>

      <div class="toggle-row">
        <span class="toggle-lbl">
          <i class="bi bi-play-circle me-2" style="color:var(--accent)"></i>Animations
        </span>
        <div class="toggle-sw on" id="animToggle" onclick="this.classList.toggle('on')"></div>
      </div>
    </div>

    <div class="sp-sec">
      <div class="sp-sec-lbl">🖥 View</div>
      <button class="sp-action-btn" onclick="toggleFS()">
        <i class="bi bi-arrows-fullscreen fs-icon"></i>
        <span class="fs-label">Enter Fullscreen</span>
      </button>
    </div>

    <div class="sp-sec">
      <button class="sp-action-btn sp-reset-btn" onclick="resetAll()">
        <i class="bi bi-arrow-counterclockwise"></i> Reset to Default
      </button>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function() {
    $('#logoutBtn1').on('click', function(e) {
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