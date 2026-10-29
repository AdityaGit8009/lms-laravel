@extends('Admin.layouts.app')

@section('title', 'Batches')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Batches</span>
        </div>
        <h1>Batches</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBatchModal">
          <i class="bi bi-plus-circle me-1"></i> Add Batch
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 70px;">Sr. No.</th>
      <th>Session</th>
      <th>Programme</th>
      <th>Technology</th>
      <th>Batch Name</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 90px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($batches) && count($batches) > 0){
    foreach($batches as $key => $batch){
    $rows .= '
    <tr id="batch-row-'.$batch->batch_id.'">
      <td>'.($key + 1).'</td>
      <td>'.($batch->session->session_name ?? '-').'</td>
      <td>'.($batch->programme->programme_name ?? '-').'</td>
      <td>'.($batch->technology->technology_name ?? '-').'</td>
      <td class="batch-name">'.$batch->batch_name.'</td>
      <td>'.($batch->created_by ?? '-').'</td>
      <td>'.($batch->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editBatchBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$batch->batch_id.'"
          data-session_id="'.$batch->session_id.'"
          data-programme_id="'.$batch->programme_id.'"
          data-technology_id="'.$batch->technology_id.'"
          data-name="'.$batch->batch_name.'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteBatchBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$batch->batch_id.'">
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
      <td>No courses found.</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td class="text-center">-</td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'batchesTable',
    'tableClass' => 'batch-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addBatchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addBatchForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Batch</h5>
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
            <label class="form-label">Programme</label>
            <select name="programme_id" id="add_programme_id" class="form-select" required>
              <option value="">Select Programme</option>
              @foreach($programmes as $programme)
              <option value="{{ $programme->programme_id }}" data-session="{{ $programme->session_id }}">
                {{ $programme->programme_name }}
              </option>
              @endforeach
            </select>
            <small class="text-danger" id="add_programme_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Technology</label>
            <select name="technology_id" id="add_technology_id" class="form-select" required>
              <option value="">Select Technology</option>
              @foreach($technologies as $technology)
              <option
                value="{{ $technology->technology_id }}"
                data-session="{{ $technology->session_id }}"
                data-programme="{{ $technology->programme_id }}">
                {{ $technology->technology_name }}
              </option>
              @endforeach
            </select>
            <small class="text-danger" id="add_technology_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Batch Name</label>
            <input type="text" name="batch_name" id="add_batch_name" class="form-control" placeholder="Enter batch name" required>
            <small class="text-danger" id="add_batch_name_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Batch</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBatchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editBatchForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Batch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_batch_id">

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
            <label class="form-label">Programme</label>
            <select name="programme_id" id="edit_programme_id" class="form-select" required>
              <option value="">Select Programme</option>
              @foreach($programmes as $programme)
              <option value="{{ $programme->programme_id }}" data-session="{{ $programme->session_id }}">
                {{ $programme->programme_name }}
              </option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_programme_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Technology</label>
            <select name="technology_id" id="edit_technology_id" class="form-select" required>
              <option value="">Select Technology</option>
              @foreach($technologies as $technology)
              <option
                value="{{ $technology->technology_id }}"
                data-session="{{ $technology->session_id }}"
                data-programme="{{ $technology->programme_id }}">
                {{ $technology->technology_name }}
              </option>
              @endforeach
            </select>
            <small class="text-danger" id="edit_technology_id_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Batch Name</label>
            <input type="text" name="batch_name" id="edit_batch_name" class="form-control" required>
            <small class="text-danger" id="edit_batch_name_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Batch</button>
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
    $('#batchesTable').DataTable({
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
        targets: 7
      }]
    });

    const addBatchModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addBatchModal'));
    const editBatchModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editBatchModal'));

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

    function filterProgrammes(sessionSelector, programmeSelector) {
      const selectedSession = $(sessionSelector).val();

      $(programmeSelector + ' option').each(function() {
        const sessionId = $(this).data('session');

        if ($(this).val() === '') {
          $(this).show();
          return;
        }

        if (selectedSession && sessionId == selectedSession) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });

      $(programmeSelector).val('');
    }

    function filterTechnologies(sessionSelector, programmeSelector, technologySelector) {
      const selectedSession = $(sessionSelector).val();
      const selectedProgramme = $(programmeSelector).val();

      $(technologySelector + ' option').each(function() {
        const sessionId = $(this).data('session');
        const programmeId = $(this).data('programme');

        if ($(this).val() === '') {
          $(this).show();
          return;
        }

        if (selectedSession && selectedProgramme && sessionId == selectedSession && programmeId == selectedProgramme) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });

      $(technologySelector).val('');
    }

    $('#add_session_id').on('change', function() {
      filterProgrammes('#add_session_id', '#add_programme_id');
      filterTechnologies('#add_session_id', '#add_programme_id', '#add_technology_id');
    });

    $('#add_programme_id').on('change', function() {
      filterTechnologies('#add_session_id', '#add_programme_id', '#add_technology_id');
    });

    $('#edit_session_id').on('change', function() {
      filterProgrammes('#edit_session_id', '#edit_programme_id');
      filterTechnologies('#edit_session_id', '#edit_programme_id', '#edit_technology_id');
    });

    $('#edit_programme_id').on('change', function() {
      filterTechnologies('#edit_session_id', '#edit_programme_id', '#edit_technology_id');
    });

    $('#addBatchForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_session_id_error').text('');
      $('#add_programme_id_error').text('');
      $('#add_technology_id_error').text('');
      $('#add_batch_name_error').text('');

      $.ajax({
        url: "{{ route('api.admin.batches.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addBatchForm')[0].reset();
          addBatchModal.hide();
          showToast('success', res.message || 'Batch created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_id) {
              $('#add_session_id_error').text(xhr.responseJSON.errors.session_id[0]);
            }
            if (xhr.responseJSON?.errors?.programme_id) {
              $('#add_programme_id_error').text(xhr.responseJSON.errors.programme_id[0]);
            }
            if (xhr.responseJSON?.errors?.technology_id) {
              $('#add_technology_id_error').text(xhr.responseJSON.errors.technology_id[0]);
            }
            if (xhr.responseJSON?.errors?.batch_name) {
              $('#add_batch_name_error').text(xhr.responseJSON.errors.batch_name[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create batch.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editBatchBtn', function() {
      const batchId = $(this).data('id');
      const sessionId = $(this).data('session_id');
      const programmeId = $(this).data('programme_id');
      const technologyId = $(this).data('technology_id');
      const name = $(this).data('name');

      $('#edit_batch_id').val(batchId);
      $('#edit_session_id').val(sessionId).trigger('change');

      setTimeout(function() {
        $('#edit_programme_id').val(programmeId).trigger('change');

        setTimeout(function() {
          $('#edit_technology_id').val(technologyId);
        }, 100);
      }, 100);

      $('#edit_batch_name').val(name);

      $('#edit_session_id_error').text('');
      $('#edit_programme_id_error').text('');
      $('#edit_technology_id_error').text('');
      $('#edit_batch_name_error').text('');

      editBatchModal.show();
    });

    $('#editBatchForm').on('submit', function(e) {
      e.preventDefault();

      const batchId = $('#edit_batch_id').val();

      $('#edit_session_id_error').text('');
      $('#edit_programme_id_error').text('');
      $('#edit_technology_id_error').text('');
      $('#edit_batch_name_error').text('');

      $.ajax({
        url: "{{ url('/api/admin/batches') }}/" + batchId,
        type: "POST",
        data: {
          _method: 'PUT',
          session_id: $('#edit_session_id').val(),
          programme_id: $('#edit_programme_id').val(),
          technology_id: $('#edit_technology_id').val(),
          batch_name: $('#edit_batch_name').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editBatchModal.hide();
          showToast('success', res.message || 'Batch updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.session_id) {
              $('#edit_session_id_error').text(xhr.responseJSON.errors.session_id[0]);
            }
            if (xhr.responseJSON?.errors?.programme_id) {
              $('#edit_programme_id_error').text(xhr.responseJSON.errors.programme_id[0]);
            }
            if (xhr.responseJSON?.errors?.technology_id) {
              $('#edit_technology_id_error').text(xhr.responseJSON.errors.technology_id[0]);
            }
            if (xhr.responseJSON?.errors?.batch_name) {
              $('#edit_batch_name_error').text(xhr.responseJSON.errors.batch_name[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update batch.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteBatchBtn', function() {
      const batchId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this batch.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/batches') }}/" + batchId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Batch deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete batch.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush