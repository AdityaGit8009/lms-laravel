@extends('Admin.layouts.app')

@section('title', 'Technologies')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Technologies</span>
        </div>
        <h1>Technologies</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTechnologyModal">
          <i class="bi bi-plus-circle me-1"></i> Add Technology
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width:60px;">Sr. No.</th>
      <th>Session</th>
      <th>Programme</th>
      <th>Technology</th>
      <th>Image</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width:120px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($technologies) && count($technologies) > 0){
    foreach($technologies as $key => $technology){
    $imgUrl = !empty($technology->technology_image)
    ? asset('uploads/technologies/' . $technology->technology_image)
    : asset('images/no-image.png');

    $rows .= '
    <tr id="technology-row-'.$technology->technology_id.'">
      <td>'.($key + 1).'</td>
      <td>'.($technology->session->session_name ?? '-').'</td>
      <td>'.($technology->programme->programme_name ?? '-').'</td>
      <td class="technology-name">'.$technology->technology_name.'</td>
      <td>
        <img src="'.$imgUrl.'" alt="Technology Image" style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
      </td>
      <td>'.($technology->created_by ?? '-').'</td>
      <td>'.($technology->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editTechnologyBtn" style="padding-left:10px; padding-right:10px;"
          data-id="'.$technology->technology_id.'"
          data-session_id="'.$technology->session_id.'"
          data-programme_id="'.$technology->programme_id.'"
          data-name="'.$technology->technology_name.'"
          data-image="'.$technology->technology_image.'">
          <i class=" bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteTechnologyBtn" style="padding-left:10px; padding-right:10px;"
          data-id="'.$technology->technology_id.'">
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
      <td></td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'technologiesTable',
    'tableClass' => 'technology-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addTechnologyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addTechnologyForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Technology</h5>
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
            <label class="form-label">Technology Name</label>
            <input type="text" name="technology_name" id="add_technology_name" class="form-control" placeholder="Enter technology name" required>
            <small class="text-danger" id="add_technology_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="technology_image" id="add_technology_image" class="form-control" accept="image/*">
            <small class="text-danger" id="add_technology_image_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Technology</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editTechnologyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editTechnologyForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Technology</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_technology_id">

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
            <label class="form-label">Technology Name</label>
            <input type="text" name="technology_name" id="edit_technology_name" class="form-control" required>
            <small class="text-danger" id="edit_technology_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="technology_image" id="edit_technology_image" class="form-control" accept="image/*">
            <small class="text-danger" id="edit_technology_image_error"></small>

            <div class="mt-2" id="currentTechnologyImageBox" style="display:none;">
              <img id="currentTechnologyImage" src="" alt="Current Image" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Technology</button>
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
    $('#technologiesTable').DataTable({
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
    const addTechnologyModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addTechnologyModal'));
    const editTechnologyModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editTechnologyModal'));

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

    $('#add_session_id').on('change', function() {
      filterProgrammes('#add_session_id', '#add_programme_id');
    });

    $('#edit_session_id').on('change', function() {
      filterProgrammes('#edit_session_id', '#edit_programme_id');
    });

    $('#addTechnologyForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_session_id_error').text('');
      $('#add_programme_id_error').text('');
      $('#add_technology_name_error').text('');
      $('#add_technology_image_error').text('');

      let formData = new FormData(this);

      $.ajax({
        url: "{{ route('api.admin.technologies.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addTechnologyForm')[0].reset();
          addTechnologyModal.hide();
          showToast('success', res.message || 'Technology created successfully.');
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
            if (xhr.responseJSON?.errors?.technology_name) {
              $('#add_technology_name_error').text(xhr.responseJSON.errors.technology_name[0]);
            }
            if (xhr.responseJSON?.errors?.technology_image) {
              $('#add_technology_image_error').text(xhr.responseJSON.errors.technology_image[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create technology.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editTechnologyBtn', function() {
      const techId = $(this).data('id');
      const sessionId = $(this).data('session_id');
      const programmeId = $(this).data('programme_id');
      const name = $(this).data('name');
      const image = $(this).data('image');

      $('#edit_technology_id').val(techId);
      $('#edit_session_id').val(sessionId).trigger('change');

      setTimeout(function() {
        $('#edit_programme_id').val(programmeId);
      }, 100);

      $('#edit_technology_name').val(name);

      $('#edit_session_id_error').text('');
      $('#edit_programme_id_error').text('');
      $('#edit_technology_name_error').text('');
      $('#edit_technology_image_error').text('');

      if (image) {
        $('#currentTechnologyImage').attr('src', '/uploads/technologies/' + image);
        $('#currentTechnologyImageBox').show();
      } else {
        $('#currentTechnologyImage').attr('src', '');
        $('#currentTechnologyImageBox').hide();
      }

      editTechnologyModal.show();
    });

    $('#editTechnologyForm').on('submit', function(e) {
      e.preventDefault();

      const technologyId = $('#edit_technology_id').val();

      $('#edit_session_id_error').text('');
      $('#edit_programme_id_error').text('');
      $('#edit_technology_name_error').text('');
      $('#edit_technology_image_error').text('');

      let formData = new FormData(this);
      formData.append('_method', 'PUT');

      $.ajax({
        url: "{{ url('/api/admin/technologies') }}/" + technologyId,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editTechnologyModal.hide();
          showToast('success', res.message || 'Technology updated successfully.');
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
            if (xhr.responseJSON?.errors?.technology_name) {
              $('#edit_technology_name_error').text(xhr.responseJSON.errors.technology_name[0]);
            }
            if (xhr.responseJSON?.errors?.technology_image) {
              $('#edit_technology_image_error').text(xhr.responseJSON.errors.technology_image[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update technology.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteTechnologyBtn', function() {
      const technologyId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this technology.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/technologies') }}/" + technologyId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Technology deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete technology.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush