@extends('Admin.layouts.app')

@section('title', 'Center Program Name')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Center Program Name</span>
        </div>
        <h1>Center Program Name</h1>
        <p>Manage session-wise programme center assignments.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgrammeCenterModal">
          <i class="bi bi-plus-circle me-1"></i> Add Center Program Name
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 90px;">Sr. No.</th>
      <th>Session</th>
      <th>Programme</th>
      <th>Center</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($programmeCenters) && count($programmeCenters) > 0){
    foreach($programmeCenters as $key => $row){
    $rows .= '
    <tr id="programme-center-row-'.$row->id.'">
      <td>'.($key + 1).'</td>
      <td>'.e($row->session_name).'</td>
      <td>'.e($row->programme_name).'</td>
      <td>'.e($row->center_names).'</td>
      <td>'.e($row->created_by ?? '-').'</td>
      <td>'.e($row->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editProgrammeCenterBtn"
          style="padding-left:10px; padding-right:10px;"
          data-session_id="'.$row->session_id.'"
          data-programme_id="'.$row->programme_id.'"
          data-center_ids="'.e($row->center_ids).'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteProgrammeCenterBtn"
          style="padding-left:10px; padding-right:10px;"
          data-session_id="'.$row->session_id.'"
          data-programme_id="'.$row->programme_id.'">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
    ';
    }
    } else {
    $rows = '
    <tr id="emptyRow">
      <td>1.</td>
      <td class="text-center">No records found.</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'programmeCentersTable',
    'tableClass' => 'programme-center-table',
    'thead' => $thead,
    'rows' => $rows
    ])
  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addProgrammeCenterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addProgrammeCenterForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Center Program Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Session</label>
            <select name="session_id" id="add_session_id" class="form-select" required>
              <option value="">Select Session</option>
              @foreach($sessions as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="add_session_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Programme</label>
            <select name="programme_id" id="add_programme_id" class="form-select" required>
              <option value="">Select Programme</option>
            </select>
            <small class="text-danger" id="add_programme_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Centers</label>
            <select name="center_ids[]" id="add_center_ids" class="form-select" multiple required>
              @foreach($centers as $center)
              <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="add_center_ids_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editProgrammeCenterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editProgrammeCenterForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Edit Center Program Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="old_session_id" id="edit_old_session_id">
          <input type="hidden" name="old_programme_id" id="edit_old_programme_id">

          <div class="mb-3">
            <label class="form-label">Session</label>
            <select name="session_id" id="edit_session_id" class="form-select" required>
              <option value="">Select Session</option>
              @foreach($sessions as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_session_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Programme</label>
            <select name="programme_id" id="edit_programme_id" class="form-select" required>
              <option value="">Select Programme</option>
            </select>
            <small class="text-danger" id="edit_programme_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Centers</label>
            <select name="center_ids[]" id="edit_center_ids" class="form-select" multiple required>
              @foreach($centers as $center)
              <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_center_ids_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<style>
  .choices {
    margin-bottom: 0;
  }

  .choices__inner {
    min-height: 38px;
    border: 1px solid #ced4da;
    border-radius: .375rem;
    background: #fff;
    padding: 4px 8px;
  }

  .choices__list--dropdown,
  .choices__list[aria-expanded] {
    z-index: 9999;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
  $(function() {
    $('#programmeCentersTable').DataTable({
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

    const addModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addProgrammeCenterModal'));
    const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProgrammeCenterModal'));

    const addCenterChoices = new Choices('#add_center_ids', {
      removeItemButton: true,
      searchEnabled: true,
      searchPlaceholderValue: 'Search center...',
      placeholder: true,
      placeholderValue: 'Select centers',
      itemSelectText: '',
      shouldSort: false
    });

    const editCenterChoices = new Choices('#edit_center_ids', {
      removeItemButton: true,
      searchEnabled: true,
      searchPlaceholderValue: 'Search center...',
      placeholder: true,
      placeholderValue: 'Select centers',
      itemSelectText: '',
      shouldSort: false
    });

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true
    });

    function showToast(icon, title) {
      Toast.fire({
        icon,
        title
      });
    }

    function clearAddErrors() {
      $('#add_session_id_error').text('');
      $('#add_programme_id_error').text('');
      $('#add_center_ids_error').text('');
    }

    function clearEditErrors() {
      $('#edit_session_id_error').text('');
      $('#edit_programme_id_error').text('');
      $('#edit_center_ids_error').text('');
    }

    function setFormError(type, field, message) {
      $('#' + type + '_' + field + '_error').text(message);
    }

    function handleAjaxError(xhr, type = 'add') {
      const errors = xhr.responseJSON?.errors || {};
      const message = xhr.responseJSON?.message || 'Something went wrong. Please try again.';

      if (xhr.status === 422) {
        if (errors.session_id) setFormError(type, 'session_id', errors.session_id[0]);
        if (errors.programme_id) setFormError(type, 'programme_id', errors.programme_id[0]);
        if (errors.center_ids) setFormError(type, 'center_ids', errors.center_ids[0]);
        if (errors['center_ids.0']) setFormError(type, 'center_ids', errors['center_ids.0'][0]);

        showToast('error', message);
        return;
      }

      showToast('error', message);
    }

    function fillProgrammeDropdown(selector, programmes, selectedValue = '') {
      let html = '<option value="">Select Programme</option>';

      if (programmes.length > 0) {
        programmes.forEach(function(item) {
          const selected = String(selectedValue) === String(item.programme_id) ? 'selected' : '';
          html += `<option value="${item.programme_id}" ${selected}>${item.programme_name}</option>`;
        });
      }

      $(selector).html(html);
    }

    function fetchProgrammesBySession(sessionId, selector, selectedValue = '') {
      $(selector).html('<option value="">Loading...</option>');

      if (!sessionId) {
        $(selector).html('<option value="">Select Programme</option>');
        return;
      }

      $.ajax({
        url: "{{ route('programme-centers.programmes-by-session') }}",
        type: "GET",
        data: {
          session_id: sessionId
        },
        success: function(res) {
          fillProgrammeDropdown(selector, res.data || [], selectedValue);
        },
        error: function() {
          $(selector).html('<option value="">Select Programme</option>');
          showToast('error', 'Unable to load programmes.');
        }
      });
    }

    $('#add_session_id').on('change', function() {
      fetchProgrammesBySession($(this).val(), '#add_programme_id');
    });

    $('#edit_session_id').on('change', function() {
      fetchProgrammesBySession($(this).val(), '#edit_programme_id');
    });

    $('#addProgrammeCenterForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();

      $.ajax({
        url: "{{ route('programme-centers.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addProgrammeCenterForm')[0].reset();
          $('#add_programme_id').html('<option value="">Select Programme</option>');
          addCenterChoices.removeActiveItems();
          addModal.hide();
          showToast('success', res.message || 'Saved successfully.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          handleAjaxError(xhr, 'add');
        }
      });
    });

    $(document).on('click', '.editProgrammeCenterBtn', function() {
      const sessionId = $(this).data('session_id');
      const programmeId = $(this).data('programme_id');
      const centerIds = ($(this).data('center_ids') + '').split(',').filter(Boolean).map(String);

      $('#edit_old_session_id').val(sessionId);
      $('#edit_old_programme_id').val(programmeId);
      $('#edit_session_id').val(sessionId);

      fetchProgrammesBySession(sessionId, '#edit_programme_id', programmeId);

      editCenterChoices.removeActiveItems();
      editCenterChoices.setChoiceByValue(centerIds);

      clearEditErrors();
      editModal.show();
    });

    $('#editProgrammeCenterForm').on('submit', function(e) {
      e.preventDefault();
      clearEditErrors();

      $.ajax({
        url: "{{ route('programme-centers.update-group') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editModal.hide();
          showToast('success', res.message || 'Updated successfully.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          handleAjaxError(xhr, 'edit');
        }
      });
    });

    $(document).on('click', '.deleteProgrammeCenterBtn', function() {
      const sessionId = $(this).data('session_id');
      const programmeId = $(this).data('programme_id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'All centers mapped for this session and programme will be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('programme-centers.destroy-group') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              session_id: sessionId,
              programme_id: programmeId
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              showToast('error', xhr.responseJSON?.message || 'Unable to delete record.');
            }
          });
        }
      });
    });

    $('#addProgrammeCenterModal').on('hidden.bs.modal', function() {
      $('#addProgrammeCenterForm')[0].reset();
      $('#add_programme_id').html('<option value="">Select Programme</option>');
      clearAddErrors();
      addCenterChoices.removeActiveItems();
    });

    $('#editProgrammeCenterModal').on('hidden.bs.modal', function() {
      $('#editProgrammeCenterForm')[0].reset();
      $('#edit_programme_id').html('<option value="">Select Programme</option>');
      clearEditErrors();
      editCenterChoices.removeActiveItems();
    });
  });
</script>
@endpush