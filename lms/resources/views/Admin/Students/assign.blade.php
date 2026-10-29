@extends('Admin.layouts.app')

@section('title', 'Assign Student')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1>Assign Student</h1>
        <p class="mb-0">
          <strong>{{ $student->stu_name }}</strong> | {{ $student->stu_regid }}
        </p>
      </div>
      <a href="{{ route('admin.students') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
      </a>
    </div>

    <form method="POST" action="{{ route('students.assign.save', $student->stu_id) }}" id="assignForm">
      @csrf

      <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white">
          <strong>Assign Session</strong>
        </div>
        <div class="card-body">
          <div class="row g-3">
            @forelse($sessions as $session)
            <div class="col-md-4">
              <div class="assign-box">
                <div>
                  <div class="fw-semibold">{{ $session->session_name }}</div>
                  <small class="text-muted text-capitalize">{{ $session->status }}</small>
                </div>
                <label class="switch">
                  <input
                    type="checkbox"
                    class="session-toggle"
                    name="session_ids[]"
                    value="{{ $session->session_id }}"
                    {{ in_array((int) $session->session_id, $selectedSessions) ? 'checked' : '' }}>
                  <span class="slider"></span>
                </label>
              </div>
            </div>
            @empty
            <div class="col-12">
              <div class="text-muted">No session found.</div>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white">
          <strong>Assign Programme</strong>
        </div>
        <div class="card-body">
          <div class="row g-3" id="programmeWrap">
            <div class="col-12">
              <div class="text-muted">Select session first.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white">
          <strong>Assign Technology</strong>
        </div>
        <div class="card-body">
          <div class="row g-3" id="technologyWrap">
            <div class="col-12">
              <div class="text-muted">Select programme first.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white">
          <strong>Assign Batch</strong>
        </div>
        <div class="card-body">
          <div class="row g-3" id="batchWrap">
            <div class="col-12">
              <div class="text-muted">Select technology first.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-check-circle me-1"></i> Save Assignment
        </button>
      </div>
    </form>
  </div>
</main>

