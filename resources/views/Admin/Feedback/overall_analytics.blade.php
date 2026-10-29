@extends('Admin.layouts.app')

@section('title', 'Overall Feedback Analytics')

@section('content')
<main class="main-content">
  <div class="page-body p-3 analytics-page">
    <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-filter-card">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-2">
            <label class="form-label fw-semibold">Session</label>
            <select id="filter_session_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
              @foreach(($sessions ?? []) as $session)
              <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Center</label>
            <select id="filter_center_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Programme</label>
            <select id="filter_programme_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Technology</label>
            <select id="filter_technology_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Batch</label>
            <select id="filter_batch_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label fw-semibold">Form</label>
            <select id="filter_form_ids" class="form-select analytics-filter-select" multiple>
              <option value="all">All</option>
              @foreach(($forms ?? []) as $form)
              <option value="{{ $form->form_id }}">{{ $form->title }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
          <button type="button" class="btn btn-orange" id="applyAnalyticsFilterBtn">Apply Filter</button>
          <button type="button" class="btn btn-outline-secondary" id="resetAnalyticsFilterBtn">Reset</button>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
          <div>
            <h3 class="mb-1">Student Feedback Status</h3>
            <p class="text-muted mb-0">Submitted and pending student feedback list with detailed analytics.</p>
          </div>

          <button type="button" class="btn btn-orange" id="viewOverallAnalyticsBtn" data-bs-toggle="modal" data-bs-target="#overallAnalyticsModal">
            <i class="fa fa-chart-pie me-1"></i> View Analytics
          </button>
        </div>

        <div class="table-responsive">
          <table class="table align-middle analytics-table" id="studentFeedbackStatusTable">
            <thead>
              <tr>
                <th>Sr. No.</th>
                <th>Student Name</th>
                <th>Mobile Number</th>
                <th>Status</th>
                <th>Feedback</th>
              </tr>
            </thead>
            <tbody id="studentFeedbackTableBody"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="modal fade" id="studentFeedbackModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-1">Student Feedback Response</h5>
          <div class="text-muted small" id="studentFeedbackMeta">Student details</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body bg-light" id="studentFeedbackModalBody">
        <div class="text-center py-5 text-muted">Loading...</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="overallAnalyticsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-1">Overall Feedback Analytics</h5>
          <div class="text-muted small">Question wise response summary with percentages</div>
        </div>

        <div class="d-flex align-items-center gap-2 ms-auto">
          <button type="button" class="btn btn-outline-dark btn-sm" id="printAnalyticsBtn">
            <i class="fa fa-print me-1"></i> Print Chart
          </button>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
      </div>

      <div class="modal-body bg-light" id="overallAnalyticsModalBody">
        <div class="text-center py-5 text-muted">Loading analytics...</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.bootstrap5.min.css">

<style>
  .analytics-page {
    background: #f3edf9;
    min-height: 100vh;
  }

  .analytics-page,
  .analytics-filter-card,
  .analytics-filter-card .card-body,
  .analytics-filter-card .row,
  .analytics-filter-card .col-md-2 {
    overflow: visible !important;
  }

  .analytics-filter-card {
    position: relative;
    z-index: 20;
    border: 1px solid #e4e0eb !important;
    box-shadow: none !important;
  }

  .analytics-filter-card .col-md-2 {
    position: relative;
    z-index: 1;
  }

  .analytics-filter-card .col-md-2:focus-within {
    z-index: 9999;
  }

  .choices {
    position: relative !important;
    z-index: 100;
    width: 100%;
    margin-bottom: 0;
  }

  .analytics-filter-select,
  .choices__inner {
    min-height: 42px !important;
    border-radius: 8px !important;
    border: 1px solid #d7d7d7 !important;
    background: #fff !important;
    box-shadow: none !important;
    font-size: 14px;
  }

  .choices__list--dropdown,
  .choices__list[aria-expanded] {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    width: 100% !important;
    z-index: 99999 !important;
    background: #fff !important;
    border: 1px solid #d7d7d7 !important;
    border-radius: 8px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    max-height: 260px !important;
    overflow-y: auto !important;
  }

  .choices__list--multiple .choices__item {
    background: #ef6c2f !important;
    border: 1px solid #ef6c2f !important;
    border-radius: 20px !important;
    font-size: 12px !important;
  }

  .analytics-table thead th {
    background: #fff7f2;
    color: #3c4043;
    font-weight: 600;
    border-bottom: 1px solid #eadfd7 !important;
    white-space: nowrap;
  }

  .analytics-table tbody td {
    vertical-align: middle;
  }

  #studentFeedbackStatusTable {
    width: 100% !important;
  }

  .status-badge {
    font-size: 12px;
    font-weight: 600;
    padding: 7px 12px;
    border-radius: 999px;
    display: inline-block;
  }

  .status-submitted {
    background: #e7f8ee;
    color: #18794e;
  }

  .status-not-submitted {
    background: #fdeceb;
    color: #c0392b;
  }

  .btn-orange {
    background: #ef6c2f;
    border-color: #ef6c2f;
    color: #fff;
  }

  .btn-orange:hover {
    background: #df5f22;
    border-color: #df5f22;
    color: #fff;
  }

  .student-answer-card,
  .analytics-question-card,
  .analytics-info-card {
    border: 1px solid #e4e0eb;
    border-radius: 16px;
    background: #fff;
    box-shadow: none;
  }

  .analytics-question-card .card-body,
  .analytics-info-card .card-body,
  .student-answer-card .card-body {
    padding: 18px;
  }

  .analytics-question-title {
    font-size: 16px;
    font-weight: 700;
    color: #202124;
    margin-bottom: 10px;
  }

  .analytics-question-type {
    font-size: 12px;
    font-weight: 600;
    color: #ef6c2f;
    background: #fff2ea;
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    margin-bottom: 12px;
  }

  .student-line-box {
    background: #faf7fd;
    border: 1px solid #ece4f5;
    border-radius: 12px;
    padding: 12px;
    height: 100%;
  }

  .student-line-box h6 {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 10px;
  }

  .student-line-box .line-item {
    font-size: 14px;
    color: #444;
    margin-bottom: 6px;
    word-break: break-word;
  }

  .option-stats-table th,
  .option-stats-table td {
    font-size: 13px;
    vertical-align: middle;
  }

  .paragraph-response-box {
    background: #fcfcfc;
    border: 1px solid #ececec;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
  }

  .paragraph-response-box .student-meta {
    font-size: 13px;
    font-weight: 700;
    color: #ef6c2f;
    margin-bottom: 4px;
  }

  .paragraph-response-box .student-response {
    font-size: 14px;
    color: #333;
    line-height: 1.6;
    white-space: pre-wrap;
  }

  @media print {
    body * {
      visibility: hidden !important;
    }

    #overallAnalyticsModal,
    #overallAnalyticsModal * {
      visibility: visible !important;
    }

    #overallAnalyticsModal {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      background: #fff;
    }

    #printAnalyticsBtn,
    .btn-close {
      display: none !important;
    }
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
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
  Chart.register(ChartDataLabels);

  let choicesInstances = {};
  let analyticsCharts = [];

  function initChoice(id) {
    const el = document.getElementById(id);
    if (!el) return;

    if (choicesInstances[id]) {
      choicesInstances[id].destroy();
    }

    choicesInstances[id] = new Choices(el, {
      removeItemButton: true,
      shouldSort: false,
      searchEnabled: true,
      placeholder: true,
      placeholderValue: 'Select option',
      itemSelectText: ''
    });
  }

  function initAllChoices() {
    [
      'filter_session_ids',
      'filter_center_ids',
      'filter_programme_ids',
      'filter_technology_ids',
      'filter_batch_ids',
      'filter_form_ids'
    ].forEach(initChoice);
  }

  function getValues(id) {
    const instance = choicesInstances[id];
    if (!instance) return [];
    const value = instance.getValue(true);
    return Array.isArray(value) ? value : (value ? [value] : []);
  }

  function setChoicesData(id, rows, selectedValues = []) {
    const instance = choicesInstances[id];
    if (!instance) return;

    const selected = selectedValues.map(String);

    const data = [{
      value: 'all',
      label: 'All',
      selected: selected.includes('all')
    }];

    rows.forEach(row => {
      data.push({
        value: String(row.id),
        label: row.text,
        selected: selected.includes(String(row.id))
      });
    });

    instance.clearStore();
    instance.setChoices(data, 'value', 'label', true);
  }

  async function postCascade(url, payload) {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': "{{ csrf_token() }}",
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    return await response.json();
  }

  async function loadCenters(selected = []) {
    const data = await postCascade("{{ route('admin.feedback.cascade.centers') }}", {
      session_ids: getValues('filter_session_ids')
    });

    if (data.success) {
      setChoicesData('filter_center_ids', data.data, selected);
    }
  }

  async function loadProgrammes(selected = []) {
    const data = await postCascade("{{ route('admin.feedback.cascade.programmes') }}", {
      session_ids: getValues('filter_session_ids'),
      center_ids: getValues('filter_center_ids')
    });

    if (data.success) {
      setChoicesData('filter_programme_ids', data.data, selected);
    }
  }

  async function loadTechnologies(selected = []) {
    const data = await postCascade("{{ route('admin.feedback.cascade.technologies') }}", {
      session_ids: getValues('filter_session_ids'),
      programme_ids: getValues('filter_programme_ids')
    });

    if (data.success) {
      setChoicesData('filter_technology_ids', data.data, selected);
    }
  }

  async function loadBatches(selected = []) {
    const data = await postCascade("{{ route('admin.feedback.cascade.batches') }}", {
      session_ids: getValues('filter_session_ids'),
      programme_ids: getValues('filter_programme_ids'),
      technology_ids: getValues('filter_technology_ids')
    });

    if (data.success) {
      setChoicesData('filter_batch_ids', data.data, selected);
    }
  }

  async function onSessionChange() {
    await loadCenters([]);
    setChoicesData('filter_programme_ids', [], []);
    setChoicesData('filter_technology_ids', [], []);
    setChoicesData('filter_batch_ids', [], []);
  }

  async function onCenterChange() {
    await loadProgrammes([]);
    setChoicesData('filter_technology_ids', [], []);
    setChoicesData('filter_batch_ids', [], []);
  }

  async function onProgrammeChange() {
    await loadTechnologies([]);
    setChoicesData('filter_batch_ids', [], []);
  }

  async function onTechnologyChange() {
    await loadBatches([]);
  }

  function bindCascadeEvents() {
    document.getElementById('filter_session_ids').addEventListener('change', onSessionChange);
    document.getElementById('filter_center_ids').addEventListener('change', onCenterChange);
    document.getElementById('filter_programme_ids').addEventListener('change', onProgrammeChange);
    document.getElementById('filter_technology_ids').addEventListener('change', onTechnologyChange);
  }

  function makeInfoRow(message, className = 'text-muted') {
    return `
            <tr class="dt-temp-row">
                <td></td>
                <td></td>
                <td class="text-center ${className} py-4">${message}</td>
                <td></td>
                <td></td>
            </tr>
        `;
  }

  function destroyDataTableIfExists() {
    if ($.fn.DataTable.isDataTable('#studentFeedbackStatusTable')) {
      $('#studentFeedbackStatusTable').DataTable().destroy();
    }
  }

  function initTable() {
    destroyDataTableIfExists();

    $('#studentFeedbackStatusTable').DataTable({
      responsive: true,
      pageLength: 10,
      ordering: true,
      autoWidth: false,
      fixedHeader: true,
      dom: "<'custom-dt-toolbar row align-items-center mb-3'<'col-md-3 dt-length-wrap'l><'col-md-6 dt-buttons-wrap text-center'B><'col-md-3 dt-search-wrap'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-md-5'i><'col-md-7'p>>",
      buttons: [{
          extend: 'copyHtml5',
          text: 'Copy',
          className: 'dt-btn dt-btn-copy',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'csvHtml5',
          text: 'CSV',
          className: 'dt-btn dt-btn-csv',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'excelHtml5',
          text: 'Excel',
          className: 'dt-btn dt-btn-excel',
          exportOptions: {
            columns: [0, 1, 2, 3]
          }
        },
        {
          extend: 'pdfHtml5',
          text: 'PDF',
          className: 'dt-btn dt-btn-pdf',
          exportOptions: {
            columns: [0, 1, 2, 3]
          },
          orientation: 'landscape',
          pageSize: 'A4',
          title: 'Student Feedback Status'
        },
        {
          extend: 'print',
          text: 'Print',
          className: 'dt-btn dt-btn-print',
          exportOptions: {
            columns: [0, 1, 2, 3]
          },
          title: 'Student Feedback Status'
        },
        {
          extend: 'colvis',
          text: 'Column visibility',
          className: 'dt-btn dt-btn-colvis'
        }
      ],
      columnDefs: [{
        orderable: false,
        targets: [4]
      }],
      language: {
        search: "Search:",
        lengthMenu: "Show _MENU_ entries"
      }
    });
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text ?? '';
    return div.innerHTML;
  }

  function renderStudentTableRows(rows) {
    const tbody = document.getElementById('studentFeedbackTableBody');

    destroyDataTableIfExists();

    if (!rows || !rows.length) {
      tbody.innerHTML = makeInfoRow('No students found.', 'text-muted');
      initTable();
      return;
    }

    tbody.innerHTML = rows.map((row, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${escapeHtml(row.student_name || '-')}</td>
                <td>${escapeHtml(row.mobile || '-')}</td>
                <td>
                    <span class="status-badge ${row.submitted ? 'status-submitted' : 'status-not-submitted'}">
                        ${row.submitted ? 'Submitted' : 'Not Submitted'}
                    </span>
                </td>
                <td>
                    ${
                        row.submitted
                        ? `<button type="button" class="btn btn-sm btn-orange view-student-feedback-btn"
                                data-student-id="${row.student_id}"
                                data-form-id="${row.form_id}">
                                Feedback
                           </button>`
                        : `<span class="text-muted small">No Feedback</span>`
                    }
                </td>
            </tr>
        `).join('');

    initTable();
  }

  function currentFilterPayload() {
    return {
      session_ids: getValues('filter_session_ids'),
      center_ids: getValues('filter_center_ids'),
      programme_ids: getValues('filter_programme_ids'),
      technology_ids: getValues('filter_technology_ids'),
      batch_ids: getValues('filter_batch_ids'),
      form_ids: getValues('filter_form_ids')
    };
  }

  async function loadStudentTable() {
    const tbody = document.getElementById('studentFeedbackTableBody');

    destroyDataTableIfExists();
    tbody.innerHTML = makeInfoRow('Loading data...', 'text-muted');

    try {
      const response = await fetch("{{ route('admin.feedback.analytics.filtered.students') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json'
        },
        body: JSON.stringify(currentFilterPayload())
      });

      const rawText = await response.text();
      let data = {};

      try {
        data = JSON.parse(rawText);
      } catch (e) {
        console.error('Invalid JSON response:', rawText);
        tbody.innerHTML = makeInfoRow('Server returned invalid response.', 'text-danger');
        return;
      }

      if (!response.ok) {
        tbody.innerHTML = makeInfoRow(data.message || 'Unable to load data.', 'text-danger');
        return;
      }

      if (data.success) {
        renderStudentTableRows(data.data || []);
      } else {
        tbody.innerHTML = makeInfoRow(data.message || 'No data found.', 'text-warning');
      }
    } catch (error) {
      tbody.innerHTML = makeInfoRow('Unable to load data.', 'text-danger');
    }
  }

  async function openStudentFeedback(studentId, formId) {
    const body = document.getElementById('studentFeedbackModalBody');
    const meta = document.getElementById('studentFeedbackMeta');

    body.innerHTML = `<div class="text-center py-5 text-muted">Loading...</div>`;
    meta.innerText = 'Student details';

    const modal = new bootstrap.Modal(document.getElementById('studentFeedbackModal'));
    modal.show();

    try {
      const response = await fetch("{{ route('admin.feedback.analytics.student.response') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          student_id: studentId,
          form_id: formId
        })
      });

      const data = await response.json();

      if (!data.success || !data.data) {
        body.innerHTML = `<div class="alert alert-warning mb-0">Response not found.</div>`;
        return;
      }

      const student = data.data.student || {};
      const answers = data.data.answers || [];

      meta.innerText = `${student.name || '-'} | ${student.mobile || '-'}`;

      body.innerHTML = `
                <div class="row g-3">
                    ${answers.length ? answers.map((item, index) => `
                        <div class="col-12">
                            <div class="student-answer-card">
                                <div class="card-body">
                                    <div class="fw-bold mb-2">${index + 1}. ${escapeHtml(item.question || 'Question')}</div>
                                    <div class="text-muted small mb-2">${escapeHtml(item.type || '')}</div>
                                    <div>
                                        ${
                                            Array.isArray(item.value)
                                            ? item.value.map(v => `<span class="badge bg-light text-dark border me-1 mb-1">${escapeHtml(v)}</span>`).join('')
                                            : `<div>${escapeHtml(item.value || '-')}</div>`
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('') : `
                        <div class="col-12">
                            <div class="alert alert-info mb-0">No answers found.</div>
                        </div>
                    `}
                </div>
            `;
    } catch (error) {
      body.innerHTML = `<div class="alert alert-danger mb-0">Unable to load student feedback.</div>`;
    }
  }

  function destroyAllCharts() {
    analyticsCharts.forEach(chart => {
      try {
        chart.destroy();
      } catch (e) {}
    });
    analyticsCharts = [];
  }

  function makeChart(canvasId, type, labels, values, title) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    const chart = new Chart(ctx, {
      type: type,
      data: {
        labels: labels,
        datasets: [{
          label: title,
          data: values,
          backgroundColor: [
            '#ef6c2f', '#6f42c1', '#20c997', '#0d6efd', '#ffc107',
            '#dc3545', '#6610f2', '#198754', '#fd7e14', '#6c757d'
          ],
          borderColor: '#ffffff',
          borderWidth: 2,
          borderRadius: type === 'bar' ? 10 : 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          datalabels: {
            color: '#222',
            font: {
              weight: 'bold',
              size: 11
            },
            formatter: (value) => value + '%'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.raw}%`;
              }
            }
          }
        },
        scales: type === 'bar' ? {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: value => value + '%'
            }
          }
        } : {}
      }
    });

    analyticsCharts.push(chart);
    return chart;
  }

  function renderParagraphResponses(question) {
    const responses = question.student_responses || [];

    if (!responses.length) {
      return `<div class="alert alert-info mb-0">No paragraph responses available.</div>`;
    }

    return responses.map((item, index) => `
            <div class="paragraph-response-box">
                <div class="student-meta">${index + 1}. ${escapeHtml(item.name || '-')} | ${escapeHtml(item.mobile || '-')}</div>
                <div class="student-response">${escapeHtml(item.response || '-')}</div>
            </div>
        `).join('');
  }

  function renderOptionStatsTable(question) {
    const rows = question.option_stats || [];
    if (!rows.length) return '';

    return `
            <div class="table-responsive mt-3">
                <table class="table table-bordered option-stats-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Option</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows.map(row => `
                            <tr>
                                <td>${escapeHtml(row.option || '-')}</td>
                                <td>${row.count ?? 0}</td>
                                <td>${row.percentage ?? 0}%</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
  }

  async function loadOverallAnalytics() {
    destroyAllCharts();

    const body = document.getElementById('overallAnalyticsModalBody');
    body.innerHTML = `<div class="text-center py-5 text-muted">Loading analytics...</div>`;

    try {
      const response = await fetch("{{ route('admin.feedback.analytics.overall.data') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json'
        },
        body: JSON.stringify(currentFilterPayload())
      });

      const data = await response.json();

      if (!data.success || !data.data) {
        body.innerHTML = `<div class="alert alert-warning mb-0">Analytics data not found.</div>`;
        return;
      }

      const students = data.data.students || [];
      const questions = data.data.questions || [];

      let html = `
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="analytics-info-card h-100">
                            <div class="card-body">
                                <div class="student-line-box">
                                    <h6>Students Name</h6>
                                    ${
                                        students.length
                                        ? students.map((s, i) => `<div class="line-item">${i + 1}. ${escapeHtml(s.name || '-')}</div>`).join('')
                                        : `<div class="text-muted">No student found.</div>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="analytics-info-card h-100">
                            <div class="card-body">
                                <div class="student-line-box">
                                    <h6>Mobile Number</h6>
                                    ${
                                        students.length
                                        ? students.map((s, i) => `<div class="line-item">${i + 1}. ${escapeHtml(s.mobile || '-')}</div>`).join('')
                                        : `<div class="text-muted">No mobile number found.</div>`
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

      html += questions.map((question, index) => {
        const canvasId = `analyticsChart_${index}`;
        const qType = (question.type || '').toLowerCase();

        let chartHtml = '';
        if (['radio', 'checkbox', 'dropdown'].includes(qType)) {
          chartHtml = `
                        <div class="row g-3">
                            <div class="col-lg-7">
                                <div style="height: 330px;">
                                    <canvas id="${canvasId}"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                ${renderOptionStatsTable(question)}
                            </div>
                        </div>
                    `;
        } else {
          chartHtml = `
                        <div class="mt-2">
                            ${renderParagraphResponses(question)}
                        </div>
                    `;
        }

        return `
                    <div class="analytics-question-card mb-3">
                        <div class="card-body">
                            <div class="analytics-question-title">${index + 1}. ${escapeHtml(question.question || 'Question')}</div>
                            <div class="analytics-question-type">${escapeHtml(question.type || 'text')}</div>
                            ${chartHtml}
                        </div>
                    </div>
                `;
      }).join('');

      body.innerHTML = html;

      questions.forEach((question, index) => {
        const qType = (question.type || '').toLowerCase();

        if (!['radio', 'checkbox', 'dropdown'].includes(qType)) return;

        const labels = (question.option_stats || []).map(item => item.option);
        const values = (question.option_stats || []).map(item => Number(item.percentage));

        let chartType = 'bar';
        if (qType === 'radio') chartType = 'pie';
        if (qType === 'checkbox') chartType = 'doughnut';
        if (qType === 'dropdown') chartType = 'bar';

        makeChart(`analyticsChart_${index}`, chartType, labels, values, question.question || 'Question');
      });
    } catch (error) {
      body.innerHTML = `<div class="alert alert-danger mb-0">Unable to load analytics.</div>`;
    }
  }

  function resetAllFilters() {
    Object.keys(choicesInstances).forEach(id => {
      if (choicesInstances[id]) {
        choicesInstances[id].removeActiveItems();
      }
    });

    setChoicesData('filter_center_ids', [], []);
    setChoicesData('filter_programme_ids', [], []);
    setChoicesData('filter_technology_ids', [], []);
    setChoicesData('filter_batch_ids', [], []);
  }

  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.view-student-feedback-btn');
    if (!btn) return;

    const studentId = btn.getAttribute('data-student-id');
    const formId = btn.getAttribute('data-form-id');
    openStudentFeedback(studentId, formId);
  });

  document.getElementById('applyAnalyticsFilterBtn').addEventListener('click', function() {
    loadStudentTable();
  });

  document.getElementById('resetAnalyticsFilterBtn').addEventListener('click', async function() {
    resetAllFilters();
    await loadStudentTable();
  });

  document.getElementById('viewOverallAnalyticsBtn').addEventListener('click', function() {
    loadOverallAnalytics();
  });

  document.getElementById('printAnalyticsBtn').addEventListener('click', function() {
    window.print();
  });

  document.addEventListener('DOMContentLoaded', async function() {
    initAllChoices();
    bindCascadeEvents();
    await loadStudentTable();
  });
</script>
@endpush