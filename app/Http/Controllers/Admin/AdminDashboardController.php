<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\RegisterStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
  public function index(): View
  {
    $user = Auth::user();

    $totalStudents = RegisterStudent::count();
    $approvedStudents = RegisterStudent::where('approval_status', 'Approved')->count();
    $pendingStudents = RegisterStudent::where('approval_status', 'Pending')->count();
    $rejectedStudents = RegisterStudent::where('approval_status', 'Rejected')->count();

    $totalEmployees = Employee::count();
    $activeEmployees = Employee::whereIn('status', ['Active', '1', 1])->count();

    $stats = [
      'total_students'    => $totalStudents,
      'approved_students' => $approvedStudents,
      'pending_students'  => $pendingStudents,
      'rejected_students' => $rejectedStudents,
      'total_employees'   => $totalEmployees,
      'active_employees'  => $activeEmployees,
    ];

    $recentStudents = RegisterStudent::latest('id')->take(5)->get();

    return view('Admin.index', [
      'user' => $user,
      'adminName' => $user->name ?? 'Admin',
      'stats' => $stats,
      'recentStudents' => $recentStudents,
    ]);
  }

  public function courses(): View { return view('Admin.courses'); }
  public function students(): View { return view('Admin.students'); }
  public function instructors(): View { return view('Admin.instructors'); }
  public function schedule(): View { return view('Admin.schedule'); }
  public function reports(): View { return view('Admin.reports'); }
  public function revenue(): View { return view('Admin.revenue'); }
  public function assessments(): View { return view('Admin.assessments'); }
  public function announcements(): View { return view('Admin.announcements'); }
  public function addCourse(): View { return view('Admin.add_course'); }
  public function addStudent(): View { return view('Admin.add_student'); }
  public function settings(): View { return view('Admin.settings'); }
  public function masterEntry(): View { return view('Admin.masterentry'); }

  public function approvals(): View
  {
    $students = RegisterStudent::latest('id')->get();
    return view('Admin.student_approvals', compact('students'));
  }

  public function updateApprovalStatus(Request $request, $id): RedirectResponse
  {
    $request->validate([
      'approval_status' => 'required|in:Approved,Pending,Rejected',
    ]);

    $student = RegisterStudent::findOrFail($id);
    $student->approval_status = $request->approval_status;
    $student->save();

    User::where('email', $student->email)->update([
      'approval_status' => $request->approval_status,
      'name'            => $student->name,
      'phone'           => $student->mobile,
      'college'         => $student->college,
      'regno'           => $student->regno,
      'course'          => $student->course,
      'branch'          => $student->branch,
      'year'            => $student->year,
      'gender'          => ucfirst((string) $student->gender),
      'program'         => $student->program,
      'location'        => $student->location,
      'image'           => $student->photo,
    ]);

    return redirect()
      ->route('admin.approvals')
      ->with('success', 'Student status updated successfully.');
  }
}
