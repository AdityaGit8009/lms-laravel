@extends('Admin.layouts.app')

@section('title', 'Popup Notifications')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Popup Notifications</span>
        </div>
        <h1>Popup Notifications</h1>
        <p>Manage popup notifications with same cascade order as notification module.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPopupModal">
          <i class="bi bi-plus-circle me-1"></i> Add Popup Notification
        </button>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="popupNotificationsTable" class="table table-bordered table-striped table-hover w-100">
            <thead>
              <tr>
                <th style="width:60px;">Sr. No.</th>
                <th>Sessions</th>
                <th>Centers</th>
                <th style="min-width: 260px;">Programme / Technology / Batch</th>
                <th>Title</th>
                <th>Popup From</th>
                <th>Popup To</th>
                <th>Status</th>
                <th>Image</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created At</th>
                <th style="width:110px;">Action</th>
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
<div class="modal fade" id="addPopupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="addPopupForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Popup Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Sessions</label>
              <select id="session_ids" name="session_ids[]" class="form-select" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="session_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Centers</label>
              <select id="center_ids" name="center_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="center_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Programmes</label>
              <select id="programme_ids" name="programme_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="programme_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Technologies</label>
              <select id="technology_ids" name="technology_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="technology_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Batches</label>
              <select id="batch_ids" name="batch_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="batch_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Enter title">
              <small class="text-danger" id="title_error"></small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Popup From</label>
              <input type="datetime-local" name="popup_from" id="popup_from" class="form-control">
              <small class="text-danger" id="popup_from_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Popup To</label>
              <input type="datetime-local" name="popup_to" id="popup_to" class="form-control">
              <small class="text-danger" id="popup_to_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Image</label>
              <input type="file" name="image" id="image" class="form-control" accept="image/*">
              <small class="text-danger" id="image_error"></small>
            </div>

            <div class="col-md-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter description"></textarea>
              <small class="text-danger" id="description_error"></small>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Popup Notification</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPopupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="editPopupForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Popup Notification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_popup_notification_id">

          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Sessions</label>
              <select id="edit_session_ids" name="session_ids[]" class="form-select" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_session_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Centers</label>
              <select id="edit_center_ids" name="center_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="edit_center_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Programmes</label>
              <select id="edit_programme_ids" name="programme_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="edit_programme_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Technologies</label>
              <select id="edit_technology_ids" name="technology_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="edit_technology_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Batches</label>
              <select id="edit_batch_ids" name="batch_ids[]" class="form-select" multiple></select>
              <small class="text-danger" id="edit_batch_ids_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter title">
              <small class="text-danger" id="edit_title_error"></small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Popup From</label>
              <input type="datetime-local" name="popup_from" id="edit_popup_from" class="form-control">
              <small class="text-danger" id="edit_popup_from_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Popup To</label>
              <input type="datetime-local" name="popup_to" id="edit_popup_to" class="form-control">
              <small class="text-danger" id="edit_popup_to_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Image</label>
              <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
              <small class="text-danger" id="edit_image_error"></small>
              <div class="mt-2" id="currentImageBox" style="display:none;">
                <img src="" id="currentImagePreview" class="img-thumbnail" style="width:120px;height:80px;object-fit:cover;">
              </div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="edit_description" class="form-control" rows="4" placeholder="Enter description"></textarea>
              <small class="text-danger" id="edit_description_error"></small>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Popup Notification</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Description Modal -->
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<style>
  #popupNotificationsTable th,
  #popupNotificationsTable td {
    white-space: nowrap;
    vertical-align: middle;
  }

  .popup-thumb {
    width: 70px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd;
  }

  .choices__inner {
    min-height: 42px;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function initTable() {
    if ($.fn.DataTable.isDataTable('#popupNotificationsTable')) {
      $('#popupNotificationsTable').DataTable().clear().destroy();
    }

    popupTable = $('#popupNotificationsTable').DataTable({
      pageLength: 10,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      scrollX: true,
      destroy: true,
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
        targets: [5, 6, 9]
      }],
      language: {
        emptyTable: 'No records found.'
      }
    });
  }
  let popupTable = null;

  let addSessionChoices, addCenterChoices, addProgrammeChoices, addTechnologyChoices, addBatchChoices;
  let editSessionChoices, editCenterChoices, editProgrammeChoices, editTechnologyChoices, editBatchChoices;

  $(function() {
    const addPopupModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addPopupModal'));
    const editPopupModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editPopupModal'));
    const viewDescModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewDescriptionModal'));

    const listUrl = "{{ route('admin.popup-notifications.list') }}";
    const storeUrl = "{{ route('admin.popup-notifications.store') }}";
    const centersUrl = "{{ route('admin.popup-notifications.centers') }}";
    const programmesUrl = "{{ route('admin.popup-notifications.programmes') }}";
    const technologiesUrl = "{{ route('admin.popup-notifications.technologies') }}";
    const batchesUrl = "{{ route('admin.popup-notifications.batches') }}";

    const showUrlTpl = "{{ route('admin.popup-notifications.show', ['popupNotification' => ':id']) }}";
    const updateUrlTpl = "{{ route('admin.popup-notifications.update', ['popupNotification' => ':id']) }}";
    const deleteUrlTpl = "{{ route('admin.popup-notifications.destroy', ['popupNotification' => ':id']) }}";

    initChoices();

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

    function nl2brHtml(value) {
      return escapeHtml(value ?? '').replace(/\n/g, '<br>');
    }

    function encodeText(value) {
      return encodeURIComponent(value ?? '');
    }

    function decodeText(value) {
      try {
        return decodeURIComponent(value || '');
      } catch (e) {
        return value || '';
      }
    }

    function initChoices() {
      addSessionChoices = new Choices('#session_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      addCenterChoices = new Choices('#center_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      addProgrammeChoices = new Choices('#programme_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      addTechnologyChoices = new Choices('#technology_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      addBatchChoices = new Choices('#batch_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });

      editSessionChoices = new Choices('#edit_session_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      editCenterChoices = new Choices('#edit_center_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      editProgrammeChoices = new Choices('#edit_programme_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      editTechnologyChoices = new Choices('#edit_technology_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
      editBatchChoices = new Choices('#edit_batch_ids', {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: ''
      });
    }

    function getChoiceValues(instance) {
      let values = instance.getValue(true);
      if (Array.isArray(values)) return values.map(String);
      return values ? [String(values)] : [];
    }

    function setSelectedValues(instance, values = []) {
      instance.removeActiveItems();
      if (values.length) {
        instance.setChoiceByValue(values.map(String));
      }
    }

    function clearChoice(instance) {
      instance.clearChoices();
      instance.removeActiveItems();
    }

    function fillChoice(instance, items, valueKey, textKey, selectedValues = []) {
      instance.clearChoices();
      instance.setChoices(
        (items || []).map(item => ({
          value: String(item[valueKey]),
          label: item[textKey],
          selected: selectedValues.map(String).includes(String(item[valueKey]))
        })),
        'value',
        'label',
        true
      );
    }

    function clearErrors(prefix = '') {
      $(`small[id^="${prefix}"][id$="_error"]`).text('');
    }

    function showValidationErrors(errors, prefix = '') {
      $.each(errors || {}, function(field, messages) {
        const cleanField = field.replace(/\.\d+$/, '');
        $('#' + prefix + cleanField + '_error').text(Array.isArray(messages) ? messages[0] : messages);
      });
    }

    // ye function add karo
    function getStatusBadge(status) {
      const s = (status || '').toLowerCase();

      if (s === 'expired') {
        return '<span class="badge bg-danger">Expired</span>';
      }

      if (s === 'upcoming') {
        return '<span class="badge bg-warning text-dark">Upcoming</span>';
      }

      if (s === 'active') {
        return '<span class="badge bg-success">Active</span>';
      }

      return '<span class="badge bg-secondary">No Schedule</span>';
    }


    function renderRows(rows) {
      if ($.fn.DataTable.isDataTable('#popupNotificationsTable')) {
        $('#popupNotificationsTable').DataTable().clear().destroy();
      }

      let html = '';

      if (!rows || !rows.length) {
        html = `
      <tr>
        <td colspan="13" class="text-center">No records found.</td>
      </tr>
    `;
      } else {
        $.each(rows, function(index, item) {
          const descBtn = item.description ?
            `<button type="button" class="btn btn-sm btn-secondary viewDescriptionBtn"
              data-title="${escapeHtml(item.title || 'Description')}"
              data-description="${encodeText(item.description || '')}">
              <i class="bi bi-eye me-1"></i> View
           </button>` :
            '-';

          const imageHtml = item.image_url ?
            `<a href="${item.image_url}" target="_blank">
              <img src="${item.image_url}" class="popup-thumb" alt="popup-image">
           </a>` :
            '-';

          html += `
        <tr>
          <td>${index + 1}</td>
          <td style="white-space: normal;">${escapeHtml(item.session_names || '-')}</td>
          <td style="white-space: normal;">${escapeHtml(item.center_names || '-')}</td>
          <td style="white-space: normal; min-width: 260px;">
            <div><strong>Programme:</strong> ${escapeHtml(item.programme_names || '-')}</div>
            <div><strong>Technology:</strong> ${escapeHtml(item.technology_names || '-')}</div>
            <div><strong>Batch:</strong> ${escapeHtml(item.batch_names || '-')}</div>
          </td>
          <td>${escapeHtml(item.title || '-')}</td>
          <td>${escapeHtml(item.popup_from_formatted || '-')}</td>
          <td>${escapeHtml(item.popup_to_formatted || '-')}</td>
        
          <td>${getStatusBadge(item.popup_status)}</td>
          <td>${imageHtml}</td>
          <td>${descBtn}</td>
          <td>${escapeHtml(item.created_by || '-')}</td>
          <td>${escapeHtml(item.created_at_formatted || '-')}</td>
          <td>
            <button type="button" class="btn btn-sm btn-warning rounded-circle editPopupBtn"
              style="padding-left:10px; padding-right:10px;"
              data-id="${item.popup_notification_id}">
              <i class="bi bi-pencil-square"></i>
            </button>

            <button type="button" class="btn btn-sm btn-danger rounded-circle deletePopupBtn"
              style="padding-left:10px; padding-right:10px;"
              data-id="${item.popup_notification_id}">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      `;
        });
      }

      $('#popupNotificationsTable tbody').html(html);
      initTable();
    }

    function loadList() {
      $.ajax({
        url: listUrl,
        type: 'GET',
        cache: false,
        success: function(res) {
          renderRows(res.data || []);
        },
        error: function() {
          renderRows([]);
          showToast('error', 'Unable to load popup notifications.');
        }
      });
    }
    async function loadCenters(sessionIds, targetChoice, selectedValues = []) {
      clearChoice(targetChoice);
      if (!sessionIds.length) return;

      const res = await $.ajax({
        url: centersUrl,
        type: 'GET',
        cache: false,
        data: {
          session_ids: sessionIds
        }
      });

      fillChoice(targetChoice, res.centers || [], 'center_id', 'center_name', selectedValues);
    }

    async function loadProgrammes(sessionIds, centerIds, targetChoice, selectedValues = []) {
      clearChoice(targetChoice);
      if (!sessionIds.length || !centerIds.length) return;

      const res = await $.ajax({
        url: programmesUrl,
        type: 'GET',
        cache: false,
        data: {
          session_ids: sessionIds,
          center_ids: centerIds
        }
      });

      fillChoice(targetChoice, res.programmes || [], 'programme_id', 'programme_name', selectedValues);
    }
    async function loadTechnologies(sessionIds, programmeIds, targetChoice, selectedValues = []) {
      clearChoice(targetChoice);
      if (!sessionIds.length || !programmeIds.length) return;

      const res = await $.ajax({
        url: technologiesUrl,
        type: 'GET',
        cache: false,
        data: {
          session_ids: sessionIds,
          programme_ids: programmeIds
        }
      });

      fillChoice(targetChoice, res.technologies || [], 'technology_id', 'technology_name', selectedValues);
    }
    async function loadBatches(sessionIds, programmeIds, technologyIds, targetChoice, selectedValues = []) {
      clearChoice(targetChoice);
      if (!sessionIds.length || !programmeIds.length || !technologyIds.length) return;

      const res = await $.ajax({
        url: batchesUrl,
        type: 'GET',
        cache: false,
        data: {
          session_ids: sessionIds,
          programme_ids: programmeIds,
          technology_ids: technologyIds
        }
      });

      fillChoice(targetChoice, res.batches || [], 'batch_id', 'batch_name', selectedValues);
    }

    function resetAddLower(level) {
      if (level <= 1) clearChoice(addCenterChoices);
      if (level <= 2) clearChoice(addProgrammeChoices);
      if (level <= 3) clearChoice(addTechnologyChoices);
      if (level <= 4) clearChoice(addBatchChoices);
    }

    function resetEditLower(level) {
      if (level <= 1) clearChoice(editCenterChoices);
      if (level <= 2) clearChoice(editProgrammeChoices);
      if (level <= 3) clearChoice(editTechnologyChoices);
      if (level <= 4) clearChoice(editBatchChoices);
    }

    $('#session_ids').on('change', async function() {
      resetAddLower(1);
      await loadCenters(getChoiceValues(addSessionChoices), addCenterChoices);
    });

    $('#center_ids').on('change', async function() {
      resetAddLower(2);
      await loadProgrammes(
        getChoiceValues(addSessionChoices),
        getChoiceValues(addCenterChoices),
        addProgrammeChoices
      );
    });

    $('#programme_ids').on('change', async function() {
      resetAddLower(3);
      await loadTechnologies(
        getChoiceValues(addSessionChoices),
        getChoiceValues(addProgrammeChoices),
        addTechnologyChoices
      );
    });

    $('#technology_ids').on('change', async function() {
      resetAddLower(4);
      await loadBatches(
        getChoiceValues(addSessionChoices),
        getChoiceValues(addProgrammeChoices),
        getChoiceValues(addTechnologyChoices),
        addBatchChoices
      );
    });

    $('#edit_session_ids').on('change', async function() {
      resetEditLower(1);
      await loadCenters(getChoiceValues(editSessionChoices), editCenterChoices);
    });

    $('#edit_center_ids').on('change', async function() {
      resetEditLower(2);
      await loadProgrammes(
        getChoiceValues(editSessionChoices),
        getChoiceValues(editCenterChoices),
        editProgrammeChoices
      );
    });

    $('#edit_programme_ids').on('change', async function() {
      resetEditLower(3);
      await loadTechnologies(
        getChoiceValues(editSessionChoices),
        getChoiceValues(editProgrammeChoices),
        editTechnologyChoices
      );
    });

    $('#edit_technology_ids').on('change', async function() {
      resetEditLower(4);
      await loadBatches(
        getChoiceValues(editSessionChoices),
        getChoiceValues(editProgrammeChoices),
        getChoiceValues(editTechnologyChoices),
        editBatchChoices
      );
    });

    $('#addPopupForm').on('submit', function(e) {
      e.preventDefault();
      clearErrors('');

      const formData = new FormData(this);

      $.ajax({
        url: storeUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: ajaxHeaders(),
        success: function(res) {
          $('#addPopupForm')[0].reset();

          addSessionChoices.removeActiveItems();
          clearChoice(addCenterChoices);
          clearChoice(addProgrammeChoices);
          clearChoice(addTechnologyChoices);
          clearChoice(addBatchChoices);

          addPopupModal.hide();
          showToast('success', res.message || 'Popup notification added successfully.');
          loadList();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            showValidationErrors(xhr.responseJSON?.errors, '');
            showToast('error', 'Please check the required fields.');
            return;
          }

          showToast('error', xhr.responseJSON?.message || 'Unable to save popup notification.');
        }
      });
    });

    $(document).on('click', '.viewDescriptionBtn', function() {
      const title = $(this).data('title') || 'Description';
      const desc = decodeText($(this).attr('data-description'));

      $('#viewDescriptionModal .modal-title').text(title);
      $('#viewDescriptionContent').html(nl2brHtml(desc) || 'No description available.');
      viewDescModal.show();
    });

    $(document).on('click', '.editPopupBtn', async function() {
      clearErrors('edit_');

      const id = $(this).data('id');

      try {
        const res = await $.ajax({
          url: showUrlTpl.replace(':id', id),
          type: 'GET'
        });

        const item = res.data;

        $('#edit_popup_notification_id').val(item.popup_notification_id);
        $('#edit_title').val(item.title || '');
        $('#edit_popup_from').val(item.popup_from || '');
        $('#edit_popup_to').val(item.popup_to || '');
        $('#edit_description').val(item.description || '');

        setSelectedValues(editSessionChoices, (item.session_ids || []).map(String));

        await loadCenters((item.session_ids || []).map(String), editCenterChoices, (item.center_ids || []).map(String));
        await loadProgrammes((item.session_ids || []).map(String), (item.center_ids || []).map(String), editProgrammeChoices, (item.programme_ids || []).map(String));
        await loadTechnologies((item.session_ids || []).map(String), (item.programme_ids || []).map(String), editTechnologyChoices, (item.technology_ids || []).map(String));
        await loadBatches((item.session_ids || []).map(String), (item.programme_ids || []).map(String), (item.technology_ids || []).map(String), editBatchChoices, (item.batch_ids || []).map(String));

        if (item.image_url) {
          $('#currentImagePreview').attr('src', item.image_url);
          $('#currentImageBox').show();
        } else {
          $('#currentImagePreview').attr('src', '');
          $('#currentImageBox').hide();
        }

        editPopupModal.show();
      } catch (e) {
        showToast('error', 'Unable to load popup notification details.');
      }
    });

    $('#editPopupForm').on('submit', function(e) {
      e.preventDefault();
      clearErrors('edit_');

      const id = $('#edit_popup_notification_id').val();
      const formData = new FormData(this);
      formData.append('_method', 'PUT');

      $.ajax({
        url: updateUrlTpl.replace(':id', id),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: ajaxHeaders(),
        success: function(res) {
          editPopupModal.hide();
          showToast('success', res.message || 'Popup notification updated successfully.');
          loadList();
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            showValidationErrors(xhr.responseJSON?.errors, 'edit_');
            showToast('error', 'Please check the required fields.');
            return;
          }

          showToast('error', xhr.responseJSON?.message || 'Unable to update popup notification.');
        }
      });
    });

    $(document).on('click', '.deletePopupBtn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this popup notification.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
          url: deleteUrlTpl.replace(':id', id),
          type: 'POST',
          data: {
            _method: 'DELETE'
          },
          headers: ajaxHeaders(),
          success: function(res) {
            showToast('success', res.message || 'Popup notification deleted successfully.');
            loadList();
          },
          error: function(xhr) {
            showToast('error', xhr.responseJSON?.message || 'Unable to delete popup notification.');
          }
        });
      });
    });

    loadList();
  });
</script>
@endpush