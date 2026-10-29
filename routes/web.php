<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\BlockedDeletedStudentController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BulkRegIdController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\Admin\ContentBundleController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NotificationHeadController;
use App\Http\Controllers\Admin\PopupNotificationController;
use App\Http\Controllers\Admin\ProgrammeCenterController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\ViewStudentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Admin\QrAttendanceController;
use App\Http\Controllers\Admin\StudentLoginStatusController;
use App\Http\Controllers\Admin\SubjectMockTestController;
use App\Http\Controllers\Admin\UploadContentController;
use App\Http\Controllers\Student\StudentFeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect('/login');
});

Route::middleware('guest')->group(function () {
  Route::get('/login', [LoginController::class, 'show'])->name('login');
  Route::get('/register', [RegisterController::class, 'show'])->name('register');
});

Route::middleware('auth')->group(function () {
  Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
  Route::get('/add_course', [AdminDashboardController::class, 'courses'])->name('admin.add_course');
  Route::get('/add_student', [AdminDashboardController::class, 'students'])->name('admin.add_student');
  Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses');
  Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('admin.announcements');

  Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles');
  Route::get('/sessions', [SessionController::class, 'index'])->name('admin.sessions');
  Route::get('/programmes', [ProgrammeController::class, 'index'])->name('admin.programmes');
  Route::get('/technologies', [TechnologyController::class, 'index'])->name('admin.technologies');
  Route::get('/batches', [BatchController::class, 'index'])->name('admin.batches');
  Route::get('/centers', [CenterController::class, 'index'])->name('admin.centers');
  Route::get('/programme-centers/programmes-by-session', [ProgrammeCenterController::class, 'getProgrammesBySession'])->name('programme-centers.programmes-by-session');
  Route::get('/branches', [BranchController::class, 'index'])->name('admin.branches');
  Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects');

  Route::get('/students', [StudentController::class, 'students'])->name('admin.students');
  Route::get('/students/{id}/assign', [StudentController::class, 'assignPage'])->name('students.assign.page');
  Route::get('/students/data', [StudentController::class, 'data'])->name('admin.students.data');
  Route::get('/students/bulk-template', [StudentController::class, 'downloadBulkAssignTemplate'])->name('admin.students.bulk-template');
  Route::get('/students/bulk-upload-template', [StudentController::class, 'downloadBulkUploadTemplate'])->name('admin.students.bulk-upload-template');
  Route::get('/students/bulk-regid-template', [BulkRegIdController::class, 'downloadTemplate'])->name('admin.students.bulk-regid-template');

  Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees');
  Route::get('/employees/data', [EmployeeController::class, 'data'])->name('admin.employees.data');
  Route::get('/employees/assign/{id}', [EmployeeController::class, 'assignPage'])->name('employees.assign.page');
  Route::get('/employees/bulk-upload-template', [EmployeeController::class, 'downloadBulkUploadTemplate'])->name('admin.employees.bulk-upload-template');
  Route::get('/employees/bulk-template', [EmployeeController::class, 'downloadBulkAssignTemplate'])->name('admin.employees.bulk-template');
  Route::get('/employees/bulk-regid-template', [EmployeeController::class, 'downloadRegIdTemplate'])->name('admin.employees.bulk-regid-template');
  Route::get('/employees/{id}/assigned-batches', [EmployeeController::class, 'assignedBatches'])->name('admin.employees.assigned-batches');

  Route::get('/view-students', [ViewStudentController::class, 'index'])->name('admin.viewstudents');
  Route::get('/blocked-deleted-students', [BlockedDeletedStudentController::class, 'index'])->name('admin.blocked-deleted-students');

  // Attendance page
  Route::get('/add-attandence', [AttendanceController::class, 'index'])->name('admin.addattandence');
  Route::get('/view-attandence', [AttendanceController::class, 'viewAttendance'])->name('admin.viewattandence');
  Route::get('/view-attandence/data', [AttendanceController::class, 'viewAttendanceData'])->name('admin.viewattandence.data');

  Route::get('/attendance/bulk-template', [AttendanceController::class, 'downloadBulkTemplate'])->name('attendance.bulk.template');

  Route::get('/qr-attendance', [QrAttendanceController::class, 'showForm'])->name('qr.attendance.form');

  Route::get('/upload-content', [UploadContentController::class, 'index'])->name('upload-content.index');
  Route::get('/upload-content/topics/{subjectId}', [UploadContentController::class, 'getTopicsBySubject'])->name('upload-content.topics');
  Route::get('/upload-content/online-test-template/download', [UploadContentController::class, 'downloadOnlineTestCsvTemplate'])
    ->name('upload-content.online-test-template.download');

  Route::get('/subjects/topics', [SubjectController::class, 'topicsPage'])->name('subjects.topics');

  Route::get('/content-bundles', [ContentBundleController::class, 'index'])->name('admin.content-bundles.index');
  Route::get('/content-bundles/{subjectId}/{topicId}', [ContentBundleController::class, 'show'])->name('admin.content-bundles.show');
  Route::get('/content-bundles/topics/{subjectId}', [ContentBundleController::class, 'getTopicsBySubject'])->name('admin.content-bundles.topics');
  Route::get('/content-bundles/{subjectId}/{topicId}/edit', [ContentBundleController::class, 'edit'])->name('admin.content-bundles.edit');

  Route::get('/subject-mocktest', [SubjectMockTestController::class, 'index'])->name('admin.subject-mocktest.index');
  Route::get('/subject-mocktest/create', [SubjectMockTestController::class, 'create'])->name('admin.subject-mocktest.create');
  Route::get('/subject-mocktest/show/{id}', [SubjectMockTestController::class, 'show'])->name('admin.subject-mocktest.show');
  Route::get('/subject-mocktest/edit/{id}', [SubjectMockTestController::class, 'edit'])->name('admin.subject-mocktest.edit');

  Route::get('/notification-heads', [NotificationHeadController::class, 'index'])->name('admin.notification-heads.index');
  Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
  Route::get('/popup-notifications', [PopupNotificationController::class, 'index'])->name('admin.popup-notifications.index');





  Route::get('/instructors', [AdminDashboardController::class, 'instructors'])->name('admin.instructors');
  Route::get('/schedule', [AdminDashboardController::class, 'schedule'])->name('admin.schedule');
  Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
  Route::get('/revenue', [AdminDashboardController::class, 'revenue'])->name('admin.revenue');
  Route::get('/assessments', [AdminDashboardController::class, 'assessments'])->name('admin.assessments');
  Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
  Route::get('/approvals', [AdminDashboardController::class, 'approvals'])->name('admin.approvals');

  Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
  Route::get('/admin/change-password', [AdminProfileController::class, 'changePasswordPage'])->name('admin.change.password');
});

