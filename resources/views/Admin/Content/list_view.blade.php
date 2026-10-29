@extends('Admin.layouts.app')

@section('title', 'Content Bundles')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

<style>
  .bundle-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
    overflow: hidden;
    background: #fff;
  }

  .bundle-card .card-body {
    padding: 22px;
  }

  .filter-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
    background: #fff;
    margin-bottom: 20px;
  }

  .filter-card .card-body {
    padding: 22px;
  }

  .page-title {
    font-size: 26px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
  }

  .page-subtitle {
    color: #6b7280;
    margin-bottom: 0;
  }

  .stat-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #eef5ff;
    color: #1677ff;
    font-size: 12px;
    font-weight: 600;
  }

  .badge-soft-success {
    background: #ecfdf3;
    color: #027a48;
    border: 1px solid #abefc6;
  }

  .badge-soft-secondary {
    background: #f2f4f7;
    color: #475467;
    border: 1px solid #eaecf0;
  }

  .filter-note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  .choices__inner {
    min-height: 46px !important;
    border-radius: 12px !important;
  }

  .filter-card,
  .filter-card .card-body,
  .page-body,
  .main-content {
    overflow: visible !important;
  }

  .choices {
    position: relative;
    z-index: 1055;
  }

  .choices__inner {
    min-height: 46px !important;
    border-radius: 12px !important;
    position: relative;
    z-index: 1055;
  }

  .choices__list--dropdown,
  .choices__list[aria-expanded] {
    z-index: 99999 !important;
    position: absolute !important;
    background: #fff !important;
    border: 1px solid #d0d5dd !important;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12) !important;
  }

  .table-responsive,
  .bundle-card,
  .bundle-card .card-body {
    overflow: visible !important;
  }

  .filter-card {
    position: relative;
    z-index: 20;
  }

  .bundle-card {
    position: relative;
    z-index: 1;
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
      <div>
        <div class="page-title">Content Bundles</div>
        <p class="page-subtitle">Subject select karo. Topic optional hai. Blank ya All select karoge to sabhi topics ka data aayega.</p>
      </div>

      <div class="stat-chip">
        Total Bundles: {{ $bundles->count() }}
      </div>
    </div>

    <div class="card filter-card">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.content-bundles.index') }}">
          <div class="row g-3 align-items-start">
            <div class="col-md-4">
              <label class="form-label fw-bold">Subject</label>
              <select name="subject_id" id="subject_id" class="form-select">
                <option value="">Select Subject</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->subject_id }}" {{ (string)$selectedSubjectId === (string)$subject->subject_id ? 'selected' : '' }}>
                  {{ $subject->subject_name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-5">
              <label class="form-label fw-bold">Topic</label>
              <select name="topic_ids[]" id="topic_id" class="form-select" multiple></select>

            </div>

            <div class="col-md-3">
              <label class="form-label fw-bold d-block">&nbsp;</label>
              <div class="d-grid gap-2">
                <div class="row mt-2">
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary">Fetch</button>
                  </div>
                  <div class="col-sm-2">
                    <a href="{{ route('admin.content-bundles.index') }}" class="btn btn-outline-secondary btn-secondary text-light">Reset</a>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    @if(!$hasSearched)
    <div class="alert alert-info" style="
    z-index: -1000000;
">
      Pehle subject select karke <strong>Fetch</strong> karo, tabhi table me data dikhega.
    </div>
    @else
    <div class="card bundle-card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="bundleTable" class="table table-bordered table-striped align-middle nowrap w-100">
            <thead class="table-light">
              <tr>
                <th style="width:70px;">Sr. No.</th>
                <th>Subject</th>
                <th>Main Content</th>
                <th>Assignments</th>
                <th>Recordings</th>
                <th>Last Updated</th>
                <th style="width:140px;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bundles as $key => $row)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row->subject_name ?? '-' }}</td>
                <td>
                  @if(!empty($row->latest_content_id))
                  <span class="badge badge-soft-success">Available</span>
                  @else
                  <span class="badge badge-soft-secondary">No</span>
                  @endif
                </td>
                <td>{{ $row->assignment_count }}</td>
                <td>{{ $row->recording_count }}</td>
                <td>
                  {{ !empty($row->latest_updated_at) ? \Carbon\Carbon::parse($row->latest_updated_at)->format('d M Y h:i A') : '-' }}
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.content-bundles.show', [$row->subject_id, $row->topic_id]) }}" class="btn btn-sm btn-primary">
                      View
                    </a>
                    <a href="{{ route('admin.content-bundles.edit', [$row->subject_id, $row->topic_id]) }}" class="btn btn-sm btn-warning text-dark">
                      Edit
                    </a>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
  $(function() {
    const topicsUrlTemplate = @json(route('admin.content-bundles.topics', ['subjectId' => '__SUBJECT_ID__']));
    const initialTopics = @json($oldTopics ?? []);
    const selectedTopicIds = @json($selectedTopicIdsRaw ?? []);

    const subjectSelect = document.getElementById('subject_id');
    const topicSelect = document.getElementById('topic_id');

    new Choices(subjectSelect, {
      searchEnabled: true,
      itemSelectText: '',
      shouldSort: false,
      allowHTML: false
    });

    const topicChoice = new Choices(topicSelect, {
      removeItemButton: true,
      searchEnabled: true,
      itemSelectText: '',
      shouldSort: false,
      allowHTML: false,
      placeholder: true,
      placeholderValue: 'Select topic(s)'
    });

    function buildTopicsUrl(subjectId) {
      return topicsUrlTemplate.replace('__SUBJECT_ID__', subjectId);
    }

    function renderTopics(topics, selectedIds = []) {
      const selectedStrings = selectedIds.map(String);
      const choices = [{
        value: 'all',
        label: 'All',
        selected: selectedStrings.includes('all')
      }];

      topics.forEach(function(topic) {
        choices.push({
          value: String(topic.topic_id),
          label: topic.topic_name,
          selected: selectedStrings.includes(String(topic.topic_id))
        });
      });

      topicChoice.clearStore();
      topicChoice.setChoices(choices, 'value', 'label', true);
    }

    function clearTopics() {
      topicChoice.clearStore();
      topicChoice.setChoices([{
        value: 'all',
        label: 'All',
        selected: false
      }], 'value', 'label', true);
    }

    function loadTopics(subjectId, selectedIds = []) {
      if (!subjectId) {
        clearTopics();
        return;
      }

      $.ajax({
        url: buildTopicsUrl(subjectId),
        type: 'GET',
        success: function(response) {
          if (response.status) {
            renderTopics(response.topics, selectedIds);
          } else {
            clearTopics();
          }
        },
        error: function() {
          clearTopics();
        }
      });
    }

    subjectSelect.addEventListener('change', function() {
      loadTopics(this.value, []);
    });

    topicSelect.addEventListener('addItem', function(event) {
      const value = String(event.detail.value);

      if (value === 'all') {
        topicChoice.removeActiveItems();
        topicChoice.setChoiceByValue('all');
      } else {
        topicChoice.removeActiveItemsByValue('all');
      }
    });

    if (subjectSelect.value) {
      if (initialTopics.length > 0) {
        renderTopics(initialTopics, selectedTopicIds);
      } else {
        loadTopics(subjectSelect.value, selectedTopicIds);
      }
    } else {
      clearTopics();
    }

    if ($('#bundleTable').length) {
      $('#bundleTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        destroy: true,
        language: {
          emptyTable: 'No data found.'
        },
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
          }
        ]
      });
    }
  });
</script>
@endpush