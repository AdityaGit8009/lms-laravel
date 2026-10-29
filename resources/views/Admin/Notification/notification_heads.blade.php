@extends('Admin.layouts.app')

@section('title', 'Notification Heads')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Notification</span><span>/</span>
          <span>Notification Heads</span>
        </div>
        <h1>Notification Heads</h1>
        <p>Manage notification head master data for notification creation and categorization.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationHeadModal">
          <i class="bi bi-plus-circle me-1"></i> Add Notification Head
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 60px;">Sr. No.</th>
      <th>Notification Head</th>
      <th>Created By</th>
      <th>Created At</th>
      <th style="width: 110px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($notificationHeads) && count($notificationHeads) > 0){
    foreach($notificationHeads as $key => $item){
    $rows .= '
    <tr id="notification-head-row-'.$item->notification_head_id.'">
      <td>'.($key + 1).'</td>
      <td class="notification-head-name">'.e($item->notification_head).'</td>
      <td>'.e($item->created_by ?? '-').'</td>
      <td>'.e(optional($item->created_at)->format('d M Y, h:i A') ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editNotificationHeadBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$item->notification_head_id.'"
          data-name="'.e($item->notification_head).'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteNotificationHeadBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$item->notification_head_id.'">
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
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'notificationHeadsTable',
    'tableClass' => 'notification-heads-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addNotificationHeadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="addNotificationHeadForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Notification Head</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Notification Head</label>
            <input type="text" name="notification_head" id="add_notification_head" class="form-control" placeholder="Enter notification head" required>
            <small class="text-danger" id="add_notification_head_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Notification Head</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editNotificationHeadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="editNotificationHeadForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Notification Head</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_notification_head_id">

          <div class="mb-3">
            <label class="form-label">Notification Head</label>
            <input type="text" name="notification_head" id="edit_notification_head" class="form-control" placeholder="Enter notification head" required>
            <small class="text-danger" id="edit_notification_head_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Notification Head</button>
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
    $('#notificationHeadsTable').DataTable({
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

    const addNotificationHeadModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addNotificationHeadModal'));
    const editNotificationHeadModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editNotificationHeadModal'));

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
        icon,
        title
      });
    }

    function ajaxHeaders() {
      return {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json'
      };
    }

    $('#addNotificationHeadForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_notification_head_error').text('');

      let formData = new FormData(this);

      $.ajax({
        url: "{{ route('admin.notification-heads.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: ajaxHeaders(),
        success: function(res) {
          $('#addNotificationHeadForm')[0].reset();
          addNotificationHeadModal.hide();
          showToast('success', res.message || 'Notification head created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.notification_head) {
              $('#add_notification_head_error').text(xhr.responseJSON.errors.notification_head[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create notification head.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editNotificationHeadBtn', function() {
      const id = $(this).data('id');
      const name = $(this).data('name');

      $('#edit_notification_head_id').val(id);
      $('#edit_notification_head').val(name);
      $('#edit_notification_head_error').text('');

      editNotificationHeadModal.show();
    });

    $('#editNotificationHeadForm').on('submit', function(e) {
      e.preventDefault();

      const id = $('#edit_notification_head_id').val();
      $('#edit_notification_head_error').text('');

      let formData = new FormData(this);
      formData.append('_method', 'PUT');

      $.ajax({
        url: "{{ route('admin.notification-heads.update', ':id') }}".replace(':id', id),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: ajaxHeaders(),
        success: function(res) {
          editNotificationHeadModal.hide();
          showToast('success', res.message || 'Notification head updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.notification_head) {
              $('#edit_notification_head_error').text(xhr.responseJSON.errors.notification_head[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update notification head.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteNotificationHeadBtn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this notification head.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.notification-heads.destroy', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: ajaxHeaders(),
            success: function(res) {
              showToast('success', res.message || 'Notification head deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete notification head.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush