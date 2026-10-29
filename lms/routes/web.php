<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TechnologyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Student\DashboardController;

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
  Route::get('/courses', [AdminDashboardController::class, 'courses'])->name('admin.courses');
  Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('admin.announcements');

  Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles');
  Route::get('/sessions', [SessionController::class, 'index'])->name('admin.sessions');
  Route::get('/programmes', [ProgrammeController::class, 'index'])->name('admin.programmes');
  Route::get('/technologies', [TechnologyController::class, 'index'])->name('admin.technologies');
  Route::get('/batches', [BatchController::class, 'index'])->name('admin.batches');
  Route::get('/centers', [CenterController::class, 'index'])->name('admin.centers');
  Route::get('/courses', [CourseController::class, 'index'])->name('admin.courses');
  Route::get('/branches', [BranchController::class, 'index'])->name('admin.branches');
  Route::get('/subjects', [SubjectController::class, 'index'])->name('admin.subjects');

  // Student Routes

  Route::get('/students', [StudentController::class, 'students'])->name('admin.students');
  Route::get('/students/{id}/assign', [StudentController::class, 'assignPage'])->name('students.assign.page');

  Route::get('/students/data', [StudentController::class, 'data'])->name('admin.students.data');
  Route::get('/students/bulk-template', [StudentController::class, 'downloadBulkAssignTemplate'])->name('admin.students.bulk-template');


  Route::get('/students/bulk-upload-template', [StudentController::class, 'downloadBulkUploadTemplate'])
    ->name('admin.students.bulk-upload-template');

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




Route::prefix('student')->group(function () {
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


// Route::middleware('auth')->group(function () {
//   Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
//   Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
//   Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
// });
