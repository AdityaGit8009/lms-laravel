@extends('Admin.layouts.app')

@section('title', 'Manage Feedback Forms')

@section('content')
<main class="main-content">
  <div class="page-body p-3">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="mb-1 h4">View & Manage Feedback Forms</h1>
            <p class="text-muted mb-0">Create, copy, update, delete and review analytics.</p>
          </div>
          <a href="{{ route('admin.feedback.create') }}" class="btn btn-orange">Create New</a>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle nowrap w-100" id="feedbackTable">
            <thead>
              <tr>
                <th>Sr. No.</th>
                <th>Title</th>
                <th>Session</th>
                <th>Center</th>
                <th>Programme / Technology / Batch</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($forms as $index => $form)
              <tr>
                <td>{{ $index + 1 }}</td>

                <td style="min-width:220px;">
                  {{ $form->title }}
                </td>

                <td style="min-width:160px;">
                  <div class="scope-single">
                    {{ $form->session_names }}
                  </div>
                </td>

                <td style="min-width:180px;">
                  <div class="scope-single">
                    {{ $form->center_names }}
                  </div>
                </td>

                <td style="min-width:300px;">
                  <div class="scope-box">
                    <div class="scope-row">
                      <span class="scope-label">Programme:</span>
                      <span class="scope-value">{{ $form->programme_names }}</span>
                    </div>
                    <div class="scope-row">
                      <span class="scope-label">Technology:</span>
                      <span class="scope-value">{{ $form->technology_names }}</span>
                    </div>
                    <div class="scope-row">
                      <span class="scope-label">Batch:</span>
                      <span class="scope-value">{{ $form->batch_names }}</span>
                    </div>
                  </div>
                </td>

                <td>
                  <span class="badge {{ $form->form_status === 'open' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($form->form_status) }}
                  </span>
                </td>

                <td style="min-width:260px;">
                  <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                      class="btn btn-sm btn-outline-primary view-edit-form-btn"
                      data-form-id="{{ $form->form_id }}">
                      View & Edit
                    </button>

                    <form action="{{ route('admin.feedback.copy', $form->form_id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-info">Copy</button>
                    </form>

                    <form action="{{ route('admin.feedback.delete', $form->form_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this form?')">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</main>
<div class="modal fade" id="viewEditFormModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-1">Feedback Form Details</h5>
          <div class="text-muted small">View complete form and questions</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body bg-light" id="viewEditFormModalBody">
        <div class="text-center py-5 text-muted">Loading form details...</div>
      </div>

      <div class="modal-footer">
        <a href="#" id="modalEditFormBtn" class="btn btn-orange">Edit Form</a>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap5.min.css">