Route::prefix('student')->middleware('auth')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
  Route::get('/courses', [DashboardController::class, 'courses'])->name('student.courses');
  Route::get('/assignments', [DashboardController::class, 'assignments'])->name('student.assignments');
  Route::get('/quiz', [DashboardController::class, 'quiz'])->name('student.quiz');
  Route::get('/schedule', [DashboardController::class, 'schedule'])->name('student.schedule');
  Route::get('/results', [DashboardController::class, 'results'])->name('student.results');
  Route::get('/certificates', [DashboardController::class, 'certificates'])->name('student.certificates');
  Route::get('/announcements', [DashboardController::class, 'announcements'])->name('student.announcements');
  Route::get('/profile', [DashboardController::class, 'profile'])->name('student.profile');
  Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('student.change_password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/feedback/create', [AdminFeedbackController::class, 'create'])->name('feedback.create');
  Route::post('/feedback/store', [AdminFeedbackController::class, 'store'])->name('feedback.store');
  Route::get('/feedback/manage', [AdminFeedbackController::class, 'manage'])->name('feedback.manage');
  Route::get('/feedback/edit/{id}', [AdminFeedbackController::class, 'edit'])->name('feedback.edit');
  Route::post('/feedback/update/{id}', [AdminFeedbackController::class, 'update'])->name('feedback.update');
  Route::post('/feedback/copy/{id}', [AdminFeedbackController::class, 'copy'])->name('feedback.copy');
  Route::post('/feedback/delete/{id}', [AdminFeedbackController::class, 'delete'])->name('feedback.delete');
  Route::get('/feedback/form-details/{id}', [AdminFeedbackController::class, 'formDetails'])
    ->name('feedback.form.details');
  Route::get('/feedback/overall-analytics', [AdminFeedbackController::class, 'overallAnalytics'])->name('feedback.overall.analytics');

  Route::post('/feedback/cascade/centers', [AdminFeedbackController::class, 'cascadeCenters'])->name('feedback.cascade.centers');
  Route::post('/feedback/cascade/programmes', [AdminFeedbackController::class, 'cascadeProgrammes'])->name('feedback.cascade.programmes');
  Route::post('/feedback/cascade/technologies', [AdminFeedbackController::class, 'cascadeTechnologies'])->name('feedback.cascade.technologies');
  Route::post('/feedback/cascade/batches', [AdminFeedbackController::class, 'cascadeBatches'])->name('feedback.cascade.batches');

  Route::post('/feedback/analytics/filtered-students', [AdminFeedbackController::class, 'filteredStudents'])
    ->name('feedback.analytics.filtered.students');
  Route::post('/feedback/analytics/student-response', [AdminFeedbackController::class, 'studentResponse'])
    ->name('feedback.analytics.student.response');
  Route::post('/feedback/analytics/overall-data', [AdminFeedbackController::class, 'overallData'])
    ->name('feedback.analytics.overall.data');
});

Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
  Route::get('/feedback', [StudentFeedbackController::class, 'index'])->name('feedback.index');
  Route::get('/feedback/{formId}', [StudentFeedbackController::class, 'show'])->name('feedback.show');
  Route::post('/feedback/{formId}', [StudentFeedbackController::class, 'store'])->name('feedback.store');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/student-login-status', [StudentLoginStatusController::class, 'index'])
    ->name('student.login.status');

  Route::get('/student-login-status/logs/{stuId}', [StudentLoginStatusController::class, 'logs'])
    ->name('student.login.logs');
});
