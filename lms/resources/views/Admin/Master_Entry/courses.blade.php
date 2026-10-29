@extends('Admin.layouts.app')

@section('title', 'Courses')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Courses</span>
        </div>
        <h1>Courses</h1>
        <p>Manage all courses and their duration details for academic operations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
          <i class="bi bi-plus-circle me-1"></i> Add Course
        </button>
      </div>
    </div>

    @php
    $thead = '
    <tr>
      <th style="width: 90px;">Sr. No.</th>
      <th>Course Name</th>
      <th>Duration (in Years)</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 190px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($courses) && count($courses) > 0){
    foreach($courses as $key => $course){
    $rows .= '
    <tr id="course-row-'.$course->course_id.'">
      <td>'.($key + 1).'</td>
      <td class="course-name">'.e($course->course_name).'</td>
      <td class="course-duration">'.e($course->duration).'</td>
      <td>'.e($course->created_by ?? '-').'</td>
      <td>'.e($course->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editCourseBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$course->course_id.'"
          data-name="'.e($course->course_name).'"
          data-duration="'.e($course->duration).'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteCourseBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$course->course_id.'">
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
      <td class="text-center">No courses found.</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'coursesTable',
    'tableClass' => 'course-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addCourseForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="course_name" id="add_course_name" class="form-control" placeholder="Enter course name" required>
            <small class="text-danger" id="add_course_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Duration (in Years)</label>
            <input type="number" name="duration" id="add_duration" class="form-control" placeholder="Enter duration in years" min="1" required>
            <small class="text-danger" id="add_duration_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editCourseForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_course_id">

          <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="course_name" id="edit_course_name" class="form-control" required>
            <small class="text-danger" id="edit_course_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Duration (in Years)</label>
            <input type="number" name="duration" id="edit_duration" class="form-control" placeholder="Enter duration in years" min="1" required>
            <small class="text-danger" id="edit_duration_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Course</button>
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
    $('#coursesTable').DataTable({
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

    const addCourseModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addCourseModal'));
    const editCourseModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editCourseModal'));

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

    function clearAddErrors() {
      $('#add_course_name_error').text('');
      $('#add_duration_error').text('');
    }

    function clearEditErrors() {
      $('#edit_course_name_error').text('');
      $('#edit_duration_error').text('');
    }

    $('#addCourseForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();

      $.ajax({
        url: "{{ route('api.admin.courses.store') }}",
        type: "POST",
        data: $(this).serialize(),
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addCourseForm')[0].reset();
          addCourseModal.hide();
          showToast('success', res.message || 'Course created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.course_name) {
              $('#add_course_name_error').text(xhr.responseJSON.errors.course_name[0]);
            }
            if (xhr.responseJSON?.errors?.duration) {
              $('#add_duration_error').text(xhr.responseJSON.errors.duration[0]);
            }
            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create course.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editCourseBtn', function() {
      $('#edit_course_id').val($(this).data('id'));
      $('#edit_course_name').val($(this).data('name'));
      $('#edit_duration').val($(this).data('duration'));
      clearEditErrors();
      editCourseModal.show();
    });

    $('#editCourseForm').on('submit', function(e) {
      e.preventDefault();

      const courseId = $('#edit_course_id').val();
      clearEditErrors();

      $.ajax({
        url: "{{ route('api.admin.courses.update', ':id') }}".replace(':id', courseId),
        type: "POST",
        data: {
          _method: 'PUT',
          course_name: $('#edit_course_name').val(),
          duration: $('#edit_duration').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editCourseModal.hide();
          showToast('success', res.message || 'Course updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.course_name) {
              $('#edit_course_name_error').text(xhr.responseJSON.errors.course_name[0]);
            }
            if (xhr.responseJSON?.errors?.duration) {
              $('#edit_duration_error').text(xhr.responseJSON.errors.duration[0]);
            }
            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update course.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.deleteCourseBtn', function() {
      const courseId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this course.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('api.admin.courses.destroy', ':id') }}".replace(':id', courseId),
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Course deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete course.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush