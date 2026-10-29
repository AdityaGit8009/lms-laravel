@extends('Admin.layouts.app')

@section('title', 'Sessions')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Sessions</span>
        </div>
        <h1>Sessions</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
          <i class="bi bi-plus-circle me-1"></i> Add Session
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 60px;">Sr. No.</th>
      <th>Session Name</th>
      <th>Status</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($sessions) && count($sessions) > 0){
    foreach($sessions as $key => $session){
    $badgeClass = match($session->status){
    'old' => 'secondary',
    'current' => 'success',
    'upcomming' => 'warning',
    default => 'dark'
    };

    $rows .= '
    <tr id="session-row-'.$session->session_id.'">
      <td>'.($key + 1).'</td>
      <td class="session-name">'.$session->session_name.'</td>
      <td><span class="badge bg-'.$badgeClass.' text-uppercase">'.$session->status.'</span></td>
      <td>'.($session->created_by ?? '-').'</td>
      <td>'.($session->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editSessionBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$session->session_id.'"
          data-name="'.$session->session_name.'"
          data-status="'.$session->status.'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteSessionBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$session->session_id.'">
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
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'sessionsTable',
    'tableClass' => 'session-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<div class="modal fade" id="addSessionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addSessionForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Session</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Session Name</label>
            <input type="text" name="session_name" id="add_session_name" class="form-control" placeholder="e.g. 2025-26" required>
            <small class="text-danger" id="add_session_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="add_status" class="form-select" required>
              <option value="">Select Status</option>
              <option value="old">Old</option>
              <option value="current">Current</option>
              <option value="upcomming">Upcomming</option>
            </select>
            <small class="text-danger" id="add_status_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Session</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editSessionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSessionForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Session</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_session_id">

          <div class="mb-3">
            <label class="form-label">Session Name</label>
            <input type="text" name="session_name" id="edit_session_name" class="form-control" required>
            <small class="text-danger" id="edit_session_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-select" required>
              <option value="">Select Status</option>
              <option value="old">Old</option>
              <option value="current">Current</option>
              <option value="upcomming">Upcomming</option>
            </select>
            <small class="text-danger" id="edit_status_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Session</button>
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
    $('#sessionsTable').DataTable({
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
        targets: 5
      }]
    });

    const addSessionModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addSessionModal'));
    const editSessionModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editSessionModal'));

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

    $('#addSessionForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_session_name_error').text('');
      $('#add_status_error').text('');

      $.ajax({
        url: "{{ route('api.admin.sessions.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addSessionForm')[0].reset();
          addSessionModal.hide();
          showToast('success', res.message || 'Session created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_name) {
              $('#add_session_name_error').text(xhr.responseJSON.errors.session_name[0]);
            }
            if (xhr.responseJSON?.errors?.status) {
              $('#add_status_error').text(xhr.responseJSON.errors.status[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create session.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editSessionBtn', function() {
      $('#edit_session_id').val($(this).data('id'));
      $('#edit_session_name').val($(this).data('name'));
      $('#edit_status').val($(this).data('status'));
      $('#edit_session_name_error').text('');
      $('#edit_status_error').text('');
      editSessionModal.show();
    });

    $('#editSessionForm').on('submit', function(e) {
      e.preventDefault();

      const sessionId = $('#edit_session_id').val();
      $('#edit_session_name_error').text('');
      $('#edit_status_error').text('');

      $.ajax({
        url: "{{ url('/api/admin/sessions') }}/" + sessionId,
        type: "POST",
        data: {
          _method: 'PUT',
          session_name: $('#edit_session_name').val(),
          status: $('#edit_status').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editSessionModal.hide();
          showToast('success', res.message || 'Session updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_name) {
              $('#edit_session_name_error').text(xhr.responseJSON.errors.session_name[0]);
            }
            if (xhr.responseJSON?.errors?.status) {
              $('#edit_status_error').text(xhr.responseJSON.errors.status[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update session.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteSessionBtn', function() {
      const sessionId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this session.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/sessions') }}/" + sessionId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Session deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete session.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush