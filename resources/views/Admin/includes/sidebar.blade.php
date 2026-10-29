@php
$nav = [
'MAIN' => [
['route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Dashboard'],

[
'label' => 'Master Entry',
'icon' => 'bi-stack',
'submenu' => [
['route' => 'admin.roles', 'icon' => 'bi-person-badge-fill', 'label' => 'Roles'],
['route' => 'admin.sessions', 'icon' => 'bi-calendar-event', 'label' => 'Session'],
['route' => 'admin.programmes', 'icon' => 'bi-journal-text', 'label' => 'Programme'],
['route' => 'admin.technologies', 'icon' => 'bi-code-slash', 'label' => 'Technology'],
['route' => 'admin.batches', 'icon' => 'bi-box-seam', 'label' => 'Batch'],

['route' => 'admin.subjects', 'icon' => 'bi-book-half', 'label' => 'Subjects'],
['route' => 'admin.centers', 'icon' => 'bi-building', 'label' => 'Centers'],
['route' => 'admin.courses', 'icon' => 'bi-book', 'label' => 'Courses'],
['route' => 'admin.branches', 'icon' => 'bi-diagram-3-fill', 'label' => 'Branches']
]
],
[
'label' => 'Manage Users',
'icon' => 'bi-people-fill',
'submenu' => [
['route' => 'admin.students', 'icon' => 'bi-person-badge-fill', 'label' => 'Students'],
['route' => 'admin.employees', 'icon' => 'bi-person-badge-fill', 'label' => 'Employees']
]
],

['route' => 'admin.viewstudents', 'icon' => 'bi-person-badge-fill', 'label' => 'View Students'],


['route' => 'admin.addattandence', 'icon' => 'bi-person-badge-fill', 'label' => 'Add Attandence'],


['route' => 'admin.viewattandence', 'icon' => 'bi bi-calendar3', 'label' => 'View Attandence'],

[
'label' => 'Content Repository',
'icon' => 'bi bi-cloud-upload-fill',
'submenu' => [
['route' => 'upload-content.index', 'icon' => 'bi bi-upload', 'label' => 'Upload Content'],
['route' => 'admin.content-bundles.index', 'icon' => 'bi bi-eye', 'label' => 'Show Content'],
['route' => 'admin.subject-mocktest.index', 'icon' => 'bi bi-file-text', 'label' => 'Subject Mock Test'],

]
],
[
'label' => 'Manage Notification',
'icon' => 'bi bi-bell-fill',
'submenu' => [
['route' => 'admin.notification-heads.index', 'icon' => 'bi bi-upload', 'label' => 'Notification Head'],
['route' => 'admin.notifications.index', 'icon' => 'bi bi-plus', 'label' => 'Add Notification'],
['route' => 'admin.popup-notifications.index', 'icon' => 'bi bi-file-text', 'label' => 'Pop up Notification'],

]
],

[
'label' => 'Manage Feedbacks',
'icon' => 'bi bi-clipboard-data-fill',
'submenu' => [
['route' => 'admin.feedback.create', 'icon' => 'bi bi-plus-square-fill', 'label' => 'Create Form'],
['route' => 'admin.feedback.manage', 'icon' => 'bi bi-card-list', 'label' => 'View Forms'],
['route' => 'admin.feedback.overall.analytics', 'icon' => 'bi bi-bar-chart-fill', 'label' => 'View All Feedback'],
],
],

[
'label' => 'Login Status',
'icon' => 'bi bi-clipboard-data-fill',
'submenu' => [
['route' => 'admin.student.login.status', 'icon' => 'bi bi-plus-square-fill', 'label' => 'Student Status'],

]
],




['route' => 'admin.instructors', 'icon' => 'bi-person-badge-fill', 'label' => 'Instructors'],
['route' => 'admin.schedule', 'icon' => 'bi-calendar3', 'label' => 'Schedule'],
['route' => 'admin.approvals', 'icon' => 'bi-person-check-fill', 'label' => 'Self Registration'],
],

'ANALYTICS' => [
['route' => 'admin.reports', 'icon' => 'bi-bar-chart-line-fill', 'label' => 'Reports'],
['route' => 'admin.revenue', 'icon' => 'bi-graph-up-arrow', 'label' => 'Revenue'],
['route' => 'admin.assessments', 'icon' => 'bi-clipboard-data-fill', 'label' => 'Assessments'],
],

'MANAGE' => [
['route' => 'admin.announcements', 'icon' => 'bi-megaphone-fill', 'label' => 'Announcements', 'badge' => '3'],
['route' => 'admin.add_course', 'icon' => 'bi-plus-circle-fill', 'label' => 'Add Course'],
['route' => 'admin.add_student', 'icon' => 'bi-person-plus-fill', 'label' => 'Add Student'],
['route' => 'admin.settings', 'icon' => 'bi-gear-fill', 'label' => 'Settings'],
],
];
@endphp

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-mark">EC</div>
    <div class="logo-name">Edu<span>Core</span></div>
  </div>

  <div class="sidebar-scroll">
    @foreach($nav as $section => $items)
    <div class="sidebar-section">{{ $section }}</div>

    <div class="nav-group">
      @foreach($items as $index => $item)

      @if(isset($item['submenu']))
      @php
      $isSubmenuActive = false;
      foreach ($item['submenu'] as $sub) {
      if (request()->routeIs($sub['route'])) {
      $isSubmenuActive = true;
      break;
      }
      }
      @endphp

      <div class="nav-dropdown {{ $isSubmenuActive ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link nav-toggle {{ $isSubmenuActive ? 'active' : '' }}">
          <div class="d-flex align-items-center gap-2">
            <i class="bi {{ $item['icon'] }}"></i>
            <span class="nav-txt">{{ $item['label'] }}</span>
          </div>
          <i class="bi bi-chevron-down dropdown-arrow"></i>
        </a>

        <div class="nav-submenu" style="{{ $isSubmenuActive ? 'display:block;' : 'display:none;' }}">
          @foreach($item['submenu'] as $subItem)
          <a class="nav-sublink {{ request()->routeIs($subItem['route']) ? 'active' : '' }}"
            href="{{ route($subItem['route']) }}">
            <i class="bi {{ $subItem['icon'] }}"></i>
            <span>{{ $subItem['label'] }}</span>
          </a>
          @endforeach
        </div>
      </div>
      @else
      <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
        href="{{ route($item['route']) }}">
        <i class="bi {{ $item['icon'] }}"></i>
        <span class="nav-txt">{{ $item['label'] }}</span>

        @if(isset($item['badge']))
        <span class="nav-badge">{{ $item['badge'] }}</span>
        @endif
      </a>
      @endif

      @endforeach
    </div>

    <div class="nav-divider"></div>
    @endforeach
  </div>

  <div class="sidebar-footer">
    <div class="profile-hover-box">
      <img
        src="{{ !empty(auth()->user()->image) && file_exists(public_path('uploads/' . auth()->user()->image))
                    ? asset('uploads/' . auth()->user()->image)
                    : asset('uploads/office-man.webp') }}"
        class="topbar-avatar ms-1"
        alt="Admin"
        id="profileAvatar"
        style="width:40px;height:40px;object-fit:center;border:4px solid rgb(0, 4, 255);" />
    </div>

    <div class="sf-meta">
      <div class="uname">{{ $adminName ?? 'Admin' }}</div>
      <div class="urole">{{ ucfirst($adminRole ?? 'admin') }}</div>
    </div>
  </div>
</aside>
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-toggle').forEach(function(toggle) {
      toggle.addEventListener('click', function() {
        const parent = this.closest('.nav-dropdown');
        const submenu = parent.querySelector('.nav-submenu');

        if (parent.classList.contains('open')) {
          parent.classList.remove('open');
          submenu.style.display = 'none';
        } else {
          parent.classList.add('open');
          submenu.style.display = 'block';
        }
      });
    });
  });
</script>
@endpush