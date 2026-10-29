@extends('Admin.layouts.app')

@section('title', 'Change Password')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Change Password</span>
        </div>
        <h1>Change Password</h1>
        <p>Update your account password securely.</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-header bg-white border-0 pt-4 px-4">
            <h4 class="mb-0">Change Password</h4>
          </div>

          <div class="card-body p-4">
            <form id="changePasswordForm">
              @csrf

              <div class="mb-3">
                <label class="form-label fw-semibold">Current Password</label>
                <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
              </div>

              <button type="submit" class="btn btn-primary px-4">
                Update Password
              </button>
            </form>

            <div id="passwordMsg" class="mt-3"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(document).ready(function() {
    $('#changePasswordForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route("api.admin.change.password") }}',
        type: 'POST',
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Accept': 'application/json'
        },
        success: function(response) {
          $('#passwordMsg').html('<div class="alert alert-success">' + response.message + '</div>');
          $('#changePasswordForm')[0].reset();
        },
        error: function(xhr) {
          let html = '<div class="alert alert-danger">Password update failed.</div>';

          if (xhr.responseJSON && xhr.responseJSON.errors) {
            html = '<div class="alert alert-danger"><ul class="mb-0">';
            $.each(xhr.responseJSON.errors, function(key, value) {
              html += '<li>' + value[0] + '</li>';
            });
            html += '</ul></div>';
          } else if (xhr.responseJSON && xhr.responseJSON.message) {
            html = '<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>';
          }

          $('#passwordMsg').html(html);
        }
      });
    });
  });
</script>
@endpush