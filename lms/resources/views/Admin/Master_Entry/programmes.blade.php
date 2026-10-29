@extends('Admin.layouts.app')

@section('title', 'Programmes')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Programmes</span>
        </div>
        <h1>Programmes</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgrammeModal">
          <i class="bi bi-plus-circle me-1"></i> Add Programme
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 60px;">Sr. No.</th>
      <th>Session</th>
      <th>Programme Name</th>
      <th>Duration</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($programmes) && count($programmes) > 0){
    foreach($programmes as $key => $programme){
    $rows .= '
    <tr id="programme-row-'.$programme->programme_id.'">
      <td>'.($key + 1).'</td>
      <td>'.($programme->session->session_name ?? '-').'</td>
      <td class="programme-name">'.$programme->programme_name.'</td>
      <td class="programme-duration">'.$programme->duration.'</td>
      <td>'.($programme->created_by ?? '-').'</td>
      <td>'.($programme->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editProgrammeBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$programme->programme_id.'"
          data-session_id="'.$programme->session_id.'"
          data-name="'.$programme->programme_name.'"
          data-duration="'.$programme->duration.'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteProgrammeBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$programme->programme_id.'">
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
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'programmesTable',
    'tableClass' => 'programme-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addProgrammeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addProgrammeForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Programme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Session</label>
            <select name="session_id" id="add_session_id" class="form-select" required>
              <option value="">Select Current Session</option>
              @foreach($currentSessions as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="add_session_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Programme Name</label>
            <input type="text" name="programme_name" id="add_programme_name" class="form-control" placeholder="Enter programme name" required>
            <small class="text-danger" id="add_programme_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Duration</label>
            <input type="text" name="duration" id="add_duration" class="form-control" placeholder="e.g. 3 Months / 2 Years" required>
            <small class="text-danger" id="add_duration_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Programme</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editProgrammeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editProgrammeForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Programme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_programme_id">

          <div class="mb-3">
            <label class="form-label">Session</label>
            <select name="session_id" id="edit_session_id" class="form-select" required>
              <option value="">Select Current Session</option>
              @foreach($currentSessions as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_session_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Programme Name</label>
            <input type="text" name="programme_name" id="edit_programme_name" class="form-control" required>
            <small class="text-danger" id="edit_programme_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Duration</label>
            <input type="text" name="duration" id="edit_duration" class="form-control" required>
            <small class="text-danger" id="edit_duration_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Programme</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script> -->

<script>
  $(function() {
    $('#programmesTable').DataTable({
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
        targets: 6
      }]
    });

    const addProgrammeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addProgrammeModal'));
    const editProgrammeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProgrammeModal'));

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

    $('#addProgrammeForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_session_id_error').text('');
      $('#add_programme_name_error').text('');
      $('#add_duration_error').text('');

      $.ajax({
        url: "{{ route('api.admin.programmes.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addProgrammeForm')[0].reset();
          addProgrammeModal.hide();
          showToast('success', res.message || 'Programme created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_id) {
              $('#add_session_id_error').text(xhr.responseJSON.errors.session_id[0]);
            }
            if (xhr.responseJSON?.errors?.programme_name) {
              $('#add_programme_name_error').text(xhr.responseJSON.errors.programme_name[0]);
            }
            if (xhr.responseJSON?.errors?.duration) {
              $('#add_duration_error').text(xhr.responseJSON.errors.duration[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create programme.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editProgrammeBtn', function() {
      $('#edit_programme_id').val($(this).data('id'));
      $('#edit_session_id').val($(this).data('session_id'));
      $('#edit_programme_name').val($(this).data('name'));
      $('#edit_duration').val($(this).data('duration'));

      $('#edit_session_id_error').text('');
      $('#edit_programme_name_error').text('');
      $('#edit_duration_error').text('');

      editProgrammeModal.show();
    });

    $('#editProgrammeForm').on('submit', function(e) {
      e.preventDefault();

      const programmeId = $('#edit_programme_id').val();

      $('#edit_session_id_error').text('');
      $('#edit_programme_name_error').text('');
      $('#edit_duration_error').text('');

      $.ajax({
        url: "{{ url('/api/admin/programmes') }}/" + programmeId,
        type: "POST",
        data: {
          _method: 'PUT',
          session_id: $('#edit_session_id').val(),
          programme_name: $('#edit_programme_name').val(),
          duration: $('#edit_duration').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editProgrammeModal.hide();
          showToast('success', res.message || 'Programme updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_id) {
              $('#edit_session_id_error').text(xhr.responseJSON.errors.session_id[0]);
            }
            if (xhr.responseJSON?.errors?.programme_name) {
              $('#edit_programme_name_error').text(xhr.responseJSON.errors.programme_name[0]);
            }
            if (xhr.responseJSON?.errors?.duration) {
              $('#edit_duration_error').text(xhr.responseJSON.errors.duration[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update programme.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteProgrammeBtn', function() {
      const programmeId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this programme.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/programmes') }}/" + programmeId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Programme deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete programme.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush