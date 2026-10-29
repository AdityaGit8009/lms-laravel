<?php

use App\Http\Controllers\Admin\AdminDashboardController;
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
use App\Http\Controllers\Admin\SubjectMockTestController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\UploadContentController;
use App\Http\Controllers\Admin\ViewStudentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('web')->group(function () {
  Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
  Route::post('/login', [LoginController::class, 'login'])->name('api.login');
  Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');

  Route::post('/approvals/{id}/update-status', [AdminDashboardController::class, 'updateApprovalStatus'])
    ->name('admin.approvals.updateStatus');

  Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])
    ->name('api.admin.profile.update');

  Route::post('/admin/change-password', [AdminProfileController::class, 'changePassword'])
    ->name('api.admin.change.password');
});

Route::prefix('admin')->group(function () {
  // Roles
  Route::post('/roles', [RoleController::class, 'store'])->name('api.admin.roles.store');
  Route::put('/roles/{id}', [RoleController::class, 'update'])->name('api.admin.roles.update');
  Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('api.admin.roles.destroy');

  // Sessions
  Route::post('/sessions', [SessionController::class, 'store'])->name('api.admin.sessions.store');
  Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('api.admin.sessions.update');
  Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('api.admin.sessions.destroy');

  // Programmes
  Route::post('/programmes', [ProgrammeController::class, 'store'])->name('api.admin.programmes.store');
  Route::put('/programmes/{id}', [ProgrammeController::class, 'update'])->name('api.admin.programmes.update');
  Route::delete('/programmes/{id}', [ProgrammeController::class, 'destroy'])->name('api.admin.programmes.destroy');
  Route::post('/programmes/restore/{id}', [ProgrammeController::class, 'restore'])->name('api.admin.programmes.restore');

  // Programme Centers
  Route::post('/programme-centers', [ProgrammeCenterController::class, 'store'])->name('programme-centers.store');
  Route::post('/programme-centers/update-group', [ProgrammeCenterController::class, 'updateGroup'])->name('programme-centers.update-group');
  Route::post('/programme-centers/destroy-group', [ProgrammeCenterController::class, 'destroyGroup'])->name('programme-centers.destroy-group');

  // Technologies
  Route::post('/technologies', [TechnologyController::class, 'store'])->name('api.admin.technologies.store');
  Route::put('/technologies/{id}', [TechnologyController::class, 'update'])->name('api.admin.technologies.update');
  Route::delete('/technologies/{id}', [TechnologyController::class, 'destroy'])->name('api.admin.technologies.destroy');

  // Batches
  Route::post('/batches', [BatchController::class, 'store'])->name('api.admin.batches.store');
  Route::put('/batches/{id}', [BatchController::class, 'update'])->name('api.admin.batches.update');
  Route::delete('/batches/{id}', [BatchController::class, 'destroy'])->name('api.admin.batches.destroy');

  // Subjects
  Route::post('/subjects', [SubjectController::class, 'store'])->name('api.admin.subjects.store');
  Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('api.admin.subjects.update');
  Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('api.admin.subjects.destroy');

  // Centers
  Route::post('/centers', [CenterController::class, 'store'])->name('api.admin.centers.store');
  Route::put('/centers/{id}', [CenterController::class, 'update'])->name('api.admin.centers.update');
  Route::delete('/centers/{id}', [CenterController::class, 'destroy'])->name('api.admin.centers.destroy');

  // Courses
  Route::post('/courses', [CourseController::class, 'store'])->name('api.admin.courses.store');
  Route::put('/courses/{id}', [CourseController::class, 'update'])->name('api.admin.courses.update');
  Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('api.admin.courses.destroy');

  // Branches
  Route::post('/branches', [BranchController::class, 'store'])->name('api.admin.branches.store');
  Route::put('/branches/{id}', [BranchController::class, 'update'])->name('api.admin.branches.update');
  Route::delete('/branches/{id}', [BranchController::class, 'destroy'])->name('api.admin.branches.destroy');

  // Students
  Route::post('/students', [StudentController::class, 'store'])->name('api.admin.students.store');
  Route::put('/students/{id}', [StudentController::class, 'update'])->name('api.admin.students.update');
  Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('api.admin.students.destroy');

  Route::post('/students/branches/{courseId}', [StudentController::class, 'getBranchesByCourse'])->name('students.branches');
  Route::post('/students/years/{courseId}', [StudentController::class, 'getYearsByCourse'])->name('students.years');
  Route::post('/students/{id}/block-toggle', [StudentController::class, 'toggleBlock'])->name('students.block-toggle');

  Route::post('/students/{id}/assign', [StudentController::class, 'saveAssignment'])->name('students.assign.save');
  Route::post('/students/assign/filter-data', [StudentController::class, 'getAssignFilterData'])->name('admin.students.assign.filter-data');
  Route::post('/students/bulk-assign', [StudentController::class, 'bulkAssign'])->name('admin.students.bulk-assign');
  Route::post('/students/bulk-upload', [StudentController::class, 'bulkUpload'])->name('admin.students.bulk-upload');
  Route::post('/students/bulk-update-regid', [BulkRegIdController::class, 'bulkUpdateRegId'])->name('admin.students.bulk-update-regid');

  // Employees
  Route::post('/employees/store', [EmployeeController::class, 'store'])->name('api.admin.employees.store');
  Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('api.admin.employees.update');
  Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('api.admin.employees.destroy');

  Route::post('/employees/block-toggle/{id}', [EmployeeController::class, 'toggleBlock'])->name('employees.block-toggle');
  Route::post('/employees/assign/save/{id}', [EmployeeController::class, 'saveAssignment'])->name('employees.assign.save');
  Route::post('/employees/assign/filter-data', [EmployeeController::class, 'getAssignFilterData'])->name('admin.employees.assign.filter-data');
  Route::post('/employees/bulk-upload', [EmployeeController::class, 'bulkUpload'])->name('admin.employees.bulk-upload');
  Route::post('/employees/bulk-assign', [EmployeeController::class, 'bulkAssign'])->name('admin.employees.bulk-assign');
  Route::post('/employees/bulk-regid-update', [EmployeeController::class, 'bulkUpdateRegId'])->name('admin.employees.bulk-regid-update');

  // View Students
  Route::post('/view-students/filter-data', [ViewStudentController::class, 'getFilterData'])->name('admin.viewstudents.filter-data');
  Route::post('/view-students/data', [ViewStudentController::class, 'data'])->name('admin.viewstudents.data');

  Route::post('/blocked-deleted-students/data', [BlockedDeletedStudentController::class, 'data'])->name('admin.blocked-deleted-students.data');
  Route::post('/blocked-deleted-students/unblock/{id}', [BlockedDeletedStudentController::class, 'unblock'])->name('admin.blocked-deleted-students.unblock');
  Route::post('/blocked-deleted-students/restore/{id}', [BlockedDeletedStudentController::class, 'restore'])->name('admin.blocked-deleted-students.restore');



  Route::post('/upload-content/store', [UploadContentController::class, 'store'])->name('admin.upload-content.store');
  Route::post('/upload-content/topic/store', [UploadContentController::class, 'storeTopic'])->name('admin.upload-content.topic.store');

  Route::get('/subjects/{id}/topics-list', [SubjectController::class, 'getTopicsBySubject'])->name('admin.subjects.topics-list');


  Route::put('/upload-content/{id}/topic-title', [SubjectController::class, 'updateTopicTitle'])
    ->name('admin.upload-content.topic-title.update');

  Route::delete('/upload-content/{id}', [SubjectController::class, 'deleteTopicTitle'])
    ->name('admin.upload-content.delete');



  Route::put('/content-bundles/{subjectId}/{topicId}', [ContentBundleController::class, 'update'])->name('admin.content-bundles.update');

  Route::post('/subject-mocktest/store', [SubjectMockTestController::class, 'store'])
    ->name('admin.subject-mocktest.store');

  Route::put('/subject-mocktest/update/{id}', [SubjectMockTestController::class, 'update'])
    ->name('admin.subject-mocktest.update');

  Route::delete('/subject-mocktest/delete/{id}', [SubjectMockTestController::class, 'destroy'])
    ->name('admin.subject-mocktest.delete');



  Route::post('/notification-heads', [NotificationHeadController::class, 'store'])->name('admin.notification-heads.store');
  Route::put('/notification-heads/{notificationHead}', [NotificationHeadController::class, 'update'])->name('admin.notification-heads.update');
  Route::delete('/notification-heads/{notificationHead}', [NotificationHeadController::class, 'destroy'])->name('admin.notification-heads.destroy');
  Route::get('/notifications/list', [NotificationController::class, 'list'])->name('admin.notifications.list');

  Route::get('/notifications/centers/{sessionId}', [NotificationController::class, 'getCentersBySession'])->name('admin.notifications.centers');
  Route::get('/notifications/programmes', [NotificationController::class, 'getProgrammes'])->name('admin.notifications.programmes');
  Route::get('/notifications/technologies', [NotificationController::class, 'getTechnologies'])->name('admin.notifications.technologies');
  Route::get('/notifications/batches', [NotificationController::class, 'getBatches'])->name('admin.notifications.batches');

  Route::post('/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');
  Route::put('/notifications/{notification}', [NotificationController::class, 'update'])->name('admin.notifications.update');
  Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');



  Route::get('/popup-notifications/list', [PopupNotificationController::class, 'list'])
    ->name('admin.popup-notifications.list');

  Route::get('/popup-notifications/options/centers', [PopupNotificationController::class, 'getCenters'])
    ->name('admin.popup-notifications.centers');

  Route::get('/popup-notifications/options/programmes', [PopupNotificationController::class, 'getProgrammes'])
    ->name('admin.popup-notifications.programmes');

  Route::get('/popup-notifications/options/technologies', [PopupNotificationController::class, 'getTechnologies'])
    ->name('admin.popup-notifications.technologies');

  Route::get('/popup-notifications/options/batches', [PopupNotificationController::class, 'getBatches'])
    ->name('admin.popup-notifications.batches');

  Route::post('/popup-notifications', [PopupNotificationController::class, 'store'])
    ->name('admin.popup-notifications.store');

  Route::get('/popup-notifications/{popupNotification}', [PopupNotificationController::class, 'show'])
    ->name('admin.popup-notifications.show');

  Route::put('/popup-notifications/{popupNotification}', [PopupNotificationController::class, 'update'])
    ->name('admin.popup-notifications.update');

  Route::delete('/popup-notifications/{popupNotification}', [PopupNotificationController::class, 'destroy'])
    ->name('admin.popup-notifications.destroy');
});


// Attendance AJAX routes
Route::prefix('attendance')->name('attendance.')->group(function () {
  Route::post('/centers', [AttendanceController::class, 'getCenters'])->name('centers');
  Route::post('/programmes', [AttendanceController::class, 'getProgrammes'])->name('programmes');
  Route::post('/technologies', [AttendanceController::class, 'getTechnologies'])->name('technologies');
  Route::post('/batches', [AttendanceController::class, 'getBatches'])->name('batches');
  Route::post('/students', [AttendanceController::class, 'fetchStudents'])->name('students');
  Route::post('/store', [AttendanceController::class, 'store'])->name('store');

  Route::post('/bulk-store', [AttendanceController::class, 'bulkStore'])->name('bulk.store');

  Route::post('/view-attandence/toggle', [AttendanceController::class, 'toggleAttendanceCell'])->name('viewattandence.toggle');

  Route::post('/qr-attendance', [QrAttendanceController::class, 'submitForm'])->name('qr.attendance.submit');
});
