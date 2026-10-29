@extends('Admin.layouts.app')

@section('title', 'View Students')

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

  

  .btn-reset-custom:hover {
    background: #f9fafb;
    color: #111827;
  }


  .btn-fetch-custom:hover {
    color: #fff;
    transform: translateY(-1px);
  }

  #studentsTable th,
  #studentsTable td {
    vertical-align: middle;
    white-space: nowrap;
  }

  .dataTables_wrapper .dt-buttons .btn {
    margin-right: 6px;
    margin-bottom: 6px;
    border-radius: 10px !important;
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr mb-4">
      <div>
        <h1>View Students</h1>
        <p class="mb-0">Fetch learners using relational filters.</p>
      </div>
    </div>

    <div class="card filter-card mb-4">
      <div class="card-body">
        <div class="section-title">Fetch Learners</div>

        <div class="row g-3">
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

          <div class="col-lg-6 col-md-6">
            <label class="form-label">Select Technology(s)</label>
            <select id="technology_ids" class="form-select" multiple></select>
          </div>

          <div class="col-lg-6 col-md-6">
            <label class="form-label">Select Batch(es)</label>
            <select id="batch_ids" class="form-select" multiple></select>
          </div>

          <div class="col-12 d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <button type="button" id="resetFiltersBtn" class="btn-reset-custom btn btn-secondary border-0 px-4 py-2 rounded-1">
              <i class="bi bi-arrow-counterclockwise"></i>
              <span>Reset</span>
            </button>

            <button type="button" id="fetchStudentsBtn" class="btn-fetch-custom btn-primary border-0 px-4 py-2 rounded-1">
              <i class="bi bi-search"></i>
              <span>Fetch Students</span>
            </button>


          </div>
        </div>
      </div>
    </div>

    <div class="card table-card">
      <div class="card-body">
        <div class="section-title">View Students</div>

        <div class="table-responsive">
          <table id="studentsTable" class="table table-bordered table-striped align-middle nowrap w-100">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Registration ID</th>
                <th>Student Name</th>
                <th>Student College</th>
                <th>Student Mobile</th>
                <th>Student Email</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
  $(function() {
    const filterUrl = "{{ route('admin.viewstudents.filter-data') }}";
    const dataUrl = "{{ route('admin.viewstudents.data') }}";
    const csrfToken = "{{ csrf_token() }}";

    let hasFetched = false;
    let isUpdating = false;
    let debounceTimer = null;

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

    function getFilters() {
      return {
        session_ids: getSelectedValues(sessionChoices),
        center_ids: getSelectedValues(centerChoices),
        programme_ids: getSelectedValues(programmeChoices),
        technology_ids: getSelectedValues(technologyChoices),
        batch_ids: getSelectedValues(batchChoices)
      };
    }

    function resetChoice(instance) {
      instance.removeActiveItems();
      instance.clearChoices();
      instance.setChoices([], 'value', 'label', true);
    }

    function setChoiceOptions(instance, items, valueKey, labelKey, selectedValues) {
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

    function clearBelow(level) {
      if (level === 'session') {
        resetChoice(centerChoices);
        resetChoice(programmeChoices);
        resetChoice(technologyChoices);
        resetChoice(batchChoices);
      }

      if (level === 'center') {
        resetChoice(programmeChoices);
        resetChoice(technologyChoices);
        resetChoice(batchChoices);
      }

      if (level === 'programme') {
        resetChoice(technologyChoices);
        resetChoice(batchChoices);
      }

      if (level === 'technology') {
        resetChoice(batchChoices);
      }
    }

    function loadFilterData() {
      const filters = getFilters();

      $.ajax({
        url: filterUrl,
        type: 'POST',
        data: {
          _token: csrfToken,
          session_ids: filters.session_ids,
          center_ids: filters.center_ids,
          programme_ids: filters.programme_ids,
          technology_ids: filters.technology_ids,
          batch_ids: filters.batch_ids
        },
        success: function(res) {
          if (!res.status) {
            showToast('error', res.message || 'Unable to load filter data.');
            return;
          }

          isUpdating = true;

          setChoiceOptions(sessionChoices, res.sessions || [], 'session_id', 'session_name', filters.session_ids);
          setChoiceOptions(centerChoices, res.centers || [], 'center_id', 'center_name', filters.center_ids);
          setChoiceOptions(programmeChoices, res.programmes || [], 'programme_id', 'programme_name', filters.programme_ids);
          setChoiceOptions(technologyChoices, res.technologies || [], 'technology_id', 'technology_name', filters.technology_ids);
          setChoiceOptions(batchChoices, res.batches || [], 'batch_id', 'batch_name', filters.batch_ids);

          isUpdating = false;
        },
        error: function(xhr) {
          console.error('Filter data error:', xhr.responseText);
          showToast('error', 'Dropdown data load failed.');
        }
      });
    }

    function triggerCascade() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(loadFilterData, 200);
    }

    const studentsTable = $('#studentsTable').DataTable({
      processing: true,
      serverSide: true,
      deferLoading: 0,
      pageLength: 10,
      searching: true,
      ordering: false,
      responsive: false,
      scrollX: true,
      autoWidth: false,
      ajax: {
        url: dataUrl,
        type: 'POST',
        data: function(d) {
          const filters = getFilters();
          d._token = csrfToken;
          d.fetch = hasFetched ? 1 : 0;
          d.session_ids = filters.session_ids;
          d.center_ids = filters.center_ids;
          d.programme_ids = filters.programme_ids;
          d.technology_ids = filters.technology_ids;
          d.batch_ids = filters.batch_ids;
        },
        dataSrc: function(json) {
          return json.data || [];
        },
        error: function(xhr) {
          console.error('Students table error:', xhr.responseText);
          showToast('error', 'Student data load failed.');
        }
      },
      columns: [{
          data: 'sr_no',
          name: 'sr_no',
          searchable: false
        },
        {
          data: 'regid',
          name: 'regid'
        },
        {
          data: 'stu_name',
          name: 'stu_name'
        },
        {
          data: 'college_name',
          name: 'college_name'
        },
        {
          data: 'mobile',
          name: 'mobile'
        },
        {
          data: 'email',
          name: 'email'
        }
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
      language: {
        emptyTable: 'No students found.'
      }
    });

    $('#session_ids').on('change', function() {
      if (isUpdating) return;
      clearBelow('session');
      triggerCascade();
    });

    $('#center_ids').on('change', function() {
      if (isUpdating) return;
      clearBelow('center');
      triggerCascade();
    });

    $('#programme_ids').on('change', function() {
      if (isUpdating) return;
      clearBelow('programme');
      triggerCascade();
    });

    $('#technology_ids').on('change', function() {
      if (isUpdating) return;
      clearBelow('technology');
      triggerCascade();
    });

    $('#batch_ids').on('change', function() {
      if (isUpdating) return;
    });

    $('#fetchStudentsBtn').on('click', function() {
      hasFetched = true;
      studentsTable.ajax.reload();
    });

    $('#resetFiltersBtn').on('click', function() {
      hasFetched = false;

      resetChoice(sessionChoices);
      resetChoice(centerChoices);
      resetChoice(programmeChoices);
      resetChoice(technologyChoices);
      resetChoice(batchChoices);

      loadFilterData();
      studentsTable.ajax.reload();
    });

    loadFilterData();
  });
</script>
@endpush