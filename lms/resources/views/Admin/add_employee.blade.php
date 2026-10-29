@extends('Admin.layouts.app')

@section('title', 'Add Employee')

@section('content')
<div class="container py-4">
  <h3 class="mb-3">Add Employee</h3>
  <div id="employeeMessage"></div>

  <form id="employeeForm">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="emp_name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Mobile</label>
      <input type="text" name="emp_mobile" class="form-control">
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="emp_email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="emp_password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Gender</label>
      <select name="emp_gender" class="form-control">
        <option value="">Select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Employee</button>
  </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  $(function() {
    const token = localStorage.getItem('auth_token');

    $('#employeeForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
        url: '/api/admin/employees',
        type: 'POST',
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token
        },
        success: function(res) {
          $('#employeeMessage').html('<div class="alert alert-success">' + res.message + '</div>');
          $('#employeeForm')[0].reset();
        },
        error: function(xhr) {
          let msg = 'Unable to create employee.';
          if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
          }
          $('#employeeMessage').html('<div class="alert alert-danger">' + msg + '</div>');
        }
      });
    });
  });
</script>
@endpush