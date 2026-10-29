@extends('Admin.layouts.app')

@section('title', 'Branches')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Branches</span>
        </div>
        <h1>Branches</h1>
        <p>Manage branches and map them with courses for academic operations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
          <i class="bi bi-plus-circle me-1"></i> Add Branch
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 80px;">Sr. No.</th>
      <th>Course Name</th>
      <th>Branch Name</th>
      <th>Created By</th>
      <th style="width: 130px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($branches) && count($branches) > 0){
    foreach($branches as $key => $branch){
    $rows .= '
    <tr id="branch-row-'.$branch->branch_id.'">
      <td>'.($key + 1).'</td>
      <td>'.e($branch->course->course_name ?? '-').'</td>
      <td class="branch-name">'.e($branch->branch_name).'</td>
      <td>'.e($branch->created_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editBranchBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$branch->branch_id.'"
          data-course_id="'.$branch->course_id.'"
          data-name="'.e($branch->branch_name).'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteBranchBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$branch->branch_id.'">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
    ';
    }
    } else {
    $rows = '
    <tr id="emptyRow">
      <td></td>
      <td class="text-center">No branches found.</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'branchesTable',
    'tableClass' => 'branch-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addBranchForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Course Name</label>
            <select name="course_id" id="add_course_id" class="form-select" required>
              <option value="">Select Course</option>
              @foreach($courses as $course)
              <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="add_course_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Branch Name</label>
            <input type="text" name="branch_name" id="add_branch_name" class="form-control" placeholder="Enter branch name" required>
            <small class="text-danger" id="add_branch_name_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Branch</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editBranchForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_branch_id">

          <div class="mb-3">
            <label class="form-label">Course Name</label>
            <select name="course_id" id="edit_course_id" class="form-select" required>
              <option value="">Select Course</option>
              @foreach($courses as $course)
              <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_course_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Branch Name</label>
            <input type="text" name="branch_name" id="edit_branch_name" class="form-control" required>
            <small class="text-danger" id="edit_branch_name_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Branch</button>
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
    $('#branchesTable').DataTable({
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
        targets: 4
      }]
    });

    const addBranchModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addBranchModal'));
    const editBranchModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editBranchModal'));

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

    function clearAddErrors() {
      $('#add_course_id_error').text('');
      $('#add_branch_name_error').text('');
    }

    function clearEditErrors() {
      $('#edit_course_id_error').text('');
      $('#edit_branch_name_error').text('');
    }

    $('#addBranchForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();

      $.ajax({
        url: "{{ route('api.admin.branches.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addBranchForm')[0].reset();
          addBranchModal.hide();
          showToast('success', res.message || 'Branch created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.course_id) {
              $('#add_course_id_error').text(xhr.responseJSON.errors.course_id[0]);
            }
            if (xhr.responseJSON?.errors?.branch_name) {
              $('#add_branch_name_error').text(xhr.responseJSON.errors.branch_name[0]);
            }
            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create branch.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editBranchBtn', function() {
      $('#edit_branch_id').val($(this).data('id'));
      $('#edit_course_id').val($(this).data('course_id'));
      $('#edit_branch_name').val($(this).data('name'));
      clearEditErrors();
      editBranchModal.show();
    });

    $('#editBranchForm').on('submit', function(e) {
      e.preventDefault();

      const branchId = $('#edit_branch_id').val();
      clearEditErrors();

      $.ajax({
        url: "{{ route('api.admin.branches.update', ':id') }}".replace(':id', branchId),
        type: "POST",
        data: {
          _method: 'PUT',
          course_id: $('#edit_course_id').val(),
          branch_name: $('#edit_branch_name').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editBranchModal.hide();
          showToast('success', res.message || 'Branch updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.course_id) {
              $('#edit_course_id_error').text(xhr.responseJSON.errors.course_id[0]);
            }
            if (xhr.responseJSON?.errors?.branch_name) {
              $('#edit_branch_name_error').text(xhr.responseJSON.errors.branch_name[0]);
            }
            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update branch.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteBranchBtn', function() {
      const branchId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this branch.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('api.admin.branches.destroy', ':id') }}".replace(':id', branchId),
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Branch deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete branch.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush