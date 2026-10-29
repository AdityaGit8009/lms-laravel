@extends('Admin.layouts.app')

@section('title', 'Centers')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Centers</span>
        </div>
        <h1>Centers</h1>
        <p>Manage all center locations and geo-radius configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCenterModal">
          <i class="bi bi-plus-circle me-1"></i> Add Center
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width:50px;">Sr. No.</th>
      <th style="width:120px;">Center Name</th>
      <th>Longitude</th>
      <th>Latitude</th>
      <th>Location</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width:90px;">Action</th>
    </tr>
    ';

    $rows = '';

    if(isset($centers) && count($centers) > 0){
    foreach($centers as $key => $center){
    $mapUrl = "https://www.google.com/maps?q=".$center->latitude.",".$center->longitude;

    $rows .= '
    <tr id="center-row-'.$center->center_id.'">
      <td>'.($key + 1).'</td>
      <td class="center-name">'.e($center->center_name).'</td>
      <td>'.e($center->longitude).'</td>
      <td>'.e($center->latitude).'</td>
      <td>
        <a href="'.$mapUrl.'" target="_blank" class="btn btn-sm btn-info text-white">
          <i class="bi bi-geo-alt-fill me-1"></i> View Location
        </a>
        <div class="small text-muted mt-1">Radius: '.e($center->radius).' m</div>
      </td>
      <td>'.e($center->created_by ?? '-').'</td>
      <td>'.e($center->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editCenterBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$center->center_id.'"
          data-name="'.e($center->center_name).'"
          data-latitude="'.e($center->latitude).'"
          data-longitude="'.e($center->longitude).'"
          data-radius="'.e($center->radius).'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteCenterBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$center->center_id.'">
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
    'tableId' => 'centersTable',
    'tableClass' => 'center-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addCenterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addCenterForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Center</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Center Name</label>
            <input type="text" name="center_name" id="add_center_name" class="form-control" placeholder="Enter center name" required>
            <small class="text-danger" id="add_center_name_error"></small>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Latitude</label>
              <input type="text" name="latitude" id="add_latitude" class="form-control" placeholder="Enter latitude" required>
              <small class="text-danger" id="add_latitude_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Longitude</label>
              <input type="text" name="longitude" id="add_longitude" class="form-control" placeholder="Enter longitude" required>
              <small class="text-danger" id="add_longitude_error"></small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Radius (in meter)</label>
            <input type="number" name="radius" id="add_radius" class="form-control" placeholder="Enter radius in meter" min="1" required>
            <small class="text-danger" id="add_radius_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Center</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCenterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editCenterForm">
        @csrf
        <input type="hidden" id="edit_center_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit Center</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Center Name</label>
            <input type="text" name="center_name" id="edit_center_name" class="form-control" required>
            <small class="text-danger" id="edit_center_name_error"></small>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Latitude</label>
              <input type="text" name="latitude" id="edit_latitude" class="form-control" required>
              <small class="text-danger" id="edit_latitude_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Longitude</label>
              <input type="text" name="longitude" id="edit_longitude" class="form-control" required>
              <small class="text-danger" id="edit_longitude_error"></small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Radius (in meter)</label>
            <input type="number" name="radius" id="edit_radius" class="form-control" min="1" required>
            <small class="text-danger" id="edit_radius_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Center</button>
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
    $('#centersTable').DataTable({
      pageLength: 10,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
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
        targets: 7
      }]
    });

    const addCenterModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addCenterModal'));
    const editCenterModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editCenterModal'));

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

    function clearAddErrors() {
      $('#add_center_name_error').text('');
      $('#add_latitude_error').text('');
      $('#add_longitude_error').text('');
      $('#add_radius_error').text('');
    }

    function clearEditErrors() {
      $('#edit_center_name_error').text('');
      $('#edit_latitude_error').text('');
      $('#edit_longitude_error').text('');
      $('#edit_radius_error').text('');
    }

    $('#addCenterForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();

      $.ajax({
        url: "{{ route('api.admin.centers.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addCenterForm')[0].reset();
          addCenterModal.hide();
          showToast('success', res.message || 'Center created successfully.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.center_name) {
              $('#add_center_name_error').text(xhr.responseJSON.errors.center_name[0]);
            }
            if (xhr.responseJSON?.errors?.latitude) {
              $('#add_latitude_error').text(xhr.responseJSON.errors.latitude[0]);
            }
            if (xhr.responseJSON?.errors?.longitude) {
              $('#add_longitude_error').text(xhr.responseJSON.errors.longitude[0]);
            }
            if (xhr.responseJSON?.errors?.radius) {
              $('#add_radius_error').text(xhr.responseJSON.errors.radius[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          showToast('error', xhr.responseJSON?.message || 'Unable to create center.');
        }
      });
    });

    $(document).on('click', '.editCenterBtn', function() {
      clearEditErrors();

      $('#edit_center_id').val($(this).data('id'));
      $('#edit_center_name').val($(this).data('name'));
      $('#edit_latitude').val($(this).data('latitude'));
      $('#edit_longitude').val($(this).data('longitude'));
      $('#edit_radius').val($(this).data('radius'));

      editCenterModal.show();
    });

    $('#editCenterForm').on('submit', function(e) {
      e.preventDefault();

      clearEditErrors();

      let centerId = $('#edit_center_id').val();
      let formData = $(this).serialize() + '&_method=PUT';

      $.ajax({
        url: "{{ url('/api/admin/centers') }}/" + centerId,
        type: "POST",
        data: formData,
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editCenterModal.hide();
          showToast('success', res.message || 'Center updated successfully.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.center_name) {
              $('#edit_center_name_error').text(xhr.responseJSON.errors.center_name[0]);
            }
            if (xhr.responseJSON?.errors?.latitude) {
              $('#edit_latitude_error').text(xhr.responseJSON.errors.latitude[0]);
            }
            if (xhr.responseJSON?.errors?.longitude) {
              $('#edit_longitude_error').text(xhr.responseJSON.errors.longitude[0]);
            }
            if (xhr.responseJSON?.errors?.radius) {
              $('#edit_radius_error').text(xhr.responseJSON.errors.radius[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          showToast('error', xhr.responseJSON?.message || 'Unable to update center.');
        }
      });
    });

    $(document).on('click', '.deleteCenterBtn', function() {
      let centerId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this center.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/centers') }}/" + centerId,
            type: "POST",
            data: {
              _method: 'DELETE',
              _token: "{{ csrf_token() }}"
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Center deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              showToast('error', xhr.responseJSON?.message || 'Unable to delete center.');
            }
          });
        }
      });
    });
  });
</script>
@endpush