@extends('Admin.layouts.app')

@section('title', 'Subject Topics')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr">
      <div>
        <div class="breadcrumb-bar">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span>/</span>
          <span>Master Entry</span>
          <span>/</span>
          <a href="{{ route('admin.subjects') }}">Subjects</a>
          <span>/</span>
          <span>View Topics</span>
        </div>
        <h1>Subject Topics</h1>
        <p>Select any subject and fetch its uploaded topics and titles.</p>
      </div>

      <div class="text-end">
        <a href="{{ route('admin.subjects') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-left me-1"></i> Back to Subjects
        </a>
      </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-9">
            <label for="subjectDropdown" class="form-label fw-semibold">Select Subject</label>
            <select id="subjectDropdown" class="form-select">
              <option value="">Select Subject</option>
              @foreach($subjects as $subject)
              <option value="{{ $subject->subject_id }}" {{ (int) $selectedSubjectId === (int) $subject->subject_id ? 'selected' : '' }}>
                {{ $subject->subject_name }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <button type="button" id="fetchTopicsBtn" class="btn btn-primary w-100">
              <i class="bi bi-search me-1"></i> Fetch Topics
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body pb-0">
        <ul class="nav nav-tabs flex-nowrap overflow-auto" id="subjectTabs">
          @forelse($subjects as $subject)
          <li class="nav-item me-2">
            <button type="button"
              class="nav-link subjectTabBtn {{ (int) $selectedSubjectId === (int) $subject->subject_id ? 'active' : '' }}"
              data-subject-id="{{ $subject->subject_id }}">
              {{ $subject->subject_name }}
            </button>
          </li>
          @empty
          <li class="nav-item">
            <span class="nav-link disabled">No Subjects Available</span>
          </li>
          @endforelse
        </ul>
      </div>

      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h5 class="mb-0" id="currentSubjectHeading">Topics List</h5>
          <span class="badge bg-primary fs-6" id="topicCountBadge">0 Records</span>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 80px;">Sr. No.</th>
                <th>Topic</th>
                <th>Title</th>
                <th style="width: 120px;">Action</th>
              </tr>
            </thead>
            <tbody id="topicsTableBody">
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  Select subject and click Fetch Topics.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<div class="modal fade" id="editTopicModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editTopicForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Topic</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_topic_id">
          <input type="hidden" id="edit_subject_id">

          <div class="mb-3">
            <label for="edit_topic_name" class="form-label">Topic</label>
            <input type="text" id="edit_topic_name" name="topic_name" class="form-control" required>
            <small class="text-danger" id="edit_topic_name_error"></small>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function() {
    const dropdown = $('#subjectDropdown');
    const tableBody = $('#topicsTableBody');
    const countBadge = $('#topicCountBadge');
    const heading = $('#currentSubjectHeading');
    const selectedSubjectId = "{{ $selectedSubjectId }}";
    const editTopicModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editTopicModal'));

    const updateUrlTemplate = @json(route('admin.upload-content.topic-title.update', ['id' => '__ID__']));
    const deleteUrlTemplate = @json(route('admin.upload-content.delete', ['id' => '__ID__']));

    let currentRows = [];
    let currentSubjectId = selectedSubjectId || '';

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

    function escapeHtml(text) {
      if (text === null || text === undefined) return '';
      return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function setActiveTab(subjectId) {
      $('.subjectTabBtn').removeClass('active');
      $('.subjectTabBtn[data-subject-id="' + subjectId + '"]').addClass('active');
      dropdown.val(subjectId);
      currentSubjectId = subjectId;
    }

    function renderRows(rows) {
      currentRows = rows || [];

      if (!currentRows.length) {
        tableBody.html(`
          <tr>
            <td colspan="4" class="text-center text-muted py-4">
              No topics found for this subject.
            </td>
          </tr>
        `);
        countBadge.text('0 Records');
        return;
      }

      let html = '';
      currentRows.forEach(function(row) {
        html += `
          <tr>
            <td>${row.sr_no}</td>
            <td>${escapeHtml(row.topic || '-')}</td>
            <td>${escapeHtml(row.title || '-')}</td>
            <td>
              <button type="button"
                class="btn btn-sm btn-warning rounded-circle me-1 editTopicBtn"
                data-id="${row.topic_id}"
                title="Edit"
                style="padding-left:10px; padding-right:10px;">
                <i class="bi bi-pencil-square"></i>
              </button>

              <button type="button"
                class="btn btn-sm btn-danger rounded-circle deleteTopicBtn"
                data-id="${row.topic_id}"
                title="Delete"
                style="padding-left:10px; padding-right:10px;">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        `;
      });

      tableBody.html(html);
      countBadge.text(currentRows.length + ' Records');
    }

    function loadTopics(subjectId) {
      if (!subjectId) {
        tableBody.html(`
          <tr>
            <td colspan="4" class="text-center text-danger py-4">
              Please select a subject first.
            </td>
          </tr>
        `);
        heading.text('Topics List');
        countBadge.text('0 Records');
        currentRows = [];
        return;
      }

      setActiveTab(subjectId);

      tableBody.html(`
        <tr>
          <td colspan="4" class="text-center py-4">
            Loading...
          </td>
        </tr>
      `);

      $.ajax({
        url: "{{ url('/api/admin/subjects') }}/" + subjectId + "/topics-list",
        type: "GET",
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          heading.text(((res.subject && res.subject.subject_name) ? res.subject.subject_name : 'Selected Subject') + ' - Topics');
          renderRows(res.data || []);
        },
        error: function(xhr) {
          heading.text('Topics List');
          countBadge.text('0 Records');
          currentRows = [];
          tableBody.html(`
            <tr>
              <td colspan="4" class="text-center text-danger py-4">
                ${(xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to fetch topics.'}
              </td>
            </tr>
          `);
        }
      });
    }

    dropdown.on('change', function() {
      const subjectId = $(this).val();
      if (subjectId) {
        setActiveTab(subjectId);
      }
    });

    $('#fetchTopicsBtn').on('click', function() {
      loadTopics(dropdown.val());
    });

    $(document).on('click', '.subjectTabBtn', function() {
      const subjectId = $(this).data('subject-id');
      loadTopics(subjectId);
    });

    $(document).on('click', '.editTopicBtn', function() {
      const topicId = $(this).data('id');
      const row = currentRows.find(item => String(item.topic_id) === String(topicId));

      if (!row) {
        showToast('error', 'Topic not found.');
        return;
      }

      $('#edit_topic_id').val(row.topic_id);
      $('#edit_subject_id').val(row.subject_id);
      $('#edit_topic_name').val(row.topic || '');
      $('#edit_topic_name_error').text('');

      editTopicModal.show();
    });

    $('#editTopicForm').on('submit', function(e) {
      e.preventDefault();

      const topicId = $('#edit_topic_id').val();
      const updateUrl = updateUrlTemplate.replace('__ID__', topicId);

      $('#edit_topic_name_error').text('');

      $.ajax({
        url: updateUrl,
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          _method: 'PUT',
          subject_id: $('#edit_subject_id').val(),
          topic_name: $('#edit_topic_name').val()
        },
        headers: {
          'Accept': 'application/json'
        },
        success: function(res) {
          editTopicModal.hide();
          showToast('success', res.message || 'Topic updated successfully.');
          loadTopics(currentSubjectId);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.topic_name) {
              $('#edit_topic_name_error').text(xhr.responseJSON.errors.topic_name[0]);
            }
            showToast('error', xhr.responseJSON?.message || 'Validation error.');
            return;
          }

          showToast('error', xhr.responseJSON?.message || 'Unable to update topic.');
        }
      });
    });

    $(document).on('click', '.deleteTopicBtn', function() {
      const topicId = $(this).data('id');
      const deleteUrl = deleteUrlTemplate.replace('__ID__', topicId);

      Swal.fire({
        title: 'Are you sure?',
        text: 'This topic and its related content will be deleted permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: deleteUrl,
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              _method: 'DELETE'
            },
            headers: {
              'Accept': 'application/json'
            },
            success: function(res) {
              showToast('success', res.message || 'Topic deleted successfully.');
              loadTopics(currentSubjectId);
            },
            error: function(xhr) {
              showToast('error', xhr.responseJSON?.message || 'Unable to delete topic.');
            }
          });
        }
      });
    });

    if (selectedSubjectId) {
      loadTopics(selectedSubjectId);
    }
  });
</script>
@endpush