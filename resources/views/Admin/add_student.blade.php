@extends('Admin.layouts.app')

@section('title', 'Add Student')

@section('content')
@php
$studentTags = [
'Scholarship Student',
'Corporate Trainee',
'Repeat Student',
'Referral',
'VIP Student'
];
@endphp

<main class="main-content">
    <div class="page-body">

        <div class="page-hdr">
            <div>
                <div class="breadcrumb-bar">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('students') }}">Students</a>
                    <span>/</span>
                    <span>Add Student</span>
                </div>
                <h1>Add New Student</h1>
                <p>Register a new student manually and enroll them in courses.</p>
            </div>
            <div class="page-hdr-right">
                <a href="{{ route('students') }}" class="btn btn-ghost btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
                <button class="btn btn-ghost btn-sm" onclick="showToast('Importing CSV...','info')" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Bulk Import
                </button>
                <button class="btn btn-primary btn-sm" onclick="submitStudent()">
                    <i class="bi bi-person-plus-fill me-1"></i>Add Student
                </button>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-8">

                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-person-fill" style="color:var(--accent)"></i>Personal Information
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name <span class="req">*</span></label>
                                <input type="text" class="form-control" id="fname" placeholder="e.g. Priya">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="req">*</span></label>
                                <input type="text" class="form-control" id="lname" placeholder="e.g. Sharma">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email Address <span class="req">*</span></label>
                                <input type="email" class="form-control" id="semail" placeholder="student@email.com">
                                <div class="form-hint">A welcome email will be sent to this address.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-addon">+91</span>
                                    <input type="tel" class="form-control" placeholder="98765 43210">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Gender</label>
                                <select class="form-control">
                                    <option value="">Select…</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Non-binary</option>
                                    <option>Prefer not to say</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">City / State</label>
                                <input type="text" class="form-control" placeholder="e.g. Mumbai, MH">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-play-btn-fill" style="color:var(--accent)"></i>Course Enrollment
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Enroll In Course <span class="req">*</span></label>
                                <select class="form-control" id="scourse">
                                    <option value="">Select a course…</option>
                                    <option>React Masterclass</option>
                                    <option>Data Science Pro</option>
                                    <option>Python Beginners</option>
                                    <option>UX Design Basics</option>
                                    <option>AWS Cloud Essentials</option>
                                    <option>Digital Marketing</option>
                                    <option>Business Strategy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Batch / Cohort</label>
                                <select class="form-control">
                                    <option>Self-Paced (No Batch)</option>
                                    <option>Batch A — Mar 2026</option>
                                    <option>Batch B — Apr 2026</option>
                                    <option>Batch C — May 2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Enrollment Date</label>
                                <input type="date" class="form-control" value="2026-03-23">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Access Expires</label>
                                <input type="date" class="form-control" value="2026-12-31">
                                <div class="form-hint">Leave blank for lifetime access.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-credit-card-fill" style="color:var(--accent)"></i>Payment Details
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Payment Status</label>
                                <select class="form-control">
                                    <option>Paid</option>
                                    <option>Pending</option>
                                    <option>Scholarship</option>
                                    <option>Free Enrollment</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Amount Paid (₹)</label>
                                <div class="input-group">
                                    <span class="input-addon">₹</span>
                                    <input type="number" class="form-control" value="3200" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Payment Method</label>
                                <select class="form-control">
                                    <option>UPI</option>
                                    <option>Debit/Credit Card</option>
                                    <option>Net Banking</option>
                                    <option>Cash</option>
                                    <option>Scholarship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Payment Notes</label>
                                <input type="text" class="form-control" placeholder="Transaction ID, remarks...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-section-title">
                        <i class="bi bi-journal-text" style="color:var(--accent)"></i>Admin Notes
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Internal Notes</label>
                        <textarea class="form-control" placeholder="Any internal notes about this student (not visible to student)..." style="min-height:90px;"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-image-fill" style="color:var(--accent)"></i>Profile Photo
                    </div>
                    <div class="avatar-upload">
                        <img src="https://i.pravatar.cc/120?u=new" alt="Student" id="avatarPreview">
                        <div>
                            <div class="upload-area" onclick="showToast('File picker opened','info')">
                                <i class="bi bi-cloud-upload" style="font-size:1.4rem;color:var(--muted);display:block;margin-bottom:4px;"></i>
                                <span style="font-size:.75rem;color:var(--muted);">Click to upload</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock-fill" style="color:var(--accent)"></i>Account Setup
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="Auto-generated from email">
                        <div class="form-hint">Leave blank to auto-generate.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Temporary Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="tempPass" value="Edu@2026#">
                            <button type="button" class="btn btn-ghost" onclick="genPass()">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </div>
                        <div class="form-hint">Student will be asked to change this on first login.</div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-check">
                            <input type="checkbox" checked>
                            <span class="form-check-label">Send welcome email with credentials</span>
                        </label>
                        <label class="form-check mt-1">
                            <input type="checkbox">
                            <span class="form-check-label">Force password change on first login</span>
                        </label>
                    </div>
                </div>

                <div class="form-card mb-3">
                    <div class="form-section-title">
                        <i class="bi bi-tag-fill" style="color:var(--accent)"></i>Student Tags
                    </div>
                    <div class="d-flex flex-column gap-2">
                        @foreach($studentTags as $tag)
                        <label class="form-check">
                            <input type="checkbox" {{ $tag === 'Referral' ? 'checked' : '' }}>
                            <span class="form-check-label">{{ $tag }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-primary btn-sm w-100" onclick="submitStudent()">
                        <i class="bi bi-person-plus-fill me-1"></i>Add Student
                    </button>
                    <button class="btn btn-ghost btn-sm w-100" onclick="submitStudent(true)">
                        <i class="bi bi-person-plus-fill me-1"></i>Add & Add Another
                    </button>
                    <a href="{{ route('students') }}" class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="bulkImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-spreadsheet me-2" style="color:var(--accent)"></i>Bulk Import Students
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="upload-box mb-3" onclick="showToast('File picker opened','info')">
                    <i class="bi bi-file-earmark-spreadsheet" style="font-size:2rem;color:var(--accent)"></i>
                    <div class="ub-title">Upload CSV / Excel File</div>
                    <div class="ub-sub">Download template below, fill in student data, then upload.</div>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-ghost btn-sm" onclick="showToast('CSV template downloaded','success')">
                        <i class="bi bi-download me-1"></i>Download CSV Template
                    </button>
                    <button class="btn btn-ghost btn-sm" onclick="showToast('Excel template downloaded','success')">
                        <i class="bi bi-file-earmark-excel me-1"></i>Download Excel Template
                    </button>
                </div>
                <div style="background:var(--surface2);border-radius:var(--card-r);padding:14px;font-size:.8rem;">
                    <div class="fw-600 mb-2">Required columns:</div>
                    <div class="text-muted">first_name, last_name, email, phone, course, batch, payment_status, amount_paid</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary btn-sm" onclick="showToast('12 students imported successfully','success'); bootstrap.Modal.getInstance(document.getElementById('bulkImportModal')).hide()">
                    <i class="bi bi-upload me-1"></i>Import Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitStudent(addAnother = false) {
        let f = document.getElementById('fname').value.trim();
        let e = document.getElementById('semail').value.trim();
        let c = document.getElementById('scourse').value;

        if (!f) {
            showToast('Please enter first name', 'error');
            return;
        }

        if (!e) {
            showToast('Please enter email address', 'error');
            return;
        }

        if (!c) {
            showToast('Please select a course', 'error');
            return;
        }

        showToast('Student added successfully!', 'success');

        if (addAnother) {
            setTimeout(function() {
                document.getElementById('fname').value = '';
                document.getElementById('semail').value = '';
                document.getElementById('scourse').value = '';
                document.getElementById('fname').focus();
            }, 600);
        } else {
            setTimeout(function() {
                window.location.href = "{{ route('students') }}";
            }, 1500);
        }
    }

    function genPass() {
        let chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789@#';
        let p = '';
        for (let i = 0; i < 10; i++) {
            p += chars[Math.floor(Math.random() * chars.length)];
        }
        document.getElementById('tempPass').value = p;
    }
</script>
@endpush