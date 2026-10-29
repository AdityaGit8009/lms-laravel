@extends('Admin.layouts.app')

@section('title', 'Roles')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Roles</span>
        </div>
        <h1>Roles</h1>
        <p>Manage all user roles and define access levels for different modules and operations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
          <i class="bi bi-plus-circle me-1"></i> Add Role
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 90px;">Sr. No.</th>
      <th>Role Name</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($roles) && count($roles) > 0){
    foreach($roles as $key => $role){
    $rows .= '
    <tr id="role-row-'.$role->role_id.'">
      <td>'.($key + 1).'</td>
      <td class="role-name">'.$role->role_name.'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editRoleBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$role->role_id.'"
          data-name="'.$role->role_name.'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteRoleBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$role->role_id.'">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
    ';
    }
    } else {
    $rows = '
    <tr id="emptyRow">
      <td colspan="3" class="text-center">No roles found.</td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'rolesTable',
    'tableClass' => 'role-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addRoleForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="role_name" id="add_role_name" class="form-control" required>
            <small class="text-danger" id="add_role_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Role</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editRoleForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_role_id">

          <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="role_name" id="edit_role_name" class="form-control" required>
            <small class="text-danger" id="edit_role_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Role</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function() {
    $('#rolesTable').DataTable({
      pageLength: 10,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      dom: "<'row align-items-center mb-3'<'col-md-2'l><'col-md-6 text-md-end text-start mt-2 mt-md-0'B><'col-md-3 ms-auto'f> >" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      buttons: [{
          extend: 'copy',
          className: 'btn btn-sm btn-secondary'
        },
        {
          extend: 'csv',
          className: 'btn btn-sm btn-success'
        },
        {
          extend: 'excel',
          className: 'btn btn-sm btn-success'
        },
        {
          extend: 'pdf',
          className: 'btn btn-sm btn-danger',
          orientation: 'landscape',
          pageSize: 'A4'
        },
        {
          extend: 'print',
          className: 'btn btn-sm btn-dark'
        },
        {
          extend: 'colvis',
          className: 'btn btn-sm btn-primary'
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: 2
      }]
    });
    const addRoleModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addRoleModal'));
    const editRoleModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editRoleModal'));

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

    function showToast(icon, title) {
      Toast.fire({
        icon: icon,
        title: title
      });
    }

    $('#addRoleForm').on('submit', function(e) {
      e.preventDefault();
      $('#add_role_error').text('');

      $.ajax({
        url: "{{ route('api.admin.roles.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addRoleForm')[0].reset();
          addRoleModal.hide();
          showToast('success', res.message || 'Role created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422 && xhr.responseJSON?.errors?.role_name) {
            $('#add_role_error').text(xhr.responseJSON.errors.role_name[0]);
            showToast('error', xhr.responseJSON.errors.role_name[0]);
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create role.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editRoleBtn', function() {
      $('#edit_role_id').val($(this).data('id'));
      $('#edit_role_name').val($(this).data('name'));
      $('#edit_role_error').text('');
      editRoleModal.show();
    });

    $('#editRoleForm').on('submit', function(e) {
      e.preventDefault();

      const roleId = $('#edit_role_id').val();
      $('#edit_role_error').text('');

      $.ajax({
        url: "{{ url('/api/admin/roles') }}/" + roleId,
        type: "POST",
        data: {
          _method: 'PUT',
          role_name: $('#edit_role_name').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editRoleModal.hide();
          showToast('success', res.message || 'Role updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422 && xhr.responseJSON?.errors?.role_name) {
            $('#edit_role_error').text(xhr.responseJSON.errors.role_name[0]);
            showToast('error', xhr.responseJSON.errors.role_name[0]);
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update role.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteRoleBtn', function() {
      const roleId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this role.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/roles') }}/" + roleId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Role deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete role.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush