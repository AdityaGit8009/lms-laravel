@extends('Admin.layouts.app')

@section('title', 'Student Approvals')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Admin Profile</span>
        </div>
        <h1>Admin Profile</h1>
        <p>Manage your admin profile information and settings.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body text-center pb-5 pt-4">
            <img
              id="profilePreview"
              src="{{ $admin->image ? asset('uploads/' . $admin->image) : asset('uploads/default-user.png') }}"
              class="rounded-circle mb-3"
              style="width:120px;height:120px;object-fit:center;border:4px solid #e9ecef;margin:auto;"
              alt="Admin">

            <h4 class="mb-1">{{ $admin->name }}</h4>
            <p class="text-muted mb-1">{{ ucfirst($admin->role->role_name) }}</p>
            <p class="mb-1"><strong>Email:</strong> {{ $admin->email }}</p>
            <p class="mb-0"><strong>Phone:</strong> {{ $admin->phone ?? 'N/A' }}</p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body">
            <h4 class="mb-4">Edit Profile</h4>

            <form id="adminProfileForm" enctype="multipart/form-data">
              @csrf

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control" value="{{ $admin->name }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="{{ $admin->email }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="text" name="phone" class="form-control" value="{{ $admin->phone }}">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Profile Image</label>
                  <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </div>
            </form>

            <div id="profileMsg" class="mt-3"></div>
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
    $('#imageInput').on('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          $('#profilePreview').attr('src', ev.target.result);
        };
        reader.readAsDataURL(file);
      }
    });

    $('#adminProfileForm').on('submit', function(e) {
      e.preventDefault();

      let formData = new FormData(this);

      $.ajax({
        url: '{{ route("api.admin.profile.update") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'Accept': 'application/json'
        },
        success: function(response) {
          $('#profileMsg').html('<div class="alert alert-success">' + response.message + '</div>');

          $('#profilePreview').attr('src', response.image_url);
          $('#profileAvatar').attr('src', response.image_url);

          $('#adminNameText').text(response.name);
          $('#adminEmailText').html('<strong>Email:</strong> ' + response.email);
          $('#adminPhoneText').html('<strong>Phone:</strong> ' + (response.phone ? response.phone : 'N/A'));

          setTimeout(function() {
            location.reload();
          }, 800);
        },
        error: function(xhr) {
          let html = '<div class="alert alert-danger">Profile update failed.</div>';
          if (xhr.responseJSON && xhr.responseJSON.errors) {
            html = '<div class="alert alert-danger"><ul class="mb-0">';
            $.each(xhr.responseJSON.errors, function(key, value) {
              html += '<li>' + value[0] + '</li>';
            });
            html += '</ul></div>';
          }
          $('#profileMsg').html(html);
        }
      });
    });
  });
</script>
@endpush