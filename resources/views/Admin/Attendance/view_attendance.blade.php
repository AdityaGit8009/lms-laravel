@extends('Admin.layouts.app')

@section('title', 'View Attendance Sheet')

@php
$hasSheetData = count($dateColumns) > 0 && $studentRows->count() > 0;
@endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />

<style>
  .filter-card,
  .table-card,
  .summary-card {
    border: 0 !important;
    /* border-radius: 16px !important; */
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08) !important;
    overflow: hidden !important;
    background: #fff !important;
  }

  .filter-card .card-body,
  .table-card .card-body,
  .summary-card .card-body {
    padding: 1.25rem;
  }

  .section-title {
    font-size: 1.1rem;
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
    min-height: 46px;
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



  .summary-card.total-card .card-body {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff) !important;
  }

  .summary-card.days-card .card-body {
    background: linear-gradient(135deg, #fff7ed, #ffedd5) !important;
  }

  .summary-card.present-card .card-body {
    background: linear-gradient(135deg, #ecfdf5, #d1fae5) !important;
  }

  .summary-card.absent-card .card-body {
    background: linear-gradient(135deg, #fff1f2, #ffe4e6) !important;
  }

  .summary-label {
    font-size: 13px;
    font-weight: 700;
    color: #667085;
    margin-bottom: 6px;
  }

  .summary-value {
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
  }

  .summary-value.total-text {
    color: #4338ca;
  }

  .summary-value.days-text {
    color: #ea580c;
  }

  .summary-value.present-text {
    color: #059669 !important;
  }

  .summary-value.absent-text {
    color: #dc2626;
  }

  .attendance-sheet-table {
    width: 100% !important;
  }

  .attendance-sheet-table th,
  .attendance-sheet-table td {
    vertical-align: middle;
    white-space: nowrap;
    text-align: center;
  }

  .attendance-sheet-table thead th {
    background: #ffffff !important;
    border-bottom: 2px solid #e5e7eb !important;
    font-weight: 800;
    color: #111827;
  }

  .attendance-sheet-table .left-col {
    text-align: left !important;
    white-space: normal !important;
    min-width: 180px;
  }

  .attendance-sheet-table .date-head {
    min-width: 130px;
    background: #fff !important;
  }

  .attendance-sheet-table .date-title {
    font-weight: 800;
    font-size: 16px;
    color: #111827;
    margin-bottom: 8px;
    text-align: center;
  }

  .attendance-sheet-table .count-chip {
    display: flex !important;
    align-items: center;
    justify-content: center;
    width: 96px;
    height: 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 800;
    margin: 3px auto;
  }

  .attendance-sheet-table .present-chip {
    background: #ecfdf5 !important;
    border: 1px solid #10b981 !important;
    color: #059669 !important;
  }

  .attendance-sheet-table .absent-chip {
    background: #fff1f2 !important;
    border: 1px solid #ef4444 !important;
    color: #dc2626 !important;
  }

  .avg-badge {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-width: 82px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 800;
    border: 1px solid;
  }

  .avg-good {
    background: #ecfdf5 !important;
    color: #059669 !important;
    border-color: #10b981 !important;
  }

  .avg-mid {
    background: #fff7ed !important;
    color: #ea580c !important;
    border-color: #fb923c !important;
  }

  .avg-low {
    background: #fff1f2 !important;
    color: #dc2626 !important;
    border-color: #fda4af !important;
  }

  .attendance-toggle {
    cursor: pointer;
    user-select: none;
    text-align: center;
  }

  .status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 32px;
    border-radius: 999px;
    font-size: 18px;
    font-weight: 800;
    line-height: 1;
  }

  .status-pill.present-pill {
    background: #e8fff4;
    color: #059669;
    border: 1px solid #10b981;
  }

  .status-pill.absent-pill {
    background: #fff1f2;
    color: #dc2626;
    border: 1px solid #ef4444;
  }

  .by-text {
    display: inline-block;
    margin-top: 4px;
    padding: 2px 8px;
    border-radius: 999px;
    background: #f8fafc;
    color: #475467;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.2;
  }

  .empty-cell {
    color: #98a2b3;
    font-weight: 700;
  }

  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d0d5dd;
    border-radius: 10px;
    padding: 6px 10px;
    margin-left: 6px;
  }

  .dataTables_wrapper .dataTables_length select {
    border: 1px solid #d0d5dd;
    border-radius: 10px;
    padding: 4px 28px 4px 10px;
  }

  .dt-buttons .btn {
    margin-right: 6px;
    margin-bottom: 6px;
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h1>Attendance Sheet</h1>
        <p class="mb-0">Date-wise class attendance summary.</p>
      </div>
    </div>

    <div class="card filter-card mb-4">
      <div class="card-body">
        <div class="section-title">Filter Attendance Sheet</div>

        <form method="GET" action="{{ route('admin.viewattandence') }}" id="filterForm">
          <div class="row g-3">
            <div class="col-lg-3 col-md-6">
              <label class="form-label">From</label>
              <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">To</label>
              <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Select Session(s)</label>
              <select name="session_ids[]" id="session_ids" class="form-select" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}" {{ in_array((string)$session->session_id, array_map('strval', $sessionIds)) ? 'selected' : '' }}>
                  {{ $session->session_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Select Center(s)</label>
              <select name="center_ids[]" id="center_ids" class="form-select" multiple>
                @foreach($centers as $center)
                <option value="{{ $center->center_id }}" {{ in_array((string)$center->center_id, array_map('strval', $centerIds)) ? 'selected' : '' }}>
                  {{ $center->center_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Select Programme(s)</label>
              <select name="programme_ids[]" id="programme_ids" class="form-select" multiple>
                @foreach($programmes as $programme)
                <option value="{{ $programme->programme_id }}" {{ in_array((string)$programme->programme_id, array_map('strval', $programmeIds)) ? 'selected' : '' }}>
                  {{ $programme->programme_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Select Technology(s)</label>
              <select name="technology_ids[]" id="technology_ids" class="form-select" multiple>
                @foreach($technologies as $technology)
                <option value="{{ $technology->technology_id }}" {{ in_array((string)$technology->technology_id, array_map('strval', $technologyIds)) ? 'selected' : '' }}>
                  {{ $technology->technology_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Select Batch(es)</label>
              <select name="batch_ids[]" id="batch_ids" class="form-select" multiple>
                @foreach($batches as $batch)
                <option value="{{ $batch->batch_id }}" {{ in_array((string)$batch->batch_id, array_map('strval', $batchIds)) ? 'selected' : '' }}>
                  {{ $batch->batch_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-3 col-md-6">
              <label class="form-label">Search Student</label>
              <input type="text" name="search_student" class="form-control" value="{{ $searchStudent }}" placeholder="Name / Reg ID / College / Mobile">
            </div>

            <div class="col-lg-5 col-md-12 d-flex align-items-center gap-2">
              <button type="submit" class="btn btn-primary w-50">
                <i class="bi bi-search"></i> Fetch Attendance
              </button>
              <a href="{{ route('admin.viewattandence') }}" class="btn btn-secondary w-50">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-lg-3 col-md-6 col-sm-6 col-6">
        <div class="card summary-card total-card">
          <div class="card-body">
            <div class="summary-label">Total Students</div>
            <div class="summary-value total-text" id="totalStudents">{{ $totalStudents }}</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-6">
        <div class="card summary-card days-card">
          <div class="card-body">
            <div class="summary-label">Attendance Days</div>
            <div class="summary-value days-text" id="attendanceDays">{{ $attendanceDays }}</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-6">
        <div class="card summary-card present-card">
          <div class="card-body">
            <div class="summary-label">Overall Present</div>
            <div class="summary-value present-text" id="overallPresent">{{ $overallPresent }}</div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6 col-6">
        <div class="card summary-card absent-card">
          <div class="card-body">
            <div class="summary-label">Overall Absent</div>
            <div class="summary-value absent-text" id="overallAbsent">{{ $overallAbsent }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card table-card">
      <div class="card-body">
        <div class="section-title">Attendance List</div>

        @if($hasSheetData)
        <div class="table-responsive">
          <table id="attendanceSheetTable" class="table table-bordered table-striped align-middle attendance-sheet-table nowrap">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Reg ID</th>
                <th class="left-col">Student Name</th>
                <th class="left-col">Student College</th>
                <th>Student Mobile</th>
                <th>Average Present</th>

                @foreach($dateColumns as $dateKey)
                @php
                $dateDomId = str_replace('-', '', $dateKey);
                @endphp
                <th class="date-head">
                  <div class="date-title">{{ \Carbon\Carbon::parse($dateKey)->format('Y-m-d') }}</div>
                  <div class="count-chip present-chip" id="present-count-{{ $dateDomId }}">
                    {{ $dateSummary[$dateKey]['present'] ?? 0 }}
                  </div>
                  <div class="count-chip absent-chip" id="absent-count-{{ $dateDomId }}">
                    {{ $dateSummary[$dateKey]['absent'] ?? 0 }}
                  </div>
                </th>
                @endforeach
              </tr>
            </thead>

            <tbody>
              @foreach($studentRows as $index => $student)
              <tr data-student-id="{{ $student->stu_id }}" data-present-count="{{ $student->present_count }}" data-days="{{ $attendanceDays }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->stu_regid ?? '-' }}</td>
                <td class="left-col">{{ $student->stu_name ?? '-' }}</td>
                <td class="left-col">{{ $student->stu_college ?? '-' }}</td>
                <td>{{ $student->stu_mobile ?? '-' }}</td>
                <td>
                  @php
                  $avgClass = $student->average_present >= 75
                  ? 'avg-good'
                  : ($student->average_present >= 50 ? 'avg-mid' : 'avg-low');
                  @endphp

                  <span class="avg-badge student-average {{ $avgClass }}" id="avg-student-{{ $student->stu_id }}">
                    {{ number_format($student->average_present, 0) }}%
                  </span>
                </td>

                @foreach($dateColumns as $dateKey)
                @php
                $cell = $attendanceMap[$student->stu_id][$dateKey] ?? null;
                @endphp

                @if($cell)
                <td
                  class="attendance-cell attendance-toggle {{ $cell['attendance'] === 'P' ? 'is-present' : 'is-absent' }}"
                  data-attend-id="{{ $cell['attend_id'] }}"
                  data-status="{{ $cell['attendance'] }}"
                  data-student-id="{{ $student->stu_id }}"
                  data-date="{{ $dateKey }}"
                  title="Double click to toggle attendance">
                  <div class="mark-icon">
                    @if($cell['attendance'] === 'P')
                    <span class="status-pill present-pill">P</span>
                    @else
                    <span class="status-pill absent-pill">A</span>
                    @endif
                  </div>
                  <!-- <div class="by-text">dbl click</div> -->
                </td>
                @else
                <td class="attendance-cell empty-cell">—</td>
                @endif
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-5 text-muted fw-semibold">
          No attendance found for selected filters.
        </div>
        @endif
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

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

<script>
  $(function() {
    const csrfToken = "{{ csrf_token() }}";
    const hasSheetData = @json($hasSheetData);

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2200,
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

    function setChoiceOptions(instance, items, valueKey, labelKey) {
      instance.clearStore();
      instance.setChoices(
        (items || []).map(item => ({
          value: String(item[valueKey]),
          label: item[labelKey]
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
        success: cb
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

    if (hasSheetData) {
      $('#attendanceSheetTable').DataTable({
        destroy: true,
        scrollX: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: false,
        pageLength: 10,
        lengthMenu: [
          [10, 25, 50, 100],
          [10, 25, 50, 100]
        ],
        dom: "<'row align-items-center mb-3'<'col-md-2'l><'col-md-7 text-md-end text-start mt-2 mt-md-0'B><'col-md-3 ms-auto'f>>" +
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
            pageSize: 'A3'
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
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
          infoEmpty: "No entries available",
          zeroRecords: "No matching attendance found"
        }
      });
    }

    function getDateDomId(dateString) {
      return String(dateString).split('-').join('');
    }

    function renderCellIcon(status) {
      if (status === 'P') {
        return '<span class="status-pill present-pill">P</span>';
      }
      return '<span class="status-pill absent-pill">A</span>';
    }

    $(document).on('dblclick', '.attendance-toggle', function() {
      const $cell = $(this);
      const attendId = $cell.data('attend-id');
      const oldStatus = String($cell.data('status'));
      const studentId = String($cell.data('student-id'));
      const dateKey = String($cell.data('date'));

      if (!attendId) return;

      $.ajax({
        url: "{{ route('attendance.viewattandence.toggle') }}",
        type: "POST",
        data: {
          _token: csrfToken,
          attend_id: attendId
        },
        success: function(res) {
          if (!res.status) {
            showToast('error', res.message || 'Unable to update attendance.');
            return;
          }

          const newStatus = String(res.new_status);
          const dateDomId = getDateDomId(dateKey);

          $cell.data('status', newStatus);
          $cell.attr('data-status', newStatus);
          $cell.removeClass('is-present is-absent').addClass(newStatus === 'P' ? 'is-present' : 'is-absent');
          $cell.find('.mark-icon').html(renderCellIcon(newStatus));

          const $presentCount = $('#present-count-' + dateDomId);
          const $absentCount = $('#absent-count-' + dateDomId);
          const $overallPresent = $('#overallPresent');
          const $overallAbsent = $('#overallAbsent');

          let presentVal = parseInt($presentCount.text() || '0');
          let absentVal = parseInt($absentCount.text() || '0');
          let overallPresentVal = parseInt($overallPresent.text() || '0');
          let overallAbsentVal = parseInt($overallAbsent.text() || '0');

          const $row = $('tr[data-student-id="' + studentId + '"]');
          let rowPresentCount = parseInt($row.attr('data-present-count') || '0');
          const days = parseInt($row.attr('data-days') || '0');

          if (oldStatus === 'P' && newStatus === 'A') {
            presentVal = Math.max(0, presentVal - 1);
            absentVal += 1;
            overallPresentVal = Math.max(0, overallPresentVal - 1);
            overallAbsentVal += 1;
            rowPresentCount = Math.max(0, rowPresentCount - 1);
          } else if (oldStatus === 'A' && newStatus === 'P') {
            presentVal += 1;
            absentVal = Math.max(0, absentVal - 1);
            overallPresentVal += 1;
            overallAbsentVal = Math.max(0, overallAbsentVal - 1);
            rowPresentCount += 1;
          }

          $presentCount.text(presentVal);
          $absentCount.text(absentVal);
          $overallPresent.text(overallPresentVal);
          $overallAbsent.text(overallAbsentVal);

          $row.attr('data-present-count', rowPresentCount);

          const avg = days > 0 ? ((rowPresentCount / days) * 100).toFixed(2) : '0.00';
          const $avgBadge = $('#avg-student-' + studentId);
          $avgBadge.text(parseFloat(avg).toFixed(0) + '%');
          $avgBadge.removeClass('avg-good avg-mid avg-low');

          if (parseFloat(avg) >= 75) {
            $avgBadge.addClass('avg-good');
          } else if (parseFloat(avg) >= 50) {
            $avgBadge.addClass('avg-mid');
          } else {
            $avgBadge.addClass('avg-low');
          }

          showToast('success', 'Attendance updated successfully.');
        },
        error: function() {
          showToast('error', 'Attendance update failed.');
        }
      });
    });
  });
</script>
@endpush