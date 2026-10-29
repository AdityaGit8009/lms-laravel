@extends('Admin.layouts.app')

@section('title', 'Students')

@section('content')
<main class="main-content">
  <div class="page-body">

    <div class="page-hdr d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h1>Students</h1>
        <p class="mb-0">Manage student details.</p>
      </div>

      <div class="d-flex gap-2 flex-wrap">


        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
          <i class="bi bi-upload me-1"></i> Bulk Upload Students
        </button>

        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkUpupdateRegID">
          <i class="bi bi-upload me-1"></i> Bulk Update RegID
        </button>

        <button type="button" class="btn btn-primary text-end" data-bs-toggle="modal" data-bs-target="#bulkassignModal">
          <i class="bi bi-plus-circle me-1"></i> Bulk Assign
        </button>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
          <i class="bi bi-plus-circle me-1"></i> Add Student
        </button>
      </div>

    </div>
    <div class="row mb-4 d-flex">
      <div class="col-md-2">
        <button type="button" class="btn btn-primary text-end" id="showAllStudentsBtn">
          <i class="bi bi-people me-1"></i> Total Learners: <span id="totalLearnersCount">{{ $totalLearners }}</span>
        </button>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-warning text-end" id="showNoBatchStudentsBtn">
          <i class="bi bi-exclamation-circle me-1"></i> No Batch Assign: <span id="noBatchAssignedCount">{{ $noBatchAssignedCount }}</span>
        </button>
      </div>

      <div class="col-md-3 ms-md-auto text-light">
        <a href="{{ route('admin.blocked-deleted-students') }}" class="btn btn-danger">
          <i class="bi bi-person-x-fill me-1"></i> Blocked & Deleted Students
        </a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="datatable-wrap">
          <table id="studentsTable" class="table table-bordered table-striped align-middle nowrap w-100">
            <thead>
              <tr>
                <th>Sr.</th>
                <th>Photo</th>
                <th>Student Details</th>
                <th>Registration ID</th>
                <th>College</th>
                <th>Center</th>
                <th>Course</th>
                <th>Year</th>
                <th>Branch</th>
                <th>Gender</th>
                <th>Programme</th>
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

  .blocked-row .student-inline-info strong,
  .blocked-row .student-inline-info span {
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

  #studentsTable {
    min-width: 1900px !important;
    white-space: nowrap !important;
  }

  #studentsTable th,
  #studentsTable td {
    white-space: nowrap !important;
    vertical-align: middle;
  }

  .student-inline-info {
    white-space: nowrap !important;
  }

  #studentsTable tbody tr.child {
    display: none !important;
  }

  #studentsTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
  #studentsTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
    display: none !important;
  }

  .dataTables_wrapper {
    width: 100%;
  }

  .dataTables_scroll {
    overflow: auto !important;
  }

  .dataTables_scrollHeadInner,
  .dataTables_scrollHeadInner table,
  .dataTables_scrollBody table {
    min-width: 1900px !important;
  }

  .dataTables_scrollBody {
    overflow-x: auto !important;
    overflow-y: auto !important;
    padding-right: 18px !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: var(--btn-r) !important;
    background: var(--surface2) !important;
    border: 1px solid var(--border) !important;
    color: var(--muted) !important;
    font-size: .78rem !important;
    padding: 3px 3px !important;
    margin: 0 2px !important;
    cursor: pointer !important;
  }

  .active>.page-link,
  .page-link.active {
    padding: 5px 14px !important;
    border-radius: 50%;
    z-index: 3;
    color: var(--bs-pagination-active-color);
    background-color: var(--bs-pagination-active-bg);
    border-color: var(--bs-pagination-active-border-color);
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

  #studentsTable th:last-child,
  #studentsTable td:last-child {
    min-width: 110px !important;
    width: 110px !important;
    text-align: center;
    white-space: nowrap !important;
  }

  #studentsTable .action-cell {
    min-width: 90px !important;
    width: 90px !important;
    overflow: visible !important;
  }

  tr:nth-child(odd) td:nth-child(5) {
    width: 150px !important;
  }
