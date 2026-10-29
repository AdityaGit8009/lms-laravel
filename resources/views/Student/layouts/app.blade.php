<!DOCTYPE html>
<html lang="hi">

<head>
  @include('Student.includes.head')
  @stack('styles')
</head>

<body>

  <div class="s-wrapper">

    {{-- Sidebar --}}
    @include('Student.includes.sidebar')

    {{-- Main --}}
    <div class="s-main">

      {{-- Topbar --}}
      @include('Student.includes.topbar')

      {{-- Flash Messages --}}
      <div style="padding: 0 24px; margin-top: 16px;">
        @if(session('success'))
        <div class="alert-success">✓ &nbsp;{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-error">✕ &nbsp;{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert-error">
          <ul style="padding-left:16px;margin:0;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>

      {{-- Page Content --}}
      <main class="s-page">
        @yield('content')
      </main>

      {{-- Footer --}}
      @include('Student.includes.footer')

    </div>

  </div>

</body>

</html>