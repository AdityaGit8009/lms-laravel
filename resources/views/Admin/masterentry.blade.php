@extends('Admin.layouts.app')

@section('title','Master Entry')

@section('content')

<main class="main-content">
  <div class="page-body">

    <!-- HEADER -->
    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span>
        </div>
        <h1>Master Entry</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>
    </div>

    <!-- CARDS -->
    <div class="row g-3 mb-4">
      
    </div>

  </div>
</main>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof initDataTable === 'function') {
      initDataTable('masterTable');
    }
  });
</script>
@endpush