@extends('Admin.layouts.app')

@section('title', 'Add Attandence')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
  .filter-card,
  .table-card {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    overflow: hidden;
    background: #fff;
  }

  .filter-card .card-body,
  .table-card .card-body {
    padding: 1.5rem;
  }

  .section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
  }

  .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #667085;
    margin-bottom: 8px;
  }

  .choices {
    margin-bottom: 0;
  }

  .choices__inner {
    min-height: 48px;
    border: 1px solid #d0d5dd;
    border-radius: 12px;
    background: #fff;
    padding: 7px 10px !important;
    font-size: 14px;
    box-shadow: none !important;
  }

  .choices.is-focused .choices__inner,
  .choices.is-open .choices__inner {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.10) !important;
  }

  .choices__list--dropdown,
  .choices__list[aria-expanded] {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 14px 28px rgba(15, 23, 42, 0.12);
    z-index: 9999;
  }

  .choices__list--multiple .choices__item {
    background: #0f766e !important;
    border: 1px solid #0f766e !important;
    border-radius: 999px !important;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 9px;
  }

  .choices[data-type*="select-multiple"] .choices__button {
    border-left: 1px solid rgba(255, 255, 255, 0.35);
    margin-left: 8px;
    padding-left: 10px;
  }


  .btn-reset-custom {
    border: 1px solid #d0d5dd;
    background: #344054;
    color: white;
  }

  .btn-reset-custom:hover {
    background: #72829e;
    color: #fff;
  }


  .btn-fetch-custom:hover {
    color: #fff;
    transform: translateY(-1px);
  }

  .btn-save-custom {
    border: 0;
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff;
    box-shadow: 0 10px 22px rgba(21, 128, 61, 0.20);
  }

  .btn-save-custom:hover {
    color: #fff;
    transform: translateY(-1px);
  }

  .attendance-table th,
  .attendance-table td {
    vertical-align: middle;
    white-space: nowrap;
  }

  .attendance-table thead th {
    background: #fff7f2;
    border-bottom: 2px solid #f26b2f;
    font-weight: 700;
  }

  .attendance-radio {
    width: 18px;
    height: 18px;
    cursor: pointer;
  }

  .hidden-card {
    display: none;
  }

  .modal-content {
    border-radius: 16px;
  }

  .modal-header,
  .modal-footer {
    border-color: #eef2f7;
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr mb-4 d-flex justify-content-between">
      <div>
        <h1>Add Attandence</h1>
        <p class="mb-0">Fetch learners and mark attendance using relational filters.</p>
      </div>

      <div class="col-sm-5 mt-3 d-flex justify-content-evenly">
        <button type="button" id="openBulkAttendanceModalBtn" class="btn-fetch-custom btn btn-primary border-0 px-4 py-2 rounded-1">
          <i class="bi bi-plus"></i>
          <span>Add Bulk Attendance</span>
        </button>
        <button type="button" id="generateQrBtn" class="btn-fetch-custom btn btn-secondary border-0 px-4 py-2 rounded-1">
          <i class="bi bi-plus"></i>
          <span>Generate QR</span>
        </button>
      </div>
    </div>


    <div class="card filter-card mb-4">
      <div class="card-body">
        <div class="section-title">Fetch Learners</div>

        <form id="filterForm">
          @csrf
          <div class="row g-3">
            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Date</label>
              <input type="date" id="attendance_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Session(s)</label>
              <select id="session_ids" class="form-select" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Center(s)</label>
              <select id="center_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Programme(s)</label>
              <select id="programme_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Technology(s)</label>
              <select id="technology_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Batch(es)</label>
              <select id="batch_ids" class="form-select" multiple></select>
            </div>

            <div class="col-12 d-flex justify-content-center gap-3 mt-3 flex-wrap">
              <button type="button" id="resetBtn" class="btn-reset-custom btn btn-secondary border-0 px-4 py-2 rounded-1">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Reset</span>
              </button>

              <button type="button" id="fetchStudentsBtn" class="btn-fetch-custom btn btn-primary border-0 px-4 py-2 rounded-1">
                <i class="bi bi-search"></i>
                <span>Fetch Students</span>
              </button>

            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card table-card hidden-card" id="studentsCard">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
          <div class="section-title mb-0">Student Attendance</div>

          <button type="button" id="saveAttendanceBtn" class="btn-save-custom btn border-0 px-4 py-2 rounded-1 btn-success">
            <i class="bi bi-check2-circle"></i>
            <span>Save Attendance</span>
          </button>
        </div>

        <form id="attendanceForm">
          @csrf
          <input type="hidden" name="attendance_date" id="save_attendance_date">

          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle nowrap w-100 attendance-table">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Registration ID</th>
                  <th>Student Name</th>
                  <th>Student College</th>
                  <th>Student Mobile</th>
                  <th class="text-center">Present</th>
                  <th class="text-center">Absent</th>
                </tr>
              </thead>
              <tbody id="studentsTableBody"></tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<div class="modal fade" id="qrAttendanceModal" tabindex="-1" aria-labelledby="qrAttendanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title" id="qrAttendanceModalLabel">QR Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <div id="qrCodeBox" class="d-flex justify-content-center mb-3"></div>

        <div class="mb-2">
          <label class="form-label fw-semibold">QR Link</label>
          <input type="text" id="qrLinkInput" class="form-control" readonly>
        </div>

        <button type="button" class="btn btn-outline-primary mt-2" id="copyQrLinkBtn">
          Copy Link
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bulkAttendanceModal" tabindex="-1" aria-labelledby="bulkAttendanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bulk-attendance-modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="bulkAttendanceModalLabel">Add Bulk Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="bulkAttendanceForm" enctype="multipart/form-data">
        @csrf

        <div class="modal-body bulk-attendance-modal-body">
          <div class="row g-3">


            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Session(s)</label>
              <select name="session_ids[]" id="bulk_session_ids" class="form-select" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Center(s)</label>
              <select name="center_ids[]" id="bulk_center_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Programme(s)</label>
              <select name="programme_ids[]" id="bulk_programme_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Technology(s)</label>
              <select name="technology_ids[]" id="bulk_technology_ids" class="form-select" multiple></select>
            </div>

            <div class="col-lg-4 col-md-6">
              <label class="form-label">Select Batch(es)</label>
              <select name="batch_ids[]" id="bulk_batch_ids" class="form-select" multiple></select>
            </div>

            <div class="col-12">
              <label class="form-label">Upload CSV</label>
              <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv,.txt">

            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="{{ route('attendance.bulk.template') }}" class="btn btn-outline-primary">
            <i class="bi bi-download"></i> Download CSV Template
          </a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-upload"></i> Upload & Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


<script>
  $(function() {
    const csrfToken = "{{ csrf_token() }}";

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

    function createChoice(selector, placeholder) {
      return new Choices(selector, {
        removeItemButton: true,
        searchEnabled: true,
        shouldSort: false,
        itemSelectText: '',
        allowHTML: false,
        placeholder: true,
        placeholderValue: placeholder,
        noResultsText: 'No result found',
        noChoicesText: 'No options available'
      });
    }

    const sessionChoices = createChoice('#session_ids', 'Select Session(s)');
    const centerChoices = createChoice('#center_ids', 'Select Center(s)');
    const programmeChoices = createChoice('#programme_ids', 'Select Programme(s)');
    const technologyChoices = createChoice('#technology_ids', 'Select Technology(s)');
    const batchChoices = createChoice('#batch_ids', 'Select Batch(es)');

    function normalizeValues(values) {
      if (Array.isArray(values)) return values.map(String).filter(Boolean);
      if (values === null || values === undefined || values === '') return [];
      return [String(values)];
    }

    function getSelectedValues(instance) {
      return normalizeValues(instance.getValue(true));
    }

    function resetChoice(instance) {
      instance.removeActiveItems();
      instance.clearChoices();
      instance.setChoices([], 'value', 'label', true);
    }

    function setChoiceOptions(instance, items, valueKey, labelKey, selectedValues = []) {
      const validValues = (items || []).map(item => String(item[valueKey]));
      const selected = (selectedValues || []).map(String).filter(value => validValues.includes(value));

      instance.clearStore();
      instance.setChoices(
        (items || []).map(item => ({
          value: String(item[valueKey]),
          label: item[labelKey],
          selected: selected.includes(String(item[valueKey]))
        })),
        'value',
        'label',
        true
      );
    }

    function postApi(url, payload, cb) {
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          _token: csrfToken,
          ...payload
        },
        success: cb,
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    }

    $('#session_ids').on('change', function() {
      const sessionIds = getSelectedValues(sessionChoices);

      resetChoice(centerChoices);
      resetChoice(programmeChoices);
      resetChoice(technologyChoices);
      resetChoice(batchChoices);

      postApi("{{ route('attendance.centers') }}", {
        session_ids: sessionIds
      }, function(res) {
        setChoiceOptions(centerChoices, res, 'id', 'text');
      });
    });

    $('#center_ids').on('change', function() {
      const sessionIds = getSelectedValues(sessionChoices);
      const centerIds = getSelectedValues(centerChoices);

      resetChoice(programmeChoices);
      resetChoice(technologyChoices);
      resetChoice(batchChoices);

      postApi("{{ route('attendance.programmes') }}", {
        session_ids: sessionIds,
        center_ids: centerIds
      }, function(res) {
        setChoiceOptions(programmeChoices, res, 'id', 'text');
      });
    });

    $('#programme_ids').on('change', function() {
      const sessionIds = getSelectedValues(sessionChoices);
      const programmeIds = getSelectedValues(programmeChoices);

      resetChoice(technologyChoices);
      resetChoice(batchChoices);

      postApi("{{ route('attendance.technologies') }}", {
        session_ids: sessionIds,
        programme_ids: programmeIds
      }, function(res) {
        setChoiceOptions(technologyChoices, res, 'id', 'text');
      });
    });

    $('#technology_ids').on('change', function() {
      const sessionIds = getSelectedValues(sessionChoices);
      const programmeIds = getSelectedValues(programmeChoices);
      const technologyIds = getSelectedValues(technologyChoices);

      resetChoice(batchChoices);

      postApi("{{ route('attendance.batches') }}", {
        session_ids: sessionIds,
        programme_ids: programmeIds,
        technology_ids: technologyIds
      }, function(res) {
        setChoiceOptions(batchChoices, res, 'id', 'text');
      });
    });

    $('#fetchStudentsBtn').on('click', function() {
      const attendanceDate = $('#attendance_date').val();
      const sessionIds = getSelectedValues(sessionChoices);
      const centerIds = getSelectedValues(centerChoices);
      const programmeIds = getSelectedValues(programmeChoices);
      const technologyIds = getSelectedValues(technologyChoices);
      const batchIds = getSelectedValues(batchChoices);

      postApi("{{ route('attendance.students') }}", {
        attendance_date: attendanceDate,
        session_ids: sessionIds,
        center_ids: centerIds,
        programme_ids: programmeIds,
        technology_ids: technologyIds,
        batch_ids: batchIds
      }, function(res) {
        if (!res.status) {
          $('#studentsTableBody').html('');
          $('#studentsCard').addClass('hidden-card');
          showToast('warning', res.message || 'Attendance already marked.');
          return;
        }

        const students = res.data || [];
        $('#save_attendance_date').val(attendanceDate);

        let rows = '';

        if (!students.length) {
          rows = `<tr><td colspan="7" class="text-center py-4">No students found.</td></tr>`;
        } else {
          $.each(students, function(index, student) {
            const presentChecked = student.attendance_status === 'P' ? 'checked' : '';
            const absentChecked = student.attendance_status === 'A' ? 'checked' : '';

            rows += `
        <tr>
          <td>${index + 1}</td>
          <td>${student.stu_regid ?? '-'}</td>
          <td>${student.stu_name ?? '-'}</td>
          <td>${student.stu_college ?? '-'}</td>
          <td>${student.stu_mobile ?? '-'}</td>

          <td class="text-center">
            <input class="attendance-radio" type="radio" name="students[${index}][attendance]" value="P" ${presentChecked}>
          </td>

          <td class="text-center">
            <input class="attendance-radio" type="radio" name="students[${index}][attendance]" value="A" ${absentChecked}>
            <input type="hidden" name="students[${index}][stu_id]" value="${student.stu_id}">
            <input type="hidden" name="students[${index}][session_id]" value="${student.session_id}">
            <input type="hidden" name="students[${index}][center_id]" value="${student.center_id}">
            <input type="hidden" name="students[${index}][programme_id]" value="${student.programme_id}">
            <input type="hidden" name="students[${index}][technology_id]" value="${student.technology_id}">
            <input type="hidden" name="students[${index}][batch_id]" value="${student.batch_id}">
          </td>
        </tr>
      `;
          });
        }

        $('#studentsTableBody').html(rows);
        $('#studentsCard').removeClass('hidden-card');
      });
    });

    $('#saveAttendanceBtn').on('click', function() {
      $.ajax({
        url: "{{ route('attendance.store') }}",
        type: 'POST',
        data: $('#attendanceForm').serialize(),
        success: function(res) {
          if (res.status) {
            showToast('success', res.message || 'Attendance saved successfully.');
          } else {
            showToast('warning', res.message || 'Nothing to save.');
          }
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Attendance save failed.');
        }
      });
    });

    $('#resetBtn').on('click', function() {
      $('#attendance_date').val("{{ date('Y-m-d') }}");

      resetChoice(sessionChoices);
      resetChoice(centerChoices);
      resetChoice(programmeChoices);
      resetChoice(technologyChoices);
      resetChoice(batchChoices);

      $('#studentsTableBody').html('');
      $('#studentsCard').addClass('hidden-card');
    });

    const bulkModalEl = document.getElementById('bulkAttendanceModal');
    const bulkModal = new bootstrap.Modal(bulkModalEl);

    const bulkSessionChoices = createChoice('#bulk_session_ids', 'Select Session(s)');
    const bulkCenterChoices = createChoice('#bulk_center_ids', 'Select Center(s)');
    const bulkProgrammeChoices = createChoice('#bulk_programme_ids', 'Select Programme(s)');
    const bulkTechnologyChoices = createChoice('#bulk_technology_ids', 'Select Technology(s)');
    const bulkBatchChoices = createChoice('#bulk_batch_ids', 'Select Batch(es)');

    $('#openBulkAttendanceModalBtn').on('click', function() {
      bulkModal.show();
    });

    $('#bulk_session_ids').on('change', function() {
      const sessionIds = getSelectedValues(bulkSessionChoices);

      resetChoice(bulkCenterChoices);
      resetChoice(bulkProgrammeChoices);
      resetChoice(bulkTechnologyChoices);
      resetChoice(bulkBatchChoices);

      postApi("{{ route('attendance.centers') }}", {
        session_ids: sessionIds
      }, function(res) {
        setChoiceOptions(bulkCenterChoices, res, 'id', 'text');
      });
    });

    $('#bulk_center_ids').on('change', function() {
      const sessionIds = getSelectedValues(bulkSessionChoices);
      const centerIds = getSelectedValues(bulkCenterChoices);

      resetChoice(bulkProgrammeChoices);
      resetChoice(bulkTechnologyChoices);
      resetChoice(bulkBatchChoices);

      postApi("{{ route('attendance.programmes') }}", {
        session_ids: sessionIds,
        center_ids: centerIds
      }, function(res) {
        setChoiceOptions(bulkProgrammeChoices, res, 'id', 'text');
      });
    });

    $('#bulk_programme_ids').on('change', function() {
      const sessionIds = getSelectedValues(bulkSessionChoices);
      const programmeIds = getSelectedValues(bulkProgrammeChoices);

      resetChoice(bulkTechnologyChoices);
      resetChoice(bulkBatchChoices);

      postApi("{{ route('attendance.technologies') }}", {
        session_ids: sessionIds,
        programme_ids: programmeIds
      }, function(res) {
        setChoiceOptions(bulkTechnologyChoices, res, 'id', 'text');
      });
    });

    $('#bulk_technology_ids').on('change', function() {
      const sessionIds = getSelectedValues(bulkSessionChoices);
      const programmeIds = getSelectedValues(bulkProgrammeChoices);
      const technologyIds = getSelectedValues(bulkTechnologyChoices);

      resetChoice(bulkBatchChoices);

      postApi("{{ route('attendance.batches') }}", {
        session_ids: sessionIds,
        programme_ids: programmeIds,
        technology_ids: technologyIds
      }, function(res) {
        setChoiceOptions(bulkBatchChoices, res, 'id', 'text');
      });
    });

    $('#bulkAttendanceForm').on('submit', function(e) {
      e.preventDefault();

      let formData = new FormData(this);

      $.ajax({
        url: "{{ route('attendance.bulk.store') }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(res) {
          if (res.status) {
            showToast('success', res.message || 'Bulk attendance saved successfully.');
            bulkModal.hide();
            $('#bulkAttendanceForm')[0].reset();

            resetChoice(bulkSessionChoices);
            resetChoice(bulkCenterChoices);
            resetChoice(bulkProgrammeChoices);
            resetChoice(bulkTechnologyChoices);
            resetChoice(bulkBatchChoices);
          } else {
            showToast('warning', res.message || 'Bulk attendance not saved.');
          }
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Bulk attendance upload failed.');
        }
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const generateBtn = document.getElementById('generateQrBtn');
      const qrBox = document.getElementById('qrCodeBox');
      const qrLinkInput = document.getElementById('qrLinkInput');
      const copyBtn = document.getElementById('copyQrLinkBtn');
      const qrModalEl = document.getElementById('qrAttendanceModal');
      const qrModal = new bootstrap.Modal(qrModalEl);

      generateBtn.addEventListener('click', function() {
        // Yahan apna live/public URL dena hai
        const qrUrl = "{{ route('qr.attendance.form') }}";
        // Custom link dena ho to ऊपर wali line ki jagah:
        // const qrUrl = "https://yourdomain.com/qr-attendance";

        qrBox.innerHTML = "";
        qrLinkInput.value = qrUrl;

        new QRCode(qrBox, {
          text: qrUrl,
          width: 220,
          height: 220
        });

            
      });

      copyBtn.addEventListener('click', function() {
        qrLinkInput.select();
        qrLinkInput.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(qrLinkInput.value)
          .then(() => {
            copyBtn.textContent = "Copied";
            setTimeout(() => {
              copyBtn.textContent = "Copy Link";
            }, 1500);
          });
      });
    });


  });
</script>
@endpush