<style>
  .assign-box {
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    min-height: 82px;
    background: #fff;
    transition: all 0.25s ease;
  }

  .assign-box:hover {
    border-color: #0d6efd;
    box-shadow: 0 8px 24px rgba(13, 110, 253, 0.08);
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 22px;
    flex-shrink: 0;
  }

  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #d1d5db;
    transition: .3s;
    border-radius: 34px;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    top: 2px;
    background: #fff;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
  }

  .switch input:checked+.slider {
    background: linear-gradient(135deg, #22c55e, #16a34a);
  }

  .switch input:checked+.slider:before {
    transform: translateX(28px);
  }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(function() {
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true
    });

    function showToast(icon, title) {
      Toast.fire({
        icon: icon,
        title: title
      });
    }

    let selectedProgrammeIds = @json(array_map('intval', $selectedProgrammes));
    let selectedTechnologyIds = @json(array_map('intval', $selectedTechnologies));
    let selectedBatchIds = @json(array_map('intval', $selectedBatches));

    function getCheckedValues(selector) {
      return $(selector + ':checked').map(function() {
        return parseInt($(this).val());
      }).get();
    }

    function setEmpty(target, text) {
      $(target).html(`
            <div class="col-12">
                <div class="text-muted">${text}</div>
            </div>
        `);
    }

    function keepOnlyValid(selectedIds, validIds) {
      return selectedIds.filter(id => validIds.includes(parseInt(id)));
    }

    function renderProgrammes(items) {
      if (!items.length) {
        selectedProgrammeIds = [];
        $('#programmeWrap').html(`<div class="col-12"><div class="text-muted">Select session first.</div></div>`);
        return;
      }

      const validIds = items.map(item => parseInt(item.programme_id));
      selectedProgrammeIds = keepOnlyValid(selectedProgrammeIds, validIds);

      let html = '';
      items.forEach(function(item) {
        const checked = selectedProgrammeIds.includes(parseInt(item.programme_id)) ? 'checked' : '';
        html += `
                <div class="col-md-4">
                    <div class="assign-box">
                        <div>
                            <div class="fw-semibold">${item.programme_name}</div>
                            <small class="text-muted">${item.duration ?? ''}</small>
                        </div>
                        <label class="switch">
                            <input type="checkbox" class="programme-toggle" name="programme_ids[]" value="${item.programme_id}" ${checked}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            `;
      });

      $('#programmeWrap').html(html);
    }

    function renderTechnologies(items) {
      if (!items.length) {
        selectedTechnologyIds = [];
        $('#technologyWrap').html(`<div class="col-12"><div class="text-muted">Select programme first.</div></div>`);
        return;
      }

      const validIds = items.map(item => parseInt(item.technology_id));
      selectedTechnologyIds = keepOnlyValid(selectedTechnologyIds, validIds);

      let html = '';
      items.forEach(function(item) {
        const checked = selectedTechnologyIds.includes(parseInt(item.technology_id)) ? 'checked' : '';
        html += `
                <div class="col-md-4">
                    <div class="assign-box">
                        <div>
                            <div class="fw-semibold">${item.technology_name}</div>
                            <small class="text-muted">Technology</small>
                        </div>
                        <label class="switch">
                            <input type="checkbox" class="technology-toggle" name="technology_ids[]" value="${item.technology_id}" ${checked}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            `;
      });

      $('#technologyWrap').html(html);
    }

    function renderBatches(items) {
      if (!items.length) {
        selectedBatchIds = [];
        $('#batchWrap').html(`<div class="col-12"><div class="text-muted">Select technology first.</div></div>`);
        return;
      }

      const validIds = items.map(item => parseInt(item.batch_id));
      selectedBatchIds = keepOnlyValid(selectedBatchIds, validIds);

      let html = '';
      items.forEach(function(item) {
        const checked = selectedBatchIds.includes(parseInt(item.batch_id)) ? 'checked' : '';
        html += `
                <div class="col-md-4">
                    <div class="assign-box">
                        <div>
                            <div class="fw-semibold">${item.batch_name}</div>
                            <small class="text-muted">Batch</small>
                        </div>
                        <label class="switch">
                            <input type="checkbox" class="batch-toggle" name="batch_ids[]" value="${item.batch_id}" ${checked}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            `;
      });

      $('#batchWrap').html(html);
    }

    function fetchAssignData(sessionIds, programmeIds, technologyIds) {
      return $.ajax({
        url: "{{ route('admin.students.assign.filter-data') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          session_ids: sessionIds,
          programme_ids: programmeIds,
          technology_ids: technologyIds
        }
      });
    }

    async function refreshCascade(level = 'session') {
      const sessionIds = getCheckedValues('.session-toggle');

      if (!sessionIds.length) {
        selectedProgrammeIds = [];
        selectedTechnologyIds = [];
        selectedBatchIds = [];

        setEmpty('#programmeWrap', 'Select session first.');
        setEmpty('#technologyWrap', 'Select programme first.');
        setEmpty('#batchWrap', 'Select technology first.');
        return;
      }

      try {
        if (level === 'session') {
          const res1 = await fetchAssignData(sessionIds, [], []);
          renderProgrammes(res1.programmes || []);

          selectedProgrammeIds = getCheckedValues('.programme-toggle');

          if (!selectedProgrammeIds.length) {
            setEmpty('#technologyWrap', 'Select programme first.');
            setEmpty('#batchWrap', 'Select technology first.');
            return;
          }

          const res2 = await fetchAssignData(sessionIds, selectedProgrammeIds, []);
          renderTechnologies(res2.technologies || []);

          selectedTechnologyIds = getCheckedValues('.technology-toggle');

          if (!selectedTechnologyIds.length) {
            setEmpty('#batchWrap', 'Select technology first.');
            return;
          }

          const res3 = await fetchAssignData(sessionIds, selectedProgrammeIds, selectedTechnologyIds);
          renderBatches(res3.batches || []);

          selectedBatchIds = getCheckedValues('.batch-toggle');
        }

        if (level === 'programme') {
          selectedProgrammeIds = getCheckedValues('.programme-toggle');

          if (!selectedProgrammeIds.length) {
            selectedTechnologyIds = [];
            selectedBatchIds = [];
            setEmpty('#technologyWrap', 'Select programme first.');
            setEmpty('#batchWrap', 'Select technology first.');
            return;
          }

          const res2 = await fetchAssignData(sessionIds, selectedProgrammeIds, []);
          renderTechnologies(res2.technologies || []);

          selectedTechnologyIds = getCheckedValues('.technology-toggle');

          if (!selectedTechnologyIds.length) {
            setEmpty('#batchWrap', 'Select technology first.');
            return;
          }

          const res3 = await fetchAssignData(sessionIds, selectedProgrammeIds, selectedTechnologyIds);
          renderBatches(res3.batches || []);

          selectedBatchIds = getCheckedValues('.batch-toggle');
        }

        if (level === 'technology') {
          selectedProgrammeIds = getCheckedValues('.programme-toggle');
          selectedTechnologyIds = getCheckedValues('.technology-toggle');

          if (!selectedTechnologyIds.length) {
            selectedBatchIds = [];
            setEmpty('#batchWrap', 'Select technology first.');
            return;
          }

          const res3 = await fetchAssignData(sessionIds, selectedProgrammeIds, selectedTechnologyIds);
          renderBatches(res3.batches || []);

          selectedBatchIds = getCheckedValues('.batch-toggle');
        }

        if (level === 'batch') {
          selectedBatchIds = getCheckedValues('.batch-toggle');
        }
      } catch (error) {
        showToast('error', 'Failed to load assign data.');
      }
    }

    refreshCascade('session');

    $(document).on('change', '.session-toggle', function() {
      refreshCascade('session');
    });

    $(document).on('change', '.programme-toggle', function() {
      refreshCascade('programme');
    });

    $(document).on('change', '.technology-toggle', function() {
      refreshCascade('technology');
    });

    $(document).on('change', '.batch-toggle', function() {
      refreshCascade('batch');
    });

    $('#assignForm').on('submit', function(e) {
      e.preventDefault();

      let formData = $(this).serialize();

      $.ajax({
        url: "{{ route('students.assign.save', $student->stu_id) }}",
        type: "POST",
        data: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Assign successful.');
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Something went wrong while saving assignment.');
        }
      });
    });
  });
</script>
@endpush