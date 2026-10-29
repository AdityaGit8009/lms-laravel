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
        <p>Manage programmes and center mapping from one page.</p>
      </div>

      <div class="text-end d-flex gap-2 justify-content-end flex-wrap">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgrammeModal">
          <i class="bi bi-plus-circle me-1"></i> Add Programme
        </button>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignCentersModal">
          <i class="bi bi-building-add me-1"></i> Assign Centers To Programme
        </button>
      </div>
    </div>

    @php
    $programmeCenterMap = [];

    if(isset($programmes) && count($programmes) > 0){
    $programmeIds = $programmes->pluck('programme_id')->toArray();

    if(!empty($programmeIds)){
    $mappings = \Illuminate\Support\Facades\DB::table('tbl_programme_centers as pc')
    ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
    ->select(
    'pc.programme_id',
    'pc.session_id',
    \Illuminate\Support\Facades\DB::raw("GROUP_CONCAT(DISTINCT c.center_name ORDER BY c.center_name SEPARATOR ', ') as center_names"),
    \Illuminate\Support\Facades\DB::raw("GROUP_CONCAT(DISTINCT c.center_id ORDER BY c.center_name SEPARATOR ',') as center_ids")
    )
    ->whereIn('pc.programme_id', $programmeIds)
    ->groupBy('pc.programme_id', 'pc.session_id')
    ->get();

    foreach($mappings as $mapping){
    $programmeCenterMap[$mapping->programme_id . '_' . $mapping->session_id] = [
    'center_names' => $mapping->center_names,
    'center_ids' => $mapping->center_ids,
    ];
    }
    }
    }

    $thead = '
    <tr>
      <th style="width: 60px;">Sr. No.</th>
      <th>Session</th>
      <th>Programme Name</th>
      <th>Duration</th>
      <th>Centers</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th>Action Info</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($programmes) && count($programmes) > 0){
    foreach($programmes as $key => $programme){
    $mapKey = $programme->programme_id . '_' . $programme->session_id;
    $centerNames = $programmeCenterMap[$mapKey]['center_names'] ?? '-';
    $centerIds = $programmeCenterMap[$mapKey]['center_ids'] ?? '';

    $isDeleted = !is_null($programme->deleted_at);
    $actionInfo = '-';

    if($isDeleted){
    $actionInfo = 'Deleted by: '.e($programme->deleted_by ?? '-').'<br>Deleted at: '.e(optional($programme->deleted_at)->format('d-m-Y h:i A'));
    }

    $actionButtons = '';

    if($isDeleted){
    $actionButtons .= '
    <button type="button"
      class="btn btn-sm btn-success rounded-circle restoreProgrammeBtn"
      style="padding-left:10px; padding-right:10px;"
      title="Restore Programme"
      data-id="'.$programme->programme_id.'">
      <i class="bi bi-arrow-counterclockwise"></i>
    </button>
    ';
    } else {
    $actionButtons .= '
    <button type="button"
      class="btn btn-sm btn-warning rounded-circle editProgrammeBtn"
      style="padding-left:10px; padding-right:10px;"
      title="Edit Programme"
      data-id="'.$programme->programme_id.'"
      data-session_id="'.$programme->session_id.'"
      data-name="'.e($programme->programme_name).'"
      data-duration="'.e($programme->duration).'"
      data-center_ids="'.e($centerIds).'">
      <i class="bi bi-pencil-square"></i>
    </button>

    <button type="button"
      class="btn btn-sm btn-danger rounded-circle deleteProgrammeBtn"
      style="padding-left:10px; padding-right:10px;"
      title="Delete Programme"
      data-id="'.$programme->programme_id.'"
      data-session_id="'.$programme->session_id.'">
      <i class="bi bi-trash"></i>
    </button>
    ';
    }

    $rowClass = $isDeleted ? 'table-danger' : '';

    $rows .= '
    <tr class="'.$rowClass.'" id="programme-row-'.$programme->programme_id.'">
      <td>'.($key + 1).'</td>
      <td>'.e($programme->session->session_name ?? '-').'</td>
      <td class="programme-name">'.e($programme->programme_name).'</td>
      <td class="programme-duration">'.e($programme->duration).'</td>
      <td class="programme-centers">'.e($centerNames).'</td>
      <td>'.e($programme->created_by ?? '-').'</td>
      <td>'.e($programme->updated_by ?? '-').'</td>
      <td>'.$actionInfo.'</td>
      <td>'.$actionButtons.'</td>
    </tr>
    ';
    }
    } else {
    $rows = '
    <tr id="emptyRow">
      <td colspan="9" class="text-center">No records found.</td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'programmesTable',
    'tableClass' => 'programme-table nowrap',
    'thead' => $thead,
    'rows' => $rows
    ])
  </div>