<style>
  .btn-orange {
    background: #ef6c2f;
    border-color: #ef6c2f;
    color: #fff;
  }

  .btn-orange:hover {
    background: #d95b1f;
    border-color: #d95b1f;
    color: #fff;
  }

  .scope-single {
    background: #f8f9fb;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 12px;
    color: #495057;
    font-weight: 500;
    line-height: 1.5;
  }

  .scope-box {
    background: #f8f9fb;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 12px;
  }

  .scope-row {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
    margin-bottom: 4px;
  }

  .scope-row:last-child {
    margin-bottom: 0;
  }

  .scope-label {
    min-width: 90px;
    font-weight: 700;
    color: #212529;
  }

  .scope-value {
    color: #495057;
    word-break: break-word;
  }

  .dt-buttons .btn {
    border-radius: 8px !important;
    padding: 6px 12px !important;
    font-size: 13px !important;
    font-weight: 500;
  }

  .dataTables_filter input {
    border-radius: 8px !important;
    border: 1px solid #d7d7d7 !important;
    box-shadow: none !important;
    margin-left: 8px !important;
    padding: 6px 10px !important;
  }

  .dataTables_length select {
    border-radius: 8px !important;
    border: 1px solid #d7d7d7 !important;
    box-shadow: none !important;
    padding: 4px 8px !important;
  }

  .dt-button-collection {
    border-radius: 12px !important;
    overflow: hidden;
  }

  /*  */

  .custom-dt-toolbar {
    row-gap: 12px;
  }

  .dt-length-wrap,
  .dt-search-wrap,
  .dt-buttons-wrap {
    display: flex;
    align-items: center;
  }

  .dt-length-wrap {
    justify-content: flex-start;
  }

  .dt-buttons-wrap {
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
  }

  .dt-search-wrap {
    justify-content: flex-end;
  }

  .dataTables_length label,
  .dataTables_filter label {
    font-size: 14px;
    color: #8a94a6;
    font-weight: 500;
    margin-bottom: 0;
  }

  .dataTables_length select {
    min-width: 72px;
    height: 25px;
    border-radius: 12px !important;
    border: 1px solid #d8dee8 !important;
    box-shadow: none !important;
    padding: 6px 30px 6px 12px !important;
    margin: 0 6px;
    background-color: #f8fafc !important;
    color: #3c4858 !important;
  }

  .dataTables_filter input {
    width: 150px !important;
    height: 38px;
    border-radius: 12px !important;
    border: 1px solid #d8dee8 !important;
    box-shadow: none !important;
    padding: 6px 12px !important;
    margin-left: 8px !important;
    background-color: #f8fafc !important;
  }

  .dt-buttons {
    display: flex !important;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
  }

  .dt-btn {
    border: none !important;
    border-radius: 14px !important;
    padding: 10px 18px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    line-height: 1 !important;
    box-shadow: none !important;
    transition: all 0.2s ease;
  }

  .dt-btn:hover {
    transform: translateY(-1px);
    opacity: 0.95;
  }

  .dt-btn-copy {
    background: #6c757d !important;
    color: #fff !important;
  }

  .dt-btn-csv {
    background: #d9f7ef !important;
    color: #11a36a !important;
    border: 1px solid #a8ebd2 !important;
  }

  .dt-btn-excel {
    background: #dff7ea !important;
    color: #19a55b !important;
    border: 1px solid #b8ebcd !important;
  }

  .dt-btn-pdf {
    background: #fdecef !important;
    color: #eb4d67 !important;
    border: 1px solid #f7c6d0 !important;
  }

  .dt-btn-print {
    background: #212529 !important;
    color: #fff !important;
  }

  .dt-btn-colvis {
    background: #6366f1 !important;
    color: #fff !important;
  }

  .dt-button-collection {
    border: none !important;
    border-radius: 14px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .12) !important;
    overflow: hidden;
    padding: 8px !important;
  }

  .dt-button-collection .dropdown-item,
  .dt-button-collection .dt-button {
    border-radius: 10px !important;
    margin-bottom: 4px !important;
  }

  @media (max-width: 991px) {

    .dt-length-wrap,
    .dt-search-wrap,
    .dt-buttons-wrap {
      justify-content: flex-start !important;
    }

    .dt-search-wrap {
      margin-top: 8px;
    }
  }

  .table-responsive {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
  }

  #feedbackTable {
    width: 100% !important;
    min-width: 1600px;
    white-space: nowrap;
  }

  #feedbackTable thead th,
  #feedbackTable tbody td {
    white-space: nowrap;
    vertical-align: middle;
  }

  #feedbackTable tbody td:last-child {
    white-space: normal;
  }

  .scope-box,
  .scope-single {
    min-width: 160px;
  }

  .scope-box {
    white-space: normal !important;
  }

  .scope-value {
    white-space: normal !important;
  }

  .dataTables_scrollBody {
    overflow-x: auto !important;
  }

  .form-detail-card {
    border: 1px solid #e9ecef;
    border-radius: 16px;
    background: #fff;
    box-shadow: none;
  }

  .form-detail-card .card-body {
    padding: 18px;
  }

  .form-meta-box {
    background: #f8f9fb;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 12px 14px;
    height: 100%;
  }

  .form-meta-label {
    font-size: 12px;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    margin-bottom: 4px;
  }

  .form-meta-value {
    font-size: 14px;
    color: #212529;
    font-weight: 500;
    line-height: 1.5;
  }

  .question-preview-card {
    border: 1px solid #e9ecef;
    border-radius: 14px;
    background: #fff;
  }

  .question-preview-card .card-body {
    padding: 16px 18px;
  }

  .question-preview-title {
    font-size: 15px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 8px;
  }

  .question-preview-type {
    display: inline-block;
    background: #eef2ff;
    color: #4f46e5;
    font-size: 12px;
    font-weight: 600;
    border-radius: 999px;
    padding: 4px 10px;
    margin-bottom: 10px;
  }

  .required-badge {
    display: inline-block;
    background: #fff3cd;
    color: #856404;
    font-size: 12px;
    font-weight: 600;
    border-radius: 999px;
    padding: 4px 10px;
    margin-left: 8px;
  }

  .option-pill {
    display: inline-block;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
    font-size: 13px;
    border-radius: 999px;
    padding: 6px 12px;
    margin: 0 8px 8px 0;
  }

  .preview-answer-box {
    border: 1px dashed #ced4da;
    border-radius: 10px;
    padding: 10px 12px;
    color: #6c757d;
    background: #fcfcfd;
    font-size: 14px;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script>
  $(function() {
    $('#feedbackTable').DataTable({
      pageLength: 10,
      ordering: true,
      autoWidth: false,
      fixedHeader: true,
      scrollX: true,
      responsive: false,
      order: [
        [0, 'asc']
      ],
      dom: "<'custom-dt-toolbar row align-items-center mb-3'<'col-md-3 dt-length-wrap'l><'col-md-6 dt-buttons-wrap text-center'B><'col-md-3 dt-search-wrap'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
      buttons: [{
          extend: 'copyHtml5',
          text: 'Copy',
          className: 'dt-btn dt-btn-copy',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'csvHtml5',
          text: 'CSV',
          className: 'dt-btn dt-btn-csv',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'excelHtml5',
          text: 'Excel',
          className: 'dt-btn dt-btn-excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF',
          className: 'dt-btn dt-btn-pdf',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          },
          orientation: 'landscape',
          pageSize: 'A4',
          title: 'Manage Feedback Forms'
        },
        {
          extend: 'print',
          text: 'Print',
          className: 'dt-btn dt-btn-print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5]
          },
          title: 'Manage Feedback Forms'
        },
        {
          extend: 'colvis',
          text: 'Column visibility',
          className: 'dt-btn dt-btn-colvis'
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: [6]
      }],
      language: {
        search: "Search:",
        lengthMenu: "Show _MENU_ entries"
      }
    });
  });

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text ?? '';
    return div.innerHTML;
  }

  function questionPreviewHtml(question, index) {
    const qText = question.text || 'Question';
    const qType = question.type || 'short_answer';
    const required = question.required ? '<span class="required-badge">Required</span>' : '';
    const options = Array.isArray(question.options) ? question.options : [];

    let answerHtml = '';

    if (qType === 'short_answer') {
      answerHtml = `<div class="preview-answer-box">Short answer text</div>`;
    } else if (qType === 'paragraph') {
      answerHtml = `<div class="preview-answer-box">Long answer text</div>`;
    } else if (qType === 'dropdown') {
      answerHtml = options.length ?
        options.map(opt => `<span class="option-pill">${escapeHtml(opt)}</span>`).join('') :
        `<div class="text-muted">No options added.</div>`;
    } else if (qType === 'radio') {
      answerHtml = options.length ?
        options.map(opt => `<span class="option-pill">${escapeHtml(opt)}</span>`).join('') :
        `<div class="text-muted">No options added.</div>`;
    } else if (qType === 'checkbox') {
      answerHtml = options.length ?
        options.map(opt => `<span class="option-pill">${escapeHtml(opt)}</span>`).join('') :
        `<div class="text-muted">No options added.</div>`;
    } else if (qType === 'rating') {
      answerHtml = `<div class="preview-answer-box">★★★★★</div>`;
    } else {
      answerHtml = `<div class="preview-answer-box">Preview not available</div>`;
    }

    return `
      <div class="question-preview-card mb-3">
        <div class="card-body">
          <div class="question-preview-title">
            ${index + 1}. ${escapeHtml(qText)}
            ${required}
          </div>
          <div class="question-preview-type">${escapeHtml(qType.replaceAll('_', ' '))}</div>
          <div>${answerHtml}</div>
        </div>
      </div>
    `;
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text ?? '';
    return div.innerHTML;
  }

  function questionPreviewHtml(question, index) {
    const qText = question.text || 'Question';
    const qType = question.type || 'short_answer';
    const required = question.required ? '<span class="required-badge">Required</span>' : '';
    const options = Array.isArray(question.options) ? question.options : [];

    let answerHtml = '';

    if (qType === 'short_answer') {
      answerHtml = `<div class="preview-answer-box">Short answer text</div>`;
    } else if (qType === 'paragraph') {
      answerHtml = `<div class="preview-answer-box">Long answer text</div>`;
    } else if (['dropdown', 'radio', 'checkbox'].includes(qType)) {
      answerHtml = options.length ?
        options.map(opt => `<span class="option-pill">${escapeHtml(opt)}</span>`).join('') :
        `<div class="text-muted">No options added.</div>`;
    } else if (qType === 'rating') {
      answerHtml = `<div class="preview-answer-box">★★★★★</div>`;
    } else {
      answerHtml = `<div class="preview-answer-box">Preview not available</div>`;
    }

    return `
      <div class="question-preview-card mb-3">
        <div class="card-body">
          <div class="question-preview-title">
            ${index + 1}. ${escapeHtml(qText)}
            ${required}
          </div>
          <div class="question-preview-type">${escapeHtml(qType.replaceAll('_', ' '))}</div>
          <div>${answerHtml}</div>
        </div>
      </div>
    `;
  }

  async function openFormDetailsModal(formId) {
    const modalBody = document.getElementById('viewEditFormModalBody');
    const editBtn = document.getElementById('modalEditFormBtn');

    modalBody.innerHTML = `<div class="text-center py-5 text-muted">Loading form details...</div>`;
    editBtn.href = '#';

    const modal = new bootstrap.Modal(document.getElementById('viewEditFormModal'));
    modal.show();

    try {
      const url = `{{ route('admin.feedback.form.details', ':id') }}`.replace(':id', formId);

      const response = await fetch(url, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const rawText = await response.text();
      let data = {};

      try {
        data = JSON.parse(rawText);
      } catch (e) {
        console.error('Invalid JSON:', rawText);
        modalBody.innerHTML = `<div class="alert alert-danger mb-0">Server returned invalid response.</div>`;
        return;
      }

      if (!response.ok || !data.success || !data.data) {
        console.error('Form detail load failed:', data);
        modalBody.innerHTML = `<div class="alert alert-warning mb-0">${data.message || 'Unable to load form details.'}</div>`;
        return;
      }

      const form = data.data;
      const questions = Array.isArray(form.questions_json) ? form.questions_json : [];

      editBtn.href = `{{ url('/admin/feedback/edit') }}/${form.form_id}`;

      modalBody.innerHTML = `
        <div class="row g-3 mb-4">
          <div class="col-12">
            <div class="form-detail-card">
              <div class="card-body">
                <h3 class="mb-1">${escapeHtml(form.title || 'Untitled Form')}</h3>
                <p class="text-muted mb-0">${escapeHtml(form.description || 'No description available.')}</p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Session</div>
              <div class="form-meta-value">${escapeHtml(form.session_names || '-')}</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Center</div>
              <div class="form-meta-value">${escapeHtml(form.center_names || '-')}</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Status</div>
              <div class="form-meta-value">${escapeHtml(form.form_status || '-')}</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Programme</div>
              <div class="form-meta-value">${escapeHtml(form.programme_names || '-')}</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Technology</div>
              <div class="form-meta-value">${escapeHtml(form.technology_names || '-')}</div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-meta-box">
              <div class="form-meta-label">Batch</div>
              <div class="form-meta-value">${escapeHtml(form.batch_names || '-')}</div>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <h5 class="mb-3">Questions Preview</h5>
          ${
            questions.length
              ? questions.map((q, i) => questionPreviewHtml(q, i)).join('')
              : `<div class="alert alert-info mb-0">No questions found in this form.</div>`
          }
        </div>
      `;
    } catch (error) {
      console.error(error);
      modalBody.innerHTML = `<div class="alert alert-danger mb-0">Something went wrong while loading form details.</div>`;
    }
  }

  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.view-edit-form-btn');
    if (!btn) return;

    const formId = btn.getAttribute('data-form-id');
    openFormDetailsModal(formId);
  });
</script>
@endpush