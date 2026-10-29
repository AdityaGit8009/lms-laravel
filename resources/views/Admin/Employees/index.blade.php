@extends('Admin.layouts.app')

@section('title', 'Employees')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h1>Employees</h1>
        <p class="mb-0">Manage employee details.</p>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
          <i class="bi bi-upload me-1"></i> Bulk Upload Employees
        </button>

        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkUpdateRegID">
          <i class="bi bi-upload me-1"></i> Bulk Update Employee ID
        </button>

        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkassignModal">
          <i class="bi bi-plus-circle me-1"></i> Bulk Assign
        </button>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
          <i class="bi bi-plus-circle me-1"></i> Add Employee
        </button>
      </div>
    </div>

    <div class="row mb-3 d-flex">
      <div class="col-md-2">
        <button type="button" class="btn btn-primary text-end w-100" id="showAllEmployeesBtn">
          <i class="bi bi-people me-1"></i> Total Employees: <span id="totalEmployeesCount">{{ $totalEmployees }}</span>
        </button>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-warning text-end w-100" id="showNoBatchEmployeesBtn">
          <i class="bi bi-exclamation-circle me-1"></i> No Batch Assign: <span id="noBatchAssignedCount">{{ $noBatchAssignedCount }}</span>
        </button>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-12">
        <ul class="nav nav-pills employee-type-tabs gap-2" id="employeeTypeTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active employeeTypeTabBtn"
              id="internalEmployeesTab"
              type="button"
              data-emp-type="Internal">
              <i class="bi bi-person-badge me-1"></i>
              Internal Employees
              <span class="badge bg-light text-dark ms-1">{{ $internalEmployeesCount }}</span>
            </button>
          </li>

          <li class="nav-item" role="presentation">
            <button
              class="nav-link employeeTypeTabBtn"
              id="externalEmployeesTab"
              type="button"
              data-emp-type="External">
              <i class="bi bi-people-fill me-1"></i>
              External Employees
              <span class="badge bg-light text-dark ms-1">{{ $externalEmployeesCount }}</span>
            </button>
          </li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="datatable-wrap">
          <table id="employeesTable" class="table table-bordered table-striped align-middle nowrap w-100">
            <thead>
              <tr>
                <th>Sr.</th>
                <th>Photo</th>
                <th>Employee Details</th>
                <th>Employee ID</th>
                <th>Center</th>
                <th>Employee Type</th>
                <th>Gender</th>
                <th>Assigned Batches</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  .blocked-row td {
    color: #dc3545 !important;
    border-top: 1px solid #dc3545 !important;
    border-bottom: 1px solid #dc3545 !important;
    background: #fff5f5 !important;
  }

  .blocked-row td:first-child {
    border-left: 3px solid #dc3545 !important;
  }

  .blocked-row td:last-child {
    border-right: 3px solid #dc3545 !important;
  }

  .blocked-row .employee-inline-info strong,
  .blocked-row .employee-inline-info span {
    color: #dc3545 !important;
  }

  .blocked-row img {
    border: 2px solid #dc3545 !important;
  }

  .blocked-row .btn,
  .blocked-row .dropdown-toggle {
    border-color: #dc3545 !important;
  }

  .datatable-wrap {
    width: 100%;
    overflow-x: auto;
  }

  #employeesTable {
    min-width: 1500px !important;
    white-space: nowrap !important;
  }

  #employeesTable th,
  #employeesTable td {
    white-space: nowrap !important;
    vertical-align: middle;
  }

  .employee-inline-info {
    white-space: nowrap !important;
  }

  .bulk-assign-modal {
    border: 0;
    border-radius: 18px;
    overflow: hidden;
  }

  .bulk-modal-header {
    background: #f8fbff;
    border-bottom: 1px solid #e9eef5;
    padding: 18px 22px;
  }

  .bulk-upload-box {
    border: 1px dashed #d8e1ec;
    border-radius: 14px;
    padding: 18px;
    background: #fbfdff;
  }

  .choices {
    margin-bottom: 0;
  }

  .choices__inner {
    min-height: 46px !important;
    border-radius: 12px !important;
    border: 1px solid #ced4da !important;
    background: #fff !important;
    padding: 6px 10px !important;
  }

  .choices__list--multiple .choices__item {
    border-radius: 14px !important;
    padding: 4px 10px !important;
    font-size: 12px !important;
  }

  .choices__list--dropdown,
  .choices__list[aria-expanded] {
    border-radius: 12px !important;
    overflow: hidden;
  }

  .employee-type-tabs .nav-link {
    border: 1px solid #dbe5f1;
    background: #f8fbff;
    color: #344054;
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 18px;
  }

  .employee-type-tabs .nav-link.active {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
  }

  .employee-type-tabs .nav-link.active .badge {
    background: #fff !important;
    color: #0d6efd !important;
  }
