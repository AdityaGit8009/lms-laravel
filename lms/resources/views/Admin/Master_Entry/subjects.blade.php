@extends('Admin.layouts.app')

@section('title', 'Subjects')

@section('content')

<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span><span>/</span>
          <span>Subjects</span>
        </div>
        <h1>Subjects</h1>
        <p>Manage all master data like categories, batches, roles and configurations.</p>
      </div>

      <div class="text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
          <i class="bi bi-plus-circle me-1"></i> Add Subject
        </button>
      </div>
    </div>

    @php
    use Illuminate\Support\Str;

    $thead = '
    <tr>
      <th style="width: 60px;">Sr. No.</th>
      <th>Subject Name</th>
      <th>Image</th>
      <th>Syllabus</th>
      <th>Created By</th>
      <th>Updated By</th>
      <th style="width: 110px;">Action</th>
    </tr>
    ';

    $rows = '';
    if(isset($subjects) && count($subjects) > 0){
    foreach($subjects as $key => $subject){
    $imgUrl = !empty($subject->subject_image)
    ? asset('uploads/subjects/' . $subject->subject_image)
    : asset('images/no-image.png');

    $plainSyllabus = trim(strip_tags($subject->syllabus ?? ''));
    $escapedFullHtml = htmlspecialchars($subject->syllabus ?? '', ENT_QUOTES, 'UTF-8');

    $syllabusHtml = '<span>-</span>';

    if(!empty($plainSyllabus)){
    $syllabusHtml = '
    <button
      type="button"
      class="btn btn-sm btn-secondary text-white viewSyllabusBtn"
      data-subject_name="'.e($subject->subject_name).'"
      data-syllabus="'.$escapedFullHtml.'">
      View Syllabus
    </button>
    ';
    }

    $rows .= '
    <tr id="subject-row-'.$subject->subject_id.'">
      <td>'.($key + 1).'</td>
      <td class="subject-name">'.e($subject->subject_name).'</td>
      <td>
        <img src="'.$imgUrl.'" alt="Subject Image" style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
      </td>
      <td>'.$syllabusHtml.'</td>
      <td>'.e($subject->created_by ?? '-').'</td>
      <td>'.e($subject->updated_by ?? '-').'</td>
      <td>
        <button type="button"
          class="btn btn-sm btn-warning rounded-circle editSubjectBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$subject->subject_id.'"
          data-name="'.e($subject->subject_name).'"
          data-image="'.e($subject->subject_image ?? '').'"
          data-syllabus="'.$escapedFullHtml.'">
          <i class="bi bi-pencil-square"></i>
        </button>

        <button type="button"
          class="btn btn-sm btn-danger rounded-circle deleteSubjectBtn"
          style="padding-left:10px; padding-right:10px;"
          data-id="'.$subject->subject_id.'">
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
    </tr>
    ';
    }
    @endphp

    @include('Admin.includes.common-datatable', [
    'tableId' => 'subjectsTable',
    'tableClass' => 'subject-table',
    'thead' => $thead,
    'rows' => $rows
    ])

  </div>
</main>

<!-- Add Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addSubjectForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add Subject</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="subject_name" id="add_subject_name" class="form-control" placeholder="Enter subject name" required>
            <small class="text-danger" id="add_subject_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="subject_image" id="add_subject_image" class="form-control" accept="image/*">
            <small class="text-danger" id="add_subject_image_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Syllabus</label>
            <textarea name="syllabus" id="add_syllabus" class="form-control"></textarea>
            <small class="text-danger" id="add_syllabus_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Subject</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editSubjectForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Subject</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_subject_id">

          <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
            <small class="text-danger" id="edit_subject_name_error"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="subject_image" id="edit_subject_image" class="form-control" accept="image/*">
            <small class="text-danger" id="edit_subject_image_error"></small>

            <div class="mt-2" id="currentSubjectImageBox" style="display:none;">
              <img id="currentSubjectImage" src="" alt="Current Image" style="width:70px;height:70px;object-fit:cover;border-radius:6px;border:1px solid #ddd;">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Syllabus</label>
            <textarea name="syllabus" id="edit_syllabus" class="form-control"></textarea>
            <small class="text-danger" id="edit_syllabus_error"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Subject</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Syllabus Modal -->
<div class="modal fade" id="viewSyllabusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewSyllabusTitle">Full Syllabus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="viewSyllabusContent"></div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let addSyllabusEditor, editSyllabusEditor;

  $(function() {
    $('#subjectsTable').DataTable({
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

    const addSubjectModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addSubjectModal'));
    const editSubjectModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editSubjectModal'));
    const viewSyllabusModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewSyllabusModal'));

    ClassicEditor.create(document.querySelector('#add_syllabus'))
      .then(editor => {
        addSyllabusEditor = editor;
      })
      .catch(error => {
        console.error(error);
      });

    ClassicEditor.create(document.querySelector('#edit_syllabus'))
      .then(editor => {
        editSyllabusEditor = editor;
      })
      .catch(error => {
        console.error(error);
      });

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

    $('#addSubjectModal').on('shown.bs.modal', function() {
      if (addSyllabusEditor) {
        addSyllabusEditor.ui.update();
      }
    });

    $('#editSubjectModal').on('shown.bs.modal', function() {
      if (editSyllabusEditor) {
        editSyllabusEditor.ui.update();
      }
    });

    $('#addSubjectForm').on('submit', function(e) {
      e.preventDefault();

      $('#add_subject_name_error').text('');
      $('#add_subject_image_error').text('');
      $('#add_syllabus_error').text('');

      let formData = new FormData(this);
      formData.set('syllabus', addSyllabusEditor ? addSyllabusEditor.getData() : '');

      $.ajax({
        url: "{{ route('api.admin.subjects.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          $('#addSubjectForm')[0].reset();
          if (addSyllabusEditor) {
            addSyllabusEditor.setData('');
          }
          addSubjectModal.hide();
          showToast('success', res.message || 'Subject created successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.subject_name) {
              $('#add_subject_name_error').text(xhr.responseJSON.errors.subject_name[0]);
            }
            if (xhr.responseJSON?.errors?.subject_image) {
              $('#add_subject_image_error').text(xhr.responseJSON.errors.subject_image[0]);
            }
            if (xhr.responseJSON?.errors?.syllabus) {
              $('#add_syllabus_error').text(xhr.responseJSON.errors.syllabus[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to create subject.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.editSubjectBtn', function() {
      const subjectId = $(this).data('id');
      const name = $(this).data('name');
      const image = $(this).data('image');
      const syllabus = $(this).attr('data-syllabus');

      $('#edit_subject_id').val(subjectId);
      $('#edit_subject_name').val(name);

      if (editSyllabusEditor) {
        editSyllabusEditor.setData(syllabus || '');
      }

      $('#edit_subject_name_error').text('');
      $('#edit_subject_image_error').text('');
      $('#edit_syllabus_error').text('');

      if (image) {
        $('#currentSubjectImage').attr('src', '/uploads/subjects/' + image);
        $('#currentSubjectImageBox').show();
      } else {
        $('#currentSubjectImage').attr('src', '');
        $('#currentSubjectImageBox').hide();
      }

      editSubjectModal.show();
    });

    $('#editSubjectForm').on('submit', function(e) {
      e.preventDefault();

      const subjectId = $('#edit_subject_id').val();

      $('#edit_subject_name_error').text('');
      $('#edit_subject_image_error').text('');
      $('#edit_syllabus_error').text('');

      let formData = new FormData(this);
      formData.append('_method', 'PUT');
      formData.set('syllabus', editSyllabusEditor ? editSyllabusEditor.getData() : '');

      $.ajax({
        url: "{{ url('/api/admin/subjects') }}/" + subjectId,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editSubjectModal.hide();
          showToast('success', res.message || 'Subject updated successfully.');
          setTimeout(() => location.reload(), 800);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.subject_name) {
              $('#edit_subject_name_error').text(xhr.responseJSON.errors.subject_name[0]);
            }
            if (xhr.responseJSON?.errors?.subject_image) {
              $('#edit_subject_image_error').text(xhr.responseJSON.errors.subject_image[0]);
            }
            if (xhr.responseJSON?.errors?.syllabus) {
              $('#edit_syllabus_error').text(xhr.responseJSON.errors.syllabus[0]);
            }

            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          let msg = xhr.responseJSON?.message || 'Unable to update subject.';
          showToast('error', msg);
        }
      });
    });

    $(document).on('click', '.viewSyllabusBtn', function() {
      const subjectName = $(this).data('subject_name');
      const syllabus = $(this).attr('data-syllabus');

      $('#viewSyllabusTitle').text(subjectName + ' - Full Syllabus');
      $('#viewSyllabusContent').html(syllabus || '<p>No syllabus available.</p>');
      viewSyllabusModal.show();
    });

    $(document).on('click', '.deleteSubjectBtn', function() {
      const subjectId = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this subject.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ url('/api/admin/subjects') }}/" + subjectId,
            type: "POST",
            data: {
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Subject deleted successfully.');
              setTimeout(() => location.reload(), 500);
            },
            error: function(xhr) {
              let msg = xhr.responseJSON?.message || 'Unable to delete subject.';
              showToast('error', msg);
            }
          });
        }
      });
    });
  });
</script>
@endpush