</style>

<div class="modal fade" id="bulkassignModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <form id="bulkAssignForm" enctype="multipart/form-data">
        @csrf

        <div class="modal-header bulk-modal-header">
          <div>
            <h5 class="modal-title mb-1">Bulk Assign Students</h5>
            <p class="mb-0 text-muted small">Upload CSV with mobile numbers and assign matched students in one go.</p>
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

                  <a href="{{ route('admin.students.bulk-template') }}" class="btn btn-outline-primary btn-sm">
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

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="addStudentForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Name *</label>
              <input type="text" name="stu_name" id="add_stu_name" class="form-control">
              <small class="text-danger" id="add_stu_name_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Registration ID *</label>
              <input type="text" name="stu_regid" id="add_stu_regid" class="form-control">
              <small class="text-danger" id="add_stu_regid_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>College *</label>
              <input type="text" name="stu_college" id="add_stu_college" class="form-control">
              <small class="text-danger" id="add_stu_college_error"></small>
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
              <label>Course *</label>
              <select name="stu_course" id="add_stu_course" class="form-select">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="add_stu_course_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Year *</label>
              <select name="stu_course_year" id="add_stu_course_year" class="form-select">
                <option value="">Select Year</option>
              </select>
              <small class="text-danger" id="add_stu_course_year_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Branch *</label>
              <select name="stu_branch" id="add_stu_branch" class="form-select">
                <option value="">Select Branch</option>
              </select>
              <small class="text-danger" id="add_stu_branch_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Mobile *</label>
              <input type="text" name="stu_mobile" id="add_stu_mobile" class="form-control">
              <small class="text-danger" id="add_stu_mobile_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Email *</label>
              <input type="email" name="stu_email" id="add_stu_email" class="form-control">
              <small class="text-danger" id="add_stu_email_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Password *</label>
              <input type="text" class="form-control" value="Password@123" readonly>
            </div>

            <div class="col-md-6 mb-3">
              <label>Gender *</label>
              <select name="stu_gender" id="add_stu_gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
              <small class="text-danger" id="add_stu_gender_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Photo</label>
              <input type="file" name="stu_pic" id="add_stu_pic" class="form-control">
              <small class="text-danger" id="add_stu_pic_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Programme *</label>
              <select name="stu_pro" id="add_stu_pro" class="form-select">
                <option value="">Select Programme</option>
                @foreach($programmes as $programme)
                <option value="{{ $programme->programme_id }}">{{ $programme->programme_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="add_stu_pro_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Add Student</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="editStudentForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="edit_stu_id">

        <div class="modal-header">
          <h5 class="modal-title">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Name *</label>
              <input type="text" name="stu_name" id="edit_stu_name" class="form-control">
              <small class="text-danger" id="edit_stu_name_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Registration ID *</label>
              <input type="text" name="stu_regid" id="edit_stu_regid" class="form-control">
              <small class="text-danger" id="edit_stu_regid_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>College *</label>
              <input type="text" name="stu_college" id="edit_stu_college" class="form-control">
              <small class="text-danger" id="edit_stu_college_error"></small>
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
              <label>Course *</label>
              <select name="stu_course" id="edit_stu_course" class="form-select">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_stu_course_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Year *</label>
              <select name="stu_course_year" id="edit_stu_course_year" class="form-select">
                <option value="">Select Year</option>
              </select>
              <small class="text-danger" id="edit_stu_course_year_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Branch *</label>
              <select name="stu_branch" id="edit_stu_branch" class="form-select">
                <option value="">Select Branch</option>
              </select>
              <small class="text-danger" id="edit_stu_branch_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Mobile *</label>
              <input type="text" name="stu_mobile" id="edit_stu_mobile" class="form-control">
              <small class="text-danger" id="edit_stu_mobile_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Email *</label>
              <input type="email" name="stu_email" id="edit_stu_email" class="form-control">
              <small class="text-danger" id="edit_stu_email_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Gender *</label>
              <select name="stu_gender" id="edit_stu_gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
              <small class="text-danger" id="edit_stu_gender_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Programme *</label>
              <select name="stu_pro" id="edit_stu_pro" class="form-select">
                <option value="">Select Programme</option>
                @foreach($programmes as $programme)
                <option value="{{ $programme->programme_id }}">{{ $programme->programme_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="edit_stu_pro_error"></small>
            </div>

            <div class="col-md-6 mb-3">
              <label>Photo</label>
              <input type="file" name="stu_pic" id="edit_stu_pic" class="form-control">
              <small class="text-danger" id="edit_stu_pic_error"></small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Update Student</button>
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
            <h5 class="modal-title mb-1">Bulk Upload Students</h5>
            <a href="{{ route('admin.students.bulk-upload-template') }}" class="btn btn-outline-primary btn-sm">
              <i class="bi bi-download me-1"></i> Download Template
            </a>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-lg-6">
              <label class="form-label fw-semibold">Center <span class="text-danger">*</span></label>
              <select name="center_id" id="bulk_upload_center_id" class="form-select">
                <option value="">Select Center</option>
                @foreach($centers as $center)
                <option value="{{ $center->center_id }}">{{ $center->center_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="bulk_upload_center_id_error"></small>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
              <select name="stu_course" id="bulk_upload_stu_course" class="form-select">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                @endforeach
              </select>
              <small class="text-danger" id="bulk_upload_stu_course_error"></small>
            </div>

            <div class="col-lg-6">
              <label class="form-label fw-semibold">Year <span class="text-danger">*</span></label>
              <select name="stu_course_year" id="bulk_upload_stu_course_year" class="form-select">
                <option value="">Select Year</option>
              </select>
              <small class="text-danger" id="bulk_upload_stu_course_year_error"></small>
            </div>

            <div class="col-lg-6">
              <label class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
              <select name="stu_branch" id="bulk_upload_stu_branch" class="form-select">
                <option value="">Select Branch</option>
              </select>
              <small class="text-danger" id="bulk_upload_stu_branch_error"></small>
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
            <i class="bi bi-upload me-1"></i> Upload Students
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="bulkUpupdateRegID" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content bulk-assign-modal">
      <form id="bulkUpdateRegIDForm" enctype="multipart/form-data">
        @csrf

        <div class="modal-header bulk-modal-header">
          <div class="d-flex gap-4 align-items-center flex-wrap">
            <div>
              <h5 class="modal-title mb-1">Bulk Update RegID</h5>
              <p class="mb-0 text-muted small">Upload CSV with mobile number and RegID.</p>
            </div>

            <a href="{{ route('admin.students.bulk-regid-template') }}" class="btn btn-outline-primary btn-sm">
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
            <i class="bi bi-upload me-1"></i> Update RegID
          </button>
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
    let noBatchOnly = 0;

    const studentsTable = $('#studentsTable').DataTable({
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
        url: "{{ route('admin.students.data') }}",
        type: "GET",
        data: function(d) {
          d.no_batch_only = noBatchOnly;
        }
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
        },
        {
          extend: 'colvis',
          className: 'btn btn-sm btn-primary'
        }
      ],
      columnDefs: [{
          searchable: false,
          targets: [0, 1, 11]
        },
        {
          orderable: false,
          targets: '_all'
        }
      ],
      drawCallback: function() {
        $('#studentsTable').css('min-width', '1900px');
        $('.dataTables_scrollHeadInner table').css('min-width', '1900px');
        $('.dataTables_scrollBody table').css('min-width', '1900px');
      }
    });

    const addStudentModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addStudentModal'));
    const editStudentModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editStudentModal'));
    const bulkUploadModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUploadModal'));
    const bulkAssignModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkassignModal'));

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
      instance.setChoices(
        (items || []).map(item => ({
          value: item[valueKey],
          label: item[labelKey],
          selected: false,
          disabled: false
        })),
        'value',
        'label',
        true
      );
    }

    function clearBulkErrors() {
      $('#bulkAssignForm small.text-danger').text('');
    }

    function clearAddErrors() {
      $('#addStudentForm small.text-danger').text('');
    }

    function clearEditErrors() {
      $('#editStudentForm small.text-danger').text('');
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
        url: "{{ route('admin.students.assign.filter-data') }}",
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

    function loadBranches(courseId, targetSelector, selectedBranch = '') {
      $(targetSelector).html('<option value="">Select Branch</option>');

      if (!courseId) return;

      $.ajax({
        url: "{{ route('students.branches', ':id') }}".replace(':id', courseId),
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(res) {
          if (res.status && res.branches.length > 0) {
            $.each(res.branches, function(index, branch) {
              let selected = String(selectedBranch) === String(branch.branch_id) ? 'selected' : '';
              $(targetSelector).append(`<option value="${branch.branch_id}" ${selected}>${branch.branch_name}</option>`);
            });
          }
        }
      });
    }

    function loadYears(courseId, targetSelector, selectedYear = '') {
      $(targetSelector).html('<option value="">Select Year</option>');

      if (!courseId) return;

      $.ajax({
        url: "{{ route('students.years', ':id') }}".replace(':id', courseId),
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(res) {
          if (res.status && res.years.length > 0) {
            $.each(res.years, function(index, year) {
              let selected = selectedYear === year ? 'selected' : '';
              $(targetSelector).append(`<option value="${year}" ${selected}>${year}</option>`);
            });
          }
        },
        error: function() {
          $(targetSelector).html('<option value="">Select Year</option>');
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
        url: "{{ route('admin.students.bulk-assign') }}",
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
          studentsTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors || {}, function(key, value) {
              $('#edit_' + key + '_error').text(value[0]);
            });
          }

          let message = xhr.responseJSON?.message || 'Something went wrong.';
          if (xhr.responseJSON?.error) {
            console.error('Update error:', xhr.responseJSON.error);
            message += ' ' + xhr.responseJSON.error;
          }

          showToast('error', message);
        }
      });
    });

    $('#add_stu_course').on('change', function() {
      const courseId = $(this).val();
      loadBranches(courseId, '#add_stu_branch');
      loadYears(courseId, '#add_stu_course_year');
    });

    $('#edit_stu_course').on('change', function() {
      const courseId = $(this).val();
      loadBranches(courseId, '#edit_stu_branch');
      loadYears(courseId, '#edit_stu_course_year');
    });

    $('#bulk_upload_stu_course').on('change', function() {
      const courseId = $(this).val();
      loadYears(courseId, '#bulk_upload_stu_course_year');
      loadBranches(courseId, '#bulk_upload_stu_branch');
    });

    $('#addStudentForm').on('submit', function(e) {
      e.preventDefault();
      clearAddErrors();

      let formData = new FormData(this);

      $.ajax({
        url: "{{ route('api.admin.students.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Student added successfully.');
          addStudentModal.hide();
          $('#addStudentForm')[0].reset();
          studentsTable.ajax.reload(null, false);
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

    $(document).on('click', '.editStudentBtn', function() {
      const courseId = $(this).data('course');
      const selectedYear = $(this).data('year');
      const selectedBranch = $(this).data('branch');

      $('#edit_stu_id').val($(this).data('id'));
      $('#edit_stu_name').val($(this).data('name'));
      $('#edit_stu_regid').val($(this).data('regid'));
      $('#edit_stu_college').val($(this).data('college'));
      $('#edit_center_id').val($(this).data('center'));
      $('#edit_stu_course').val(courseId);
      $('#edit_stu_email').val($(this).data('email'));
      $('#edit_stu_mobile').val($(this).data('mobile'));
      $('#edit_stu_gender').val($(this).data('gender'));
      $('#edit_stu_pro').val($(this).data('programme'));

      loadBranches(courseId, '#edit_stu_branch', selectedBranch);
      loadYears(courseId, '#edit_stu_course_year', selectedYear);

      clearEditErrors();
      editStudentModal.show();
    });

    $('#editStudentForm').on('submit', function(e) {
      e.preventDefault();
      clearEditErrors();

      let id = $('#edit_stu_id').val();
      let formData = new FormData(this);
      formData.append('_method', 'PUT');

      $.ajax({
        url: "{{ route('api.admin.students.update', ':id') }}".replace(':id', id),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Student updated successfully.');
          editStudentModal.hide();
          studentsTable.ajax.reload(null, false);
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

    $(document).on('click', '.deleteStudentBtn', function() {
      let id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'Student will be removed from listing.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('api.admin.students.destroy', ':id') }}".replace(':id', id),
            type: "POST",
            data: {
              _method: 'DELETE',
              _token: "{{ csrf_token() }}"
            },
            success: function(res) {
              showToast('success', res.message || 'Student deleted successfully.');
              studentsTable.ajax.reload(null, false);
            },
            error: function(xhr) {
              showToast('error', xhr.responseJSON?.message || 'Delete failed.');
            }
          });
        }
      });
    });

    $(document).on('click', '.blockStudentBtn', function() {
      let id = $(this).data('id');

      $.ajax({
        url: "{{ route('students.block-toggle', ':id') }}".replace(':id', id),
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(res) {
          showToast('success', res.message || 'Block status updated.');
          studentsTable.ajax.reload(null, false);
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
        url: "{{ route('admin.students.bulk-upload') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'Students uploaded successfully.');
          bulkUploadModal.hide();
          $('#bulkUploadForm')[0].reset();
          $('#bulk_upload_stu_course_year').html('<option value="">Select Year</option>');
          $('#bulk_upload_stu_branch').html('<option value="">Select Branch</option>');
          studentsTable.ajax.reload(null, false);
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

    const bulkUpdateRegIDModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkUpupdateRegID'));

    function clearBulkRegIdErrors() {
      $('#bulkUpdateRegIDForm small.text-danger').text('');
    }

    $('#bulkUpdateRegIDForm').on('submit', function(e) {
      e.preventDefault();
      clearBulkRegIdErrors();

      let fileInput = $('#bulk_regid_csv_file').val().toLowerCase();
      if (!fileInput || !fileInput.endsWith('.csv')) {
        $('#bulk_regid_csv_file_error').text('Only CSV file is allowed.');
        return;
      }

      const formData = new FormData(this);

      $.ajax({
        url: "{{ route('admin.students.bulk-update-regid') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          Accept: 'application/json'
        },
        success: function(res) {
          showToast('success', res.message || 'RegID updated successfully.');
          bulkUpdateRegIDModal.hide();
          $('#bulkUpdateRegIDForm')[0].reset();
          studentsTable.ajax.reload(null, false);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            if (xhr.responseJSON?.errors?.csv_file) {
              $('#bulk_regid_csv_file_error').text(xhr.responseJSON.errors.csv_file[0]);
            }
          }
          showToast('error', xhr.responseJSON?.message || 'Something went wrong.');
        }
      });
    });
    $('#showNoBatchStudentsBtn').on('click', function() {
      noBatchOnly = 1;
      studentsTable.ajax.reload(null, true);

      $('#showNoBatchStudentsBtn').removeClass('btn-warning').addClass('btn-danger');
      $('#showAllStudentsBtn').removeClass('btn-success').addClass('btn-primary');
    });

    $('#showAllStudentsBtn').on('click', function() {
      noBatchOnly = 0;
      studentsTable.ajax.reload(null, true);

      $('#showAllStudentsBtn').removeClass('btn-primary').addClass('btn-success');
      $('#showNoBatchStudentsBtn').removeClass('btn-danger').addClass('btn-warning');
    });

  });
</script>



@endpush