@include('Admin.includes.head');
@include('Admin.includes.sidebar');
@include('Admin.includes.topbar');
@yield('content')

@stack('scripts')
@include('Admin.includes.footer');