</main>

<!-- Add Programme Modal -->
<div class="modal fade" id="addProgrammeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addProgrammeForm">
        @csrf
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

<!-- Assign Centers Modal -->
<div class="modal fade" id="assignCentersModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="assignCentersForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Assign Centers To Programme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Session</label>
            <select name="session_id" id="assign_session_id" class="form-select" required>
              <option value="">Select Session</option>
              @foreach($currentSessions as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="assign_session_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Programme</label>
            <select name="programme_id" id="assign_programme_id" class="form-select" required>
              <option value="">Select Programme</option>
            </select>
            <small class="text-danger" id="assign_programme_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Centers</label>
            <select name="center_ids[]" id="assign_center_ids" class="form-select" multiple required>
              @foreach($centers as $center)
              <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="assign_center_ids_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Center Mapping</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Programme Modal -->
<div class="modal fade" id="editProgrammeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editProgrammeForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Edit Programme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="old_session_id" id="edit_old_session_id">
          <input type="hidden" name="old_programme_id" id="edit_old_programme_id">
          <input type="hidden" name="programme_id" id="edit_programme_id">

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

          <div class="mb-3">
            <label class="form-label">Centers</label>
            <select name="center_ids[]" id="edit_center_ids" class="form-select" multiple>
              @foreach($centers as $center)
              <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_center_ids_error"></small>
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

  table.dataTable.nowrap th,
  table.dataTable.nowrap td {
    white-space: nowrap;
  }

  .dataTables_scrollBody {
    overflow-x: auto !important;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
  $(function() {
    $('#programmesTable').DataTable({
      pageLength: 10,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: false,
      scrollX: true,
      scrollCollapse: true,
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
        targets: [7, 8]
      }]
    });

    const addProgrammeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addProgrammeModal'));
    const editProgrammeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProgrammeModal'));
    const assignCentersModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('assignCentersModal'));

    const assignCenterChoices = new Choices('#assign_center_ids', {
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
      timer: 3000,
      timerProgressBar: true
    });

    function showToast(icon, title) {
      Toast.fire({
        icon,
        title
      });
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

    function clearAddProgrammeErrors() {
      $('#add_session_id_error').text('');
      $('#add_programme_name_error').text('');
      $('#add_duration_error').text('');
    }

    function clearAssignErrors() {
      $('#assign_session_id_error').text('');
      $('#assign_programme_id_error').text('');
      $('#assign_center_ids_error').text('');
    }

    function clearEditProgrammeErrors() {
      $('#edit_session_id_error').text('');
      $('#edit_programme_name_error').text('');
      $('#edit_duration_error').text('');
      $('#edit_center_ids_error').text('');
    }

    $('#assign_session_id').on('change', function() {
      fetchProgrammesBySession($(this).val(), '#assign_programme_id');
    });

    $('#addProgrammeForm').on('submit', function(e) {
      e.preventDefault();
      clearAddProgrammeErrors();

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
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          const errors = xhr.responseJSON?.errors || {};
          if (errors.session_id) $('#add_session_id_error').text(errors.session_id[0]);
          if (errors.programme_name) $('#add_programme_name_error').text(errors.programme_name[0]);
          if (errors.duration) $('#add_duration_error').text(errors.duration[0]);
          showToast('error', xhr.responseJSON?.message || 'Unable to create programme.');
        }
      });
    });

    $('#assignCentersForm').on('submit', function(e) {
      e.preventDefault();
      clearAssignErrors();

      $.ajax({
        url: "{{ route('programme-centers.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#assignCentersForm')[0].reset();
          $('#assign_programme_id').html('<option value="">Select Programme</option>');
          assignCenterChoices.removeActiveItems();
          assignCentersModal.hide();
          showToast('success', res.message || 'Centers assigned successfully.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          const errors = xhr.responseJSON?.errors || {};
          if (errors.session_id) $('#assign_session_id_error').text(errors.session_id[0]);
          if (errors.programme_id) $('#assign_programme_id_error').text(errors.programme_id[0]);
          if (errors.center_ids) $('#assign_center_ids_error').text(errors.center_ids[0]);
          if (errors['center_ids.0']) $('#assign_center_ids_error').text(errors['center_ids.0'][0]);
          showToast('error', xhr.responseJSON?.message || 'Unable to assign centers.');
        }
      });
    });

    $(document).on('click', '.editProgrammeBtn', function() {
      const programmeId = $(this).data('id');
      const sessionId = $(this).data('session_id');
      const name = $(this).data('name');
      const duration = $(this).data('duration');
      const centerIds = ($(this).data('center_ids') + '').split(',').filter(Boolean).map(String);

      $('#edit_programme_id').val(programmeId);
      $('#edit_old_programme_id').val(programmeId);
      $('#edit_old_session_id').val(sessionId);
      $('#edit_session_id').val(sessionId);
      $('#edit_programme_name').val(name);
      $('#edit_duration').val(duration);

      editCenterChoices.removeActiveItems();
      editCenterChoices.setChoiceByValue(centerIds);

      clearEditProgrammeErrors();
      editProgrammeModal.show();
    });

    $('#editProgrammeForm').on('submit', function(e) {
      e.preventDefault();
      clearEditProgrammeErrors();

      const programmeId = $('#edit_programme_id').val();
      const selectedCenters = $('#edit_center_ids').val() || [];
      const oldSessionId = $('#edit_old_session_id').val();
      const oldProgrammeId = $('#edit_old_programme_id').val();
      const newSessionId = $('#edit_session_id').val();

      $.ajax({
        url: "{{ route('api.admin.programmes.update', ':id') }}".replace(':id', programmeId),
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
          $.ajax({
            url: "{{ route('programme-centers.update-group') }}",
            type: "POST",
            data: $.param({
              _token: "{{ csrf_token() }}",
              old_session_id: oldSessionId,
              old_programme_id: oldProgrammeId,
              session_id: newSessionId,
              programme_id: programmeId
            }) + '&' + $.param({
              'center_ids[]': selectedCenters
            }, true),
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            success: function(mappingRes) {
              editProgrammeModal.hide();
              showToast('success', mappingRes.message || 'Programme updated successfully.');
              setTimeout(() => location.reload(), 700);
            },
            error: function(xhr) {
              const errors = xhr.responseJSON?.errors || {};

              if (errors.center_ids) {
                $('#edit_center_ids_error').text(errors.center_ids[0]);
              }
              if (errors['center_ids.0']) {
                $('#edit_center_ids_error').text(errors['center_ids.0'][0]);
              }

              showToast('error', xhr.responseJSON?.message || 'Programme updated but center mapping failed.');
            }
          });
        },
        error: function(xhr) {
          const errors = xhr.responseJSON?.errors || {};

          if (errors.session_id) $('#edit_session_id_error').text(errors.session_id[0]);
          if (errors.programme_name) $('#edit_programme_name_error').text(errors.programme_name[0]);
          if (errors.duration) $('#edit_duration_error').text(errors.duration[0]);

          showToast('error', xhr.responseJSON?.message || 'Unable to update programme.');
        }
      });
    });
    $(document).on('click', '.deleteProgrammeBtn', function() {
      const programmeId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'Programme will be soft deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('api.admin.programmes.destroy', ':id') }}".replace(':id', programmeId),
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
              showToast('error', xhr.responseJSON?.message || 'Unable to delete programme.');
            }
          });
        }
      });
    });

    $(document).on('click', '.restoreProgrammeBtn', function() {
      const programmeId = $(this).data('id');

      $.ajax({
        url: "{{ route('api.admin.programmes.restore', ':id') }}".replace(':id', programmeId),
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Programme restored successfully.');
          setTimeout(() => location.reload(), 500);
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Unable to restore programme.');
        }
      });
    });

    $('#assignCentersModal').on('hidden.bs.modal', function() {
      $('#assignCentersForm')[0].reset();
      $('#assign_programme_id').html('<option value="">Select Programme</option>');
      clearAssignErrors();
      assignCenterChoices.removeActiveItems();
    });

    $('#editProgrammeModal').on('hidden.bs.modal', function() {
      $('#editProgrammeForm')[0].reset();
      clearEditProgrammeErrors();
      editCenterChoices.removeActiveItems();
    });
  });
</script>
@endpush