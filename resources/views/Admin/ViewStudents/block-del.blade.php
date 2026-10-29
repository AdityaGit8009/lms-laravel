@extends('Admin.layouts.app')

@section('title', 'Blocked & Deleted Students')

@push('styles')
<style>
  .table-card {
    border: 0;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    overflow: hidden;
    background: #fff;
  }

  .table-card .card-body {
    padding: 1.5rem;
  }

  .section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
  }

  .top-tabs-wrap {
    margin-bottom: 16px;
  }

  .nav-tabs-custom {
    border: 0;
    gap: 10px;
  }

  .nav-tabs-custom .nav-link {
    border: 1px solid #d0d5dd;
    background: #fff;
    color: #344054;
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 18px;
  }

  .nav-tabs-custom .nav-link.active {
    background: #eef4ff;
    color: #4f46e5;
    border-color: #c7d2fe;
    box-shadow: 0 6px 14px rgba(79, 70, 229, 0.10);
  }

  #blockedStudentsTable th,
  #blockedStudentsTable td,
  #deletedStudentsTable th,
  #deletedStudentsTable td {
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
        <h1>Blocked & Deleted Students</h1>
        <p class="mb-0">Students having blocked or deleted status.</p>
      </div>
    </div>

    <div class="top-tabs-wrap">
      <ul class="nav nav-tabs nav-tabs-custom" id="studentTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked-pane" type="button" role="tab" aria-controls="blocked-pane" aria-selected="true">
            Blocked Students
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="deleted-tab" data-bs-toggle="tab" data-bs-target="#deleted-pane" type="button" role="tab" aria-controls="deleted-pane" aria-selected="false">
            Deleted Students
          </button>
        </li>
      </ul>
    </div>

    <div class="card table-card">
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="blocked-pane" role="tabpanel" aria-labelledby="blocked-tab">
            <div class="section-title">Blocked Students</div>
            <div class="table-responsive">
              <table id="blockedStudentsTable" class="table table-bordered table-striped align-middle nowrap w-100">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Registration ID</th>
                    <th>Student Name</th>
                    <th>Student College</th>
                    <th>Student Mobile</th>
                    <th>Student Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="deleted-pane" role="tabpanel" aria-labelledby="deleted-tab">
            <div class="section-title">Deleted Students</div>
            <div class="table-responsive">
              <table id="deletedStudentsTable" class="table table-bordered table-striped align-middle nowrap w-100">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Registration ID</th>
                    <th>Student Name</th>
                    <th>Student College</th>
                    <th>Student Mobile</th>
                    <th>Student Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(function() {
    function tableConfig(type) {
      return {
        processing: true,
        serverSide: true,
        pageLength: 10,
        searching: true,
        ordering: false,
        responsive: false,
        scrollX: true,
        autoWidth: false,
        ajax: {
          url: "{{ route('admin.blocked-deleted-students.data') }}",
          type: "POST",
          data: function(d) {
            d._token = "{{ csrf_token() }}";
            d.type = type;
          },
          error: function(xhr) {
            console.error(xhr.responseText);
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
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ],
        columnDefs: [{
          targets: 6,
          render: function(data) {
            return data;
          }
        }],
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
          emptyTable: type === 'blocked' ? 'No blocked students found.' : 'No deleted students found.'
        }
      };
    }

    const blockedTable = $('#blockedStudentsTable').DataTable(tableConfig('blocked'));
    const deletedTable = $('#deletedStudentsTable').DataTable(tableConfig('deleted'));

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
      $.fn.dataTable.tables({
        visible: true,
        api: true
      }).columns.adjust();
    });

    $(document).on('click', '.unblock-btn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Unblock student?',
        text: 'This student will be active again.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Unblock'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.blocked-deleted-students.unblock', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function(res) {
              if (res.status) {
                Swal.fire('Success', res.message, 'success');
                blockedTable.ajax.reload(null, false);
                deletedTable.ajax.reload(null, false);
              } else {
                Swal.fire('Error', res.message, 'error');
              }
            }
          });
        }
      });
    });

    $(document).on('click', '.restore-btn', function() {
      const id = $(this).data('id');

      Swal.fire({
        title: 'Restore student?',
        text: 'This deleted student will be restored.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Restore'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.blocked-deleted-students.restore', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function(res) {
              if (res.status) {
                Swal.fire('Success', res.message, 'success');
                blockedTable.ajax.reload(null, false);
                deletedTable.ajax.reload(null, false);
              } else {
                Swal.fire('Error', res.message, 'error');
              }
            }
          });
        }
      });
    });
  });
</script>
@endpush