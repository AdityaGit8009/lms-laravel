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
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::prefix('auth')->middleware('web')->group(function () {
  Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
  Route::post('/login', [LoginController::class, 'login'])->name('api.login');
  Route::post('/logout', [LoginController::class, 'logout'])->name('api.logout');
  Route::post('/approvals/{id}/update-status', [AdminDashboardController::class, 'updateApprovalStatus'])->name('admin.approvals.updateStatus');
  Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])->name('api.admin.profile.update');
  Route::post('/admin/change-password', [AdminProfileController::class, 'changePassword'])->name('api.admin.change.password');
});

Route::prefix('admin')->group(function () {
  Route::post('/roles', [RoleController::class, 'store'])->name('api.admin.roles.store');
  Route::put('/roles/{id}', [RoleController::class, 'update'])->name('api.admin.roles.update');
  Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('api.admin.roles.destroy');

  Route::post('/sessions', [SessionController::class, 'store'])->name('api.admin.sessions.store');
  Route::put('/sessions/{id}', [SessionController::class, 'update'])->name('api.admin.sessions.update');
  Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('api.admin.sessions.destroy');

  // Programme Routes
  Route::post('/programmes', [ProgrammeController::class, 'store'])->name('api.admin.programmes.store');
  Route::put('/programmes/{id}', [ProgrammeController::class, 'update'])->name('api.admin.programmes.update');
  Route::delete('/programmes/{id}', [ProgrammeController::class, 'destroy'])->name('api.admin.programmes.destroy');

  // Technology Routes
  Route::post('/technologies', [TechnologyController::class, 'store'])->name('api.admin.technologies.store');
  Route::put('/technologies/{id}', [TechnologyController::class, 'update'])->name('api.admin.technologies.update');
  Route::delete('/technologies/{id}', [TechnologyController::class, 'destroy'])->name('api.admin.technologies.destroy');

  // Batch Routes
  Route::post('/batches', [BatchController::class, 'store'])->name('api.admin.batches.store');
  Route::put('/batches/{id}', [BatchController::class, 'update'])->name('api.admin.batches.update');
  Route::delete('/batches/{id}', [BatchController::class, 'destroy'])->name('api.admin.batches.destroy');

  // Subject Routes
  Route::post('/subjects', [SubjectController::class, 'store'])->name('api.admin.subjects.store');
  Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('api.admin.subjects.update');
  Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('api.admin.subjects.destroy');

  // Center Routes
  Route::post('/centers', [CenterController::class, 'store'])->name('api.admin.centers.store');
  Route::put('/centers/{id}', [CenterController::class, 'update'])->name('api.admin.centers.update');
  Route::delete('/centers/{id}', [CenterController::class, 'destroy'])->name('api.admin.centers.destroy');

  // Course Routes
  Route::post('/courses', [CourseController::class, 'store'])->name('api.admin.courses.store');
  Route::put('/courses/{id}', [CourseController::class, 'update'])->name('api.admin.courses.update');
  Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('api.admin.courses.destroy');

  //Branch Routes
  Route::post('/branches', [BranchController::class, 'store'])->name('api.admin.branches.store');
  Route::put('/branches/{id}', [BranchController::class, 'update'])->name('api.admin.branches.update');
  Route::delete('/branches/{id}', [BranchController::class, 'destroy'])->name('api.admin.branches.destroy');


  // Student Routes
  Route::post('/students', [StudentController::class, 'store'])->name('api.admin.students.store');
  Route::put('/students/{id}', [StudentController::class, 'update'])->name('api.admin.students.update');
  Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('api.admin.students.destroy');

  Route::post('/students/branches/{courseId}', [StudentController::class, 'getBranchesByCourse'])->name('students.branches');
  Route::post('/students/years/{courseId}', [StudentController::class, 'getYearsByCourse'])->name('students.years');
  Route::post('/students/{id}/block-toggle', [StudentController::class, 'toggleBlock'])->name('students.block-toggle');

  // Assign Routes
  Route::post('/students/{id}/assign', [StudentController::class, 'saveAssignment'])->name('students.assign.save');
  Route::post('/students/assign/filter-data', [StudentController::class, 'getAssignFilterData'])->name('admin.students.assign.filter-data');
  Route::post('/students/bulk-assign', [StudentController::class, 'bulkAssign'])->name('admin.students.bulk-assign');


  Route::post('/students/bulk-upload', [StudentController::class, 'bulkUpload'])
    ->name('admin.students.bulk-upload');
});
