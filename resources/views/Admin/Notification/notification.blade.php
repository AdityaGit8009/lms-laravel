@extends('Admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Notification</span><span>/</span>
          <span>Notifications</span>
        </div>
        <h1>Notifications</h1>
        <p>Manage notification records with cascading filters and file upload.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
          <i class="bi bi-plus-circle me-1"></i> Add Notification
        </button>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="notificationsTable" class="table table-bordered table-striped w-100">
            <thead>
              <tr>
                <th style="width: 60px;">Sr. No.</th>
                <th>Session</th>
                <th>Center</th>
                <th style="min-width: 260px;">Programme / Technology / Batch</th>
                <th>Notification Head</th>
                <th>Title</th>
                <th>File</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created At</th>
                <th style="width: 110px;">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addNotificationForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Session</label>
              <select name="session_id" id="session_id" class="form-select" required>
                <option value="">Select Session</option>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="session_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Center</label>
              <select name="center_id" id="center_id" class="form-select" required>
                <option value="">Select Center</option>
              </select>
              <small class="text-danger" id="center_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Programme</label>
              <select name="programme_id" id="programme_id" class="form-select" required>
                <option value="">Select Programme</option>
              </select>
              <small class="text-danger" id="programme_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Technology</label>
              <select name="technology_id" id="technology_id" class="form-select" required>
                <option value="">Select Technology</option>
              </select>
              <small class="text-danger" id="technology_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Batch</label>
              <select name="batch_id" id="batch_id" class="form-select" required>
                <option value="">Select Batch</option>
              </select>
              <small class="text-danger" id="batch_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Notification Head</label>
              <select name="notification_head_id" id="notification_head_id" class="form-select" required>
                <option value="">Select Notification Head</option>
                @foreach($notificationHeads as $head)
                <option value="{{ $head->notification_head_id }}">{{ $head->notification_head }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="notification_head_id_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
              <small class="text-danger" id="title_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">File</label>
              <input type="file" name="notification_file" id="notification_file" class="form-control">
              <small class="text-danger" id="notification_file_error"></small>
            </div>

            <div class="col-md-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control"></textarea>
              <small class="text-danger" id="description_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Notification</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editNotificationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editNotificationForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_notification_id">

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Session</label>
              <select name="session_id" id="edit_session_id" class="form-select" required>
                <option value="">Select Session</option>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_session_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Center</label>
              <select name="center_id" id="edit_center_id" class="form-select" required>
                <option value="">Select Center</option>
              </select>
              <small class="text-danger" id="edit_center_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Programme</label>
              <select name="programme_id" id="edit_programme_id" class="form-select" required>
                <option value="">Select Programme</option>
              </select>
              <small class="text-danger" id="edit_programme_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Technology</label>
              <select name="technology_id" id="edit_technology_id" class="form-select" required>
                <option value="">Select Technology</option>
              </select>
              <small class="text-danger" id="edit_technology_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Batch</label>
              <select name="batch_id" id="edit_batch_id" class="form-select" required>
                <option value="">Select Batch</option>
              </select>
              <small class="text-danger" id="edit_batch_id_error"></small>
            </div>

            <div class="col-md-4">
              <label class="form-label">Notification Head</label>
              <select name="notification_head_id" id="edit_notification_head_id" class="form-select" required>
                <option value="">Select Notification Head</option>
                @foreach($notificationHeads as $head)
                <option value="{{ $head->notification_head_id }}">{{ $head->notification_head }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_notification_head_id_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter title" required>
              <small class="text-danger" id="edit_title_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">File</label>
              <input type="file" name="notification_file" id="edit_notification_file" class="form-control">
              <small class="text-danger" id="edit_notification_file_error"></small>
              <div class="mt-2" id="currentFileBox" style="display:none;">
                <a href="#" target="_blank" id="currentFileLink" class="btn btn-sm btn-info text-white">
                  <i class="bi bi-paperclip me-1"></i> Current File
                </a>
              </div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="edit_description" class="form-control"></textarea>
              <small class="text-danger" id="edit_description_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Notification</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Description View Modal -->
<div class="modal fade" id="viewDescriptionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Description</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="viewDescriptionContent"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  #notificationsTable th,
  #notificationsTable td {
    white-space: nowrap;
    vertical-align: middle;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let descriptionEditor, editDescriptionEditor, notificationsTable = null;

  $(function() {
    const addNotificationModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addNotificationModal'));
    const editNotificationModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editNotificationModal'));
    const viewDescriptionModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewDescriptionModal'));

    ClassicEditor.create(document.querySelector('#description')).then(editor => {
      descriptionEditor = editor;
    });

    ClassicEditor.create(document.querySelector('#edit_description')).then(editor => {
      editDescriptionEditor = editor;
    });

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true
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

    function escapeHtml(value) {
      return $('<div>').text(value ?? '').html();
    }

    function encodeHtml(value) {
      return encodeURIComponent(value ?? '');
    }

    function decodeHtml(value) {
      try {
        return decodeURIComponent(value || '');
      } catch (e) {
        return value || '';
      }
    }

    function hasDescriptionContent(html) {
      return $.trim($('<div>').html(html || '').text()).length > 0;
    }

    function clearErrors(prefix = '') {
      $(`small[id^="${prefix}"][id$="_error"]`).text('');
    }

    function showValidationErrors(errors, prefix = '') {
      $.each(errors || {}, function(field, messages) {
        $('#' + prefix + field + '_error').text(Array.isArray(messages) ? messages[0] : messages);
      });
    }

    function fillSelect(selector, data, placeholder, idKey, textKey, selectedValue = '') {
      let html = `<option value="">${placeholder}</option>`;
      $.each(data, function(index, item) {
        const selected = String(selectedValue) === String(item[idKey]) ? 'selected' : '';
        html += `<option value="${item[idKey]}" ${selected}>${item[textKey]}</option>`;
      });
      $(selector).html(html);
    }

    function resetAddBelow(level) {
      if (level <= 1) $('#center_id').html('<option value="">Select Center</option>');
      if (level <= 2) $('#programme_id').html('<option value="">Select Programme</option>');
      if (level <= 3) $('#technology_id').html('<option value="">Select Technology</option>');
      if (level <= 4) $('#batch_id').html('<option value="">Select Batch</option>');
    }

    function resetEditBelow(level) {
      if (level <= 1) $('#edit_center_id').html('<option value="">Select Center</option>');
      if (level <= 2) $('#edit_programme_id').html('<option value="">Select Programme</option>');
      if (level <= 3) $('#edit_technology_id').html('<option value="">Select Technology</option>');
      if (level <= 4) $('#edit_batch_id').html('<option value="">Select Batch</option>');
    }

    function loadCenters(sessionId, selector, selectedValue = '') {
      return $.ajax({
        url: "{{ route('admin.notifications.centers', ':id') }}".replace(':id', sessionId),
        type: "GET",
        success: function(res) {
          fillSelect(selector, res.centers || [], 'Select Center', 'center_id', 'center_name', selectedValue);
        }
      });
    }

    function loadProgrammes(sessionId, centerId, selector, selectedValue = '') {
      return $.ajax({
        url: "{{ route('admin.notifications.programmes') }}",
        type: "GET",
        data: {
          session_id: sessionId,
          center_id: centerId
        },
        success: function(res) {
          fillSelect(selector, res.programmes || [], 'Select Programme', 'programme_id', 'programme_name', selectedValue);
        }
      });
    }

    function loadTechnologies(sessionId, programmeId, selector, selectedValue = '') {
      return $.ajax({
        url: "{{ route('admin.notifications.technologies') }}",
        type: "GET",
        data: {
          session_id: sessionId,
          programme_id: programmeId
        },
        success: function(res) {
          fillSelect(selector, res.technologies || [], 'Select Technology', 'technology_id', 'technology_name', selectedValue);
        }
      });
    }

    function loadBatches(sessionId, programmeId, technologyId, selector, selectedValue = '') {
      return $.ajax({
        url: "{{ route('admin.notifications.batches') }}",
        type: "GET",
        data: {
          session_id: sessionId,
          programme_id: programmeId,
          technology_id: technologyId
        },
        success: function(res) {
          fillSelect(selector, res.batches || [], 'Select Batch', 'batch_id', 'batch_name', selectedValue);
        }
      });
    }

    function initialiseDataTable() {
      if ($.fn.DataTable.isDataTable('#notificationsTable')) {
        $('#notificationsTable').DataTable().destroy();
      }

      notificationsTable = $('#notificationsTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: false,
        scrollX: true,
        dom: "<'row align-items-center mb-3'<'col-md-2'l><'col-md-6 text-md-end text-start mt-2 mt-md-0'B><'col-md-3 ms-auto'f>>" +
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
          targets: [6, 7, 10]
        }]
      });
    }

    function renderNotifications(rows) {
      let html = '';

      if (!rows.length) {
        html = `
          <tr>
            <td colspan="11" class="text-center">No records found.</td>
          </tr>
        `;
      } else {
        $.each(rows, function(index, item) {
          const descriptionEncoded = encodeHtml(item.description || '');
          const fileHtml = item.file_url ?
            `<a href="${item.file_url}" target="_blank" class="btn btn-sm btn-info text-white">
                 <i class="bi bi-paperclip me-1"></i> View File
               </a>` :
            '-';

          const descriptionBtn = hasDescriptionContent(item.description) ?
            `<button type="button"
                  class="btn btn-sm btn-secondary viewDescriptionBtn"
                  data-title="${escapeHtml(item.title || 'Notification Description')}"
                  data-description="${descriptionEncoded}">
                  <i class="bi bi-eye me-1"></i> View
               </button>` :
            '-';

          html += `
            <tr id="notification-row-${item.notification_id}">
              <td>${index + 1}</td>
              <td>${escapeHtml(item.session_name || '-')}</td>
              <td>${escapeHtml(item.center_name || '-')}</td>
              <td style="white-space: normal; min-width: 260px;">
                <div><strong>Programme:</strong> ${escapeHtml(item.programme_name || '-')}</div>
                <div><strong>Technology:</strong> ${escapeHtml(item.technology_name || '-')}</div>
                <div><strong>Batch:</strong> ${escapeHtml(item.batch_name || '-')}</div>
              </td>
              <td>${escapeHtml(item.notification_head || '-')}</td>
              <td>${escapeHtml(item.title || '-')}</td>
              <td>${fileHtml}</td>
              <td>${descriptionBtn}</td>
              <td>${escapeHtml(item.created_by || '-')}</td>
              <td>${escapeHtml(item.created_at_formatted || '-')}</td>
              <td>
                <button type="button"
                  class="btn btn-sm btn-warning rounded-circle editNotificationBtn"
                  style="padding-left:10px; padding-right:10px;"
                  data-id="${item.notification_id}"
                  data-session_id="${item.session_id}"
                  data-center_id="${item.center_id}"
                  data-programme_id="${item.programme_id}"
                  data-technology_id="${item.technology_id}"
                  data-batch_id="${item.batch_id}"
                  data-notification_head_id="${item.notification_head_id}"
                  data-title="${escapeHtml(item.title || '')}"
                  data-description="${descriptionEncoded}"
                  data-file_url="${item.file_url || ''}">
                  <i class="bi bi-pencil-square"></i>
                </button>

                <button type="button"
                  class="btn btn-sm btn-danger rounded-circle deleteNotificationBtn"
                  style="padding-left:10px; padding-right:10px;"
                  data-id="${item.notification_id}">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          `;
        });
      }

      $('#notificationsTable tbody').html(html);
      initialiseDataTable();
    }

    function loadNotifications() {
      $.ajax({
        url: "{{ route('admin.notifications.list') }}",
        type: "GET",
        success: function(res) {
          renderNotifications(res.data || []);
        },
        error: function() {
          showToast('error', 'Unable to load notifications.');
        }
      });
    }

    $('#session_id').on('change', function() {
      const sessionId = $(this).val();
      resetAddBelow(1);
      if (!sessionId) return;
      loadCenters(sessionId, '#center_id');
    });

    $('#center_id').on('change', function() {
      const sessionId = $('#session_id').val();
      const centerId = $(this).val();
      resetAddBelow(2);
      if (!sessionId || !centerId) return;
      loadProgrammes(sessionId, centerId, '#programme_id');
    });

    $('#programme_id').on('change', function() {
      const sessionId = $('#session_id').val();
      const programmeId = $(this).val();
      resetAddBelow(3);
      if (!sessionId || !programmeId) return;
      loadTechnologies(sessionId, programmeId, '#technology_id');
    });

    $('#technology_id').on('change', function() {
      const sessionId = $('#session_id').val();
      const programmeId = $('#programme_id').val();
      const technologyId = $(this).val();
      resetAddBelow(4);
      if (!sessionId || !programmeId || !technologyId) return;
      loadBatches(sessionId, programmeId, technologyId, '#batch_id');
    });

    $('#edit_session_id').on('change', function() {
      const sessionId = $(this).val();
      resetEditBelow(1);
      if (!sessionId) return;
      loadCenters(sessionId, '#edit_center_id');
    });

    $('#edit_center_id').on('change', function() {
      const sessionId = $('#edit_session_id').val();
      const centerId = $(this).val();
      resetEditBelow(2);
      if (!sessionId || !centerId) return;
      loadProgrammes(sessionId, centerId, '#edit_programme_id');
    });

    $('#edit_programme_id').on('change', function() {
      const sessionId = $('#edit_session_id').val();
      const programmeId = $(this).val();
      resetEditBelow(3);
      if (!sessionId || !programmeId) return;
      loadTechnologies(sessionId, programmeId, '#edit_technology_id');
    });

    $('#edit_technology_id').on('change', function() {
      const sessionId = $('#edit_session_id').val();
      const programmeId = $('#edit_programme_id').val();
      const technologyId = $(this).val();
      resetEditBelow(4);
      if (!sessionId || !programmeId || !technologyId) return;
      loadBatches(sessionId, programmeId, technologyId, '#edit_batch_id');
    });

    $('#addNotificationForm').on('submit', function(e) {
      e.preventDefault();
      clearErrors('');

      const formData = new FormData(this);
      formData.set('description', descriptionEditor ? descriptionEditor.getData() : '');

      $.ajax({
        url: "{{ route('admin.notifications.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: ajaxHeaders(),
        success: function(res) {
          $('#addNotificationForm')[0].reset();
          resetAddBelow(1);
          if (descriptionEditor) descriptionEditor.setData('');
          addNotificationModal.hide();
          showToast('success', res.message || 'Notification added successfully.');
          loadNotifications();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            showValidationErrors(xhr.responseJSON?.errors, '');
            showToast('error', 'Please check the required fields.');
            return;
          }

          const msg = xhr.responseJSON?.message || 'Unable to save notification.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.viewDescriptionBtn', function() {
      const title = $(this).data('title') || 'Description';
      const description = decodeHtml($(this).attr('data-description')) || '<p>No description available.</p>';

      $('#viewDescriptionModal .modal-title').text(title);
      $('#viewDescriptionContent').html(description);
      viewDescriptionModal.show();
    });

    $(document).on('click', '.editNotificationBtn', function() {
      const id = $(this).data('id');
      const sessionId = $(this).data('session_id');
      const centerId = $(this).data('center_id');
      const programmeId = $(this).data('programme_id');
      const technologyId = $(this).data('technology_id');
      const batchId = $(this).data('batch_id');
      const notificationHeadId = $(this).data('notification_head_id');
      const title = $(this).data('title');
      const description = decodeHtml($(this).attr('data-description'));
      const fileUrl = $(this).attr('data-file_url');

      clearErrors('edit_');
      $('#edit_notification_id').val(id);
      $('#edit_session_id').val(sessionId);
      $('#edit_notification_head_id').val(notificationHeadId);
      $('#edit_title').val(title);

      if (editDescriptionEditor) {
        editDescriptionEditor.setData(description || '');
      }

      if (fileUrl) {
        $('#currentFileLink').attr('href', fileUrl);
        $('#currentFileBox').show();
      } else {
        $('#currentFileLink').attr('href', '#');
        $('#currentFileBox').hide();
      }

      resetEditBelow(1);

      loadCenters(sessionId, '#edit_center_id', centerId)
        .then(function() {
          return loadProgrammes(sessionId, centerId, '#edit_programme_id', programmeId);
        })
        .then(function() {
          return loadTechnologies(sessionId, programmeId, '#edit_technology_id', technologyId);
        })
        .then(function() {
          return loadBatches(sessionId, programmeId, technologyId, '#edit_batch_id', batchId);
        });

      editNotificationModal.show();
    });

    $('#editNotificationForm').on('submit', function(e) {
      e.preventDefault();
      clearErrors('edit_');

      const id = $('#edit_notification_id').val();
      const formData = new FormData(this);
      formData.append('_method', 'PUT');
      formData.set('description', editDescriptionEditor ? editDescriptionEditor.getData() : '');

      $.ajax({
        url: "{{ route('admin.notifications.update', ':id') }}".replace(':id', id),
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: ajaxHeaders(),
        success: function(res) {
          editNotificationModal.hide();
          showToast('success', res.message || 'Notification updated successfully.');
          loadNotifications();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            showValidationErrors(xhr.responseJSON?.errors, 'edit_');
            showToast('error', 'Please check the required fields.');
            return;
          }

          const msg = xhr.responseJSON?.message || 'Unable to update notification.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteNotificationBtn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this notification.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
          url: "{{ route('admin.notifications.destroy', ':id') }}".replace(':id', id),
          type: "POST",
          data: {
            _method: 'DELETE'
          },
          headers: ajaxHeaders(),
          success: function(res) {
            showToast('success', res.message || 'Notification deleted successfully.');
            loadNotifications();
          },
          error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Unable to delete notification.';
            showToast('error', msg);
          }
        });
      });
    });

    loadNotifications();
  });
</script>
@endpush