</style>

<div class="modal fade" id="bulkassignModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <form id="bulkAssignForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bulk-modal-header">
          <div>
            <h5 class="modal-title mb-1">Bulk Assign Employees</h5>
            <p class="mb-0 text-muted small">Upload CSV with mobile numbers and assign matched employees in one go.</p>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-lg-6">
              <label class="form-label fw-semibold">Session <span class="text-danger">*</span></label>
              <select name="session_ids[]" id="bulk_session_ids" class="form-select bulk-choices" multiple>
                @foreach($sessions as $session)
                <option value="{{ $session->session_id }}">{{ $session->session_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="bulk_session_ids_error"></small>
            </div>

            <div class="col-lg-6">
              <label class="form-label fw-semibold">Programme <span class="text-danger">*</span></label>
              <select name="programme_ids[]" id="bulk_programme_ids" class="form-select bulk-choices" multiple></select>
              <small class="text-danger" id="bulk_programme_ids_error"></small>
            </div>

            <div class="col-lg-6">
              <label class="form-label fw-semibold">Technology <span class="text-danger">*</span></label>
              <select name="technology_ids[]" id="bulk_technology_ids" class="form-select bulk-choices" multiple></select>
              <small class="text-danger" id="bulk_technology_ids_error"></small>
            </div>

            <div class="col-lg-6">
              <label class="form-label fw-semibold">Batch <span class="text-danger">*</span></label>
              <select name="batch_ids[]" id="bulk_batch_ids" class="form-select bulk-choices" multiple></select>
              <small class="text-danger" id="bulk_batch_ids_error"></small>
            </div>

            <div class="col-12">
              <div class="bulk-upload-box">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                  <div>
                    <label class="form-label fw-semibold mb-1">CSV File <span class="text-danger">*</span></label>
                    <div class="small text-muted">Download template, mobile numbers fill karo, phir upload karo.</div>
                  </div>
                  <a href="{{ route('admin.employees.bulk-template') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Download
                  </a>
                </div>
                <input type="file" name="csv_file" id="bulk_csv_file" class="form-control" accept=".csv,text/csv">
                <small class="text-danger" id="bulk_csv_file_error"></small>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-upload me-1"></i> Upload & Assign
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="addEmployeeForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Employee Name *</label>
              <input type="text" name="emp_name" id="add_emp_name" class="form-control">
              <small class="text-danger" id="add_emp_name_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Employee ID *</label>
              <input type="text" name="emp_regid" id="add_emp_regid" class="form-control">
              <small class="text-danger" id="add_emp_regid_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Center *</label>
              <select name="center_id" id="add_center_id" class="form-select">
                <option value="">Select Center</option>
                @foreach($centers as $center)
                <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="add_center_id_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Mobile *</label>
              <input type="text" name="emp_mobile" id="add_emp_mobile" class="form-control">
              <small class="text-danger" id="add_emp_mobile_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Email *</label>
              <input type="email" name="emp_email" id="add_emp_email" class="form-control">
              <small class="text-danger" id="add_emp_email_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Password *</label>
              <input type="text" class="form-control" value="Password@123" readonly>
            </div>

            <div class="col-md-6 mb-3">
              <label>Gender *</label>
              <select name="emp_gender" id="add_emp_gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
              <small class="text-danger" id="add_emp_gender_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Employee Type *</label>
              <select name="emp_type" id="add_emp_type" class="form-select">
                <option value="">Select Employee Type</option>
                <option value="Internal">Internal</option>
                <option value="External">External</option>
              </select>
              <small class="text-danger" id="add_emp_type_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Photo</label>
              <input type="file" name="emp_pic" id="add_emp_pic" class="form-control">
              <small class="text-danger" id="add_emp_pic_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Add Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="editEmployeeForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="edit_emp_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Employee Name *</label>
              <input type="text" name="emp_name" id="edit_emp_name" class="form-control">
              <small class="text-danger" id="edit_emp_name_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Employee ID *</label>
              <input type="text" name="emp_regid" id="edit_emp_regid" class="form-control">
              <small class="text-danger" id="edit_emp_regid_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Center *</label>
              <select name="center_id" id="edit_center_id" class="form-select">
                <option value="">Select Center</option>
                @foreach($centers as $center)
                <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_center_id_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Mobile *</label>
              <input type="text" name="emp_mobile" id="edit_emp_mobile" class="form-control">
              <small class="text-danger" id="edit_emp_mobile_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Email *</label>
              <input type="email" name="emp_email" id="edit_emp_email" class="form-control">
              <small class="text-danger" id="edit_emp_email_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Gender *</label>
              <select name="emp_gender" id="edit_emp_gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
              <small class="text-danger" id="edit_emp_gender_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Employee Type *</label>
              <select name="emp_type" id="edit_emp_type" class="form-select">
                <option value="">Select Employee Type</option>
                <option value="Internal">Internal</option>
                <option value="External">External</option>
              </select>
              <small class="text-danger" id="edit_emp_type_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Photo</label>
              <input type="file" name="emp_pic" id="edit_emp_pic" class="form-control">
              <small class="text-danger" id="edit_emp_pic_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Update Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <form id="bulkUploadForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bulk-modal-header">
          <div class="d-flex gap-5">
            <h5 class="modal-title mb-1">Bulk Upload Employees</h5>
            <a href="{{ route('admin.employees.bulk-upload-template') }}" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-download me-1"></i> Download Template
            </a>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-lg-12">
              <label class="form-label fw-semibold">Center <span class="text-danger">*</span></label>
              <select name="center_id" id="bulk_upload_center_id" class="form-select">
                <option value="">Select Center</option>
                @foreach($centers as $center)
                <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="bulk_upload_center_id_error"></small>
            </div>
            <div class="col-12">
              <div class="bulk-upload-box">
                <input type="file" name="csv_file" id="bulk_upload_csv_file" class="form-control" accept=".csv,text/csv">
                <small class="text-danger" id="bulk_upload_csv_file_error"></small>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-upload me-1"></i> Upload Employees
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="bulkUpdateRegID" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <form id="bulkUpdateRegIDForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bulk-modal-header">
          <div class="d-flex gap-4 align-items-center flex-wrap">
            <div>
              <h5 class="modal-title mb-1">Bulk Update Employee ID</h5>
              <p class="mb-0 text-muted small">Upload CSV with mobile number and Employee ID.</p>
            </div>
            <a href="{{ route('admin.employees.bulk-regid-template') }}" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-download me-1"></i> Download Template
            </a>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <div class="bulk-upload-box">
                <label class="form-label fw-semibold mb-2">CSV File <span class="text-danger">*</span></label>
                <input type="file" name="csv_file" id="bulk_regid_csv_file" class="form-control" accept=".csv,text/csv">
                <small class="text-danger" id="bulk_regid_csv_file_error"></small>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-upload me-1"></i> Update Employee ID
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="AssignedBatchesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <div class="modal-header bulk-modal-header">
        <div>
          <h5 class="modal-title mb-1">Assigned Batches</h5>
          <p class="mb-0 text-muted small" id="assignedBatchEmployeeInfo">Employee assignment details</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle mb-0">
            <thead>
              <tr>
                <th>Sr.</th>
                <th>Session</th>
                <th>Programme</th>
                <th>Technology</th>
                <th>Batch</th>
              </tr>
            </thead>
            <tbody id="assignedBatchesTableBody">
              <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(function() {
    let noBatchOnly = 0;
    let currentEmpType = 'Internal';

    const employeesTable = $('#employeesTable').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      lengthMenu: [
        [10],
        [10]
      ],
      searching: true,
      ordering: false,
      info: true,
      responsive: false,
      scrollX: true,
      autoWidth: false,
      scrollCollapse: true,
      ajax: {
        url: "{{ route('admin.employees.data') }}",
        type: "GET",
        data: function(d) {
          d.no_batch_only = noBatchOnly;
          d.emp_type = currentEmpType;
        }
      },
      dom: "<'row align-items-center mb-3'<'col-md-2'l><'col-md-6 text-md-end text-start mt-2 mt-md-0'B><'col-md-3 ms-auto'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
        searchable: false,
        targets: [0, 1, 8]
      }, {
        orderable: false,
        targets: '_all'
      }]
    });

    const addEmployeeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addEmployeeModal'));
    const editEmployeeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editEmployeeModal'));
    const bulkUploadModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUploadModal'));
    const bulkAssignModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkassignModal'));
    const AssignedBatchesModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('AssignedBatchesModal'));

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true
    });

    function showToast(icon, title) {
      Toast.fire({
        icon,
        title
      });
    }

    let bulkChoices = {
      session: null,
      programme: null,
      technology: null,
      batch: null
    };

    function initBulkChoices() {
      if (!bulkChoices.session) {
        bulkChoices.session = new Choices('#bulk_session_ids', {
          removeItemButton: true,
          searchEnabled: true,
          searchPlaceholderValue: 'Search',
          placeholder: true,
          placeholderValue: 'Select',
          shouldSort: false,
          itemSelectText: ''
        });
      }
      if (!bulkChoices.programme) {
        bulkChoices.programme = new Choices('#bulk_programme_ids', {
          removeItemButton: true,
          searchEnabled: true,
          searchPlaceholderValue: 'Search',
          placeholder: true,
          placeholderValue: 'Select',
          shouldSort: false,
          itemSelectText: ''
        });
      }
      if (!bulkChoices.technology) {
        bulkChoices.technology = new Choices('#bulk_technology_ids', {
          removeItemButton: true,
          searchEnabled: true,
          searchPlaceholderValue: 'Search',
          placeholder: true,
          placeholderValue: 'Select',
          shouldSort: false,
          itemSelectText: ''
        });
      }
      if (!bulkChoices.batch) {
        bulkChoices.batch = new Choices('#bulk_batch_ids', {
          removeItemButton: true,
          searchEnabled: true,
          searchPlaceholderValue: 'Search',
          placeholder: true,
          placeholderValue: 'Select',
          shouldSort: false,
          itemSelectText: ''
        });
      }
    }

    function setChoices(instance, items, valueKey, labelKey) {
      if (!instance) return;
      instance.clearChoices();
      instance.setChoices((items || []).map(item => ({
        value: item[valueKey],
        label: item[labelKey],
        selected: false,
        disabled: false
      })), 'value', 'label', true);
    }

    function clearBulkErrors() {
      $('#bulkAssignForm small.text-danger').text('');
    }

    function clearAddErrors() {
      $('#addEmployeeForm small.text-danger').text('');
    }

    function clearEditErrors() {
      $('#editEmployeeForm small.text-danger').text('');
    }

    function clearBulkUploadErrors() {
      $('#bulkUploadForm small.text-danger').text('');
    }

    function resetBulkDropdowns() {
      if (bulkChoices.programme) bulkChoices.programme.clearChoices();
      if (bulkChoices.technology) bulkChoices.technology.clearChoices();
      if (bulkChoices.batch) bulkChoices.batch.clearChoices();
      $('#bulk_csv_file').val('');
    }

    function loadBulkAssignFilters() {
      const sessionIds = bulkChoices.session ? bulkChoices.session.getValue(true) : [];
      const programmeIds = bulkChoices.programme ? bulkChoices.programme.getValue(true) : [];
      const technologyIds = bulkChoices.technology ? bulkChoices.technology.getValue(true) : [];

      $.ajax({
        url: "{{ route('admin.employees.assign.filter-data') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          session_ids: Array.isArray(sessionIds) ? sessionIds : [sessionIds],
          programme_ids: Array.isArray(programmeIds) ? programmeIds : [programmeIds],
          technology_ids: Array.isArray(technologyIds) ? technologyIds : [technologyIds]
        },
        success: function(res) {
          if (res.status) {
            setChoices(bulkChoices.programme, res.programmes || [], 'programme_id', 'programme_name');
            setChoices(bulkChoices.technology, res.technologies || [], 'technology_id', 'technology_name');
            setChoices(bulkChoices.batch, res.batches || [], 'batch_id', 'batch_name');
          }
        }
      });
    }

    $('#bulkassignModal').on('shown.bs.modal', function() {
      initBulkChoices();
    });

    $(document).on('change', '#bulk_session_ids', function() {
      if (bulkChoices.programme) bulkChoices.programme.clearChoices();
      if (bulkChoices.technology) bulkChoices.technology.clearChoices();
      if (bulkChoices.batch) bulkChoices.batch.clearChoices();
      loadBulkAssignFilters();
    });

    $(document).on('change', '#bulk_programme_ids', function() {
      if (bulkChoices.technology) bulkChoices.technology.clearChoices();
      if (bulkChoices.batch) bulkChoices.batch.clearChoices();
      loadBulkAssignFilters();
    });

    $(document).on('change', '#bulk_technology_ids', function() {
      if (bulkChoices.batch) bulkChoices.batch.clearChoices();
      loadBulkAssignFilters();
    });

    $('#addEmployeeForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();
      let formData = new FormData(this);

      $.ajax({
        url: "{{ route('api.admin.employees.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Employee added successfully.');
          addEmployeeModal.hide();
          $('#addEmployeeForm')[0].reset();
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#add_' + key + '_error').text(value[0]);
            });
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });

    $(document).on('click', '.editEmployeeBtn', function() {
      $('#edit_emp_id').val($(this).data('id'));
      $('#edit_emp_name').val($(this).data('name'));
      $('#edit_emp_regid').val($(this).data('regid'));
      $('#edit_center_id').val($(this).data('center'));
      $('#edit_emp_email').val($(this).data('email'));
      $('#edit_emp_mobile').val($(this).data('mobile'));
      $('#edit_emp_gender').val($(this).data('gender'));
      $('#edit_emp_type').val($(this).data('type'));
      clearEditErrors();
      editEmployeeModal.show();
    });

    $('#editEmployeeForm').on('submit', function(e) {
      e.preventDefault();
      clearEditErrors();
      let id = $('#edit_emp_id').val();
      let formData = new FormData(this);
      formData.append('_method', 'PUT');

      $.ajax({
        url: "{{ route('api.admin.employees.update', ':id') }}".replace(':id', id),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Employee updated successfully.');
          editEmployeeModal.hide();
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#edit_' + key + '_error').text(value[0]);
            });
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });

    $(document).on('click', '.deleteEmployeeBtn', function() {
      let id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'Employee will be removed from listing.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('api.admin.employees.destroy', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
              _method: 'DELETE',
              _token: "{{ csrf_token() }}"
            },
            success: function(res) {
              showToast('success', res.message || 'Employee deleted successfully.');
              employeesTable.ajax.reload(null, false);
            },
            error: function(xhr) {
              showToast('error', xhr.responseJSON?.message || 'Delete failed.');
            }
          });
        }
      });
    });

    $(document).on('click', '.blockEmployeeBtn', function() {
      let id = $(this).data('id');

      $.ajax({
        url: "{{ route('employees.block-toggle', ':id') }}".replace(':id', id),
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(res) {
          showToast('success', res.message || 'Block status updated.');
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Block status update failed.');
        }
      });
    });

    $('#bulkUploadForm').on('submit', function(e) {
      e.preventDefault();
      clearBulkUploadErrors();

      let fileInput = $('#bulk_upload_csv_file').val().toLowerCase();
      if (!fileInput || !fileInput.endsWith('.csv')) {
        $('#bulk_upload_csv_file_error').text('Only CSV file is allowed.');
        return;
      }

      const formData = new FormData(this);
      $.ajax({
        url: "{{ route('admin.employees.bulk-upload') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Employees uploaded successfully.');
          bulkUploadModal.hide();
          $('#bulkUploadForm')[0].reset();
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#bulk_upload_' + key + '_error').text(value[0]);
            });
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });

    $('#bulkAssignForm').on('submit', function(e) {
      e.preventDefault();
      clearBulkErrors();

      let fileInput = $('#bulk_csv_file').val().toLowerCase();
      if (!fileInput || !fileInput.endsWith('.csv')) {
        $('#bulk_csv_file_error').text('Only CSV file is allowed.');
        return;
      }

      const formData = new FormData(this);
      $.ajax({
        url: "{{ route('admin.employees.bulk-assign') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Bulk assign completed successfully.');
          bulkAssignModal.hide();
          $('#bulkAssignForm')[0].reset();
          resetBulkDropdowns();
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#bulk_' + key + '_error').text(value[0]);
            });
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });

    $('#bulkUpdateRegIDForm').on('submit', function(e) {
      e.preventDefault();
      $('#bulk_regid_csv_file_error').text('');

      let fileInput = $('#bulk_regid_csv_file').val().toLowerCase();
      if (!fileInput || !fileInput.endsWith('.csv')) {
        $('#bulk_regid_csv_file_error').text('Only CSV file is allowed.');
        return;
      }

      const formData = new FormData(this);
      $.ajax({
        url: "{{ route('admin.employees.bulk-regid-update') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Employee ID updated successfully.');
          $('#bulkUpdateRegID').modal('hide');
          $('#bulkUpdateRegIDForm')[0].reset();
          employeesTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#' + key + '_error').text(value[0]);
            });
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });

    $('#showAllEmployeesBtn').on('click', function() {
      noBatchOnly = 0;
      employeesTable.ajax.reload();
    });

    $('#showNoBatchEmployeesBtn').on('click', function() {
      noBatchOnly = 1;
      employeesTable.ajax.reload();
    });

    $(document).on('click', '.employeeTypeTabBtn', function() {
      $('.employeeTypeTabBtn').removeClass('active');
      $(this).addClass('active');
      currentEmpType = $(this).data('emp-type');
      employeesTable.ajax.reload();
    });

    $(document).on('click', '.viewAssignedBatchesBtn', function() {
      let id = $(this).data('id');
      let name = $(this).data('name');
      let regid = $(this).data('regid');

      $('#assignedBatchEmployeeInfo').html(
        '<strong>Name:</strong> ' + name + ' &nbsp; | &nbsp; <strong>Employee ID:</strong> ' + regid
      );

      $('#assignedBatchesTableBody').html(`
        <tr>
          <td colspan="5" class="text-center text-muted">Loading...</td>
        </tr>
      `);

      $.ajax({
        url: "{{ route('admin.employees.assigned-batches', ':id') }}".replace(':id', id),
        type: "GET",
        success: function(res) {
          let html = '';

          if (res.status && res.data && res.data.length > 0) {
            $.each(res.data, function(index, row) {
              html += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${row.session_name ?? '-'}</td>
                  <td>${row.programme_name ?? '-'}</td>
                  <td>${row.technology_name ?? '-'}</td>
                  <td>${row.batch_name ?? '-'}</td>
                </tr>
              `;
            });
          } else {
            html = `
              <tr>
                <td colspan="5" class="text-center text-muted">No assigned batch found.</td>
              </tr>
            `;
          }

          $('#assignedBatchesTableBody').html(html);
          AssignedBatchesModal.show();
        },
        error: function(xhr) {
          $('#assignedBatchesTableBody').html(`
            <tr>
              <td colspan="5" class="text-center text-danger">Failed to load assigned batches.</td>
            </tr>
          `);
          AssignedBatchesModal.show();
        }
      });
    });
  });
</script>
@endpush