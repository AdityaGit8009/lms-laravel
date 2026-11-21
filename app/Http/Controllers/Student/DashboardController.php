<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DashboardController extends Controller
{
  private function sidebarData(): array
  {
    return [
      'sidebarEnrolled' => 3,
      'sidebarPending' => 4,
    ];
  }

  public function index(Request $request): View
  {
    $student = $request->user();

    $stats = [
      'enrolled' => 3,
      'completed' => 1,
      'assignments' => 4,
      'certificates' => 1,
    ];

    $streak = 6;

    $enrolledCourses = [
      ['name' => 'Java Full Stack Development','instructor' => 'Amit Sir','lessons' => 42,'progress' => 65,'emoji' => '☕','bg' => 'rgba(110, 231, 183, .12)','color' => '#6ee7b7'],
      ['name' => 'Frontend Development Bootcamp','instructor' => 'Neha Ma’am','lessons' => 30,'progress' => 40,'emoji' => '🎨','bg' => 'rgba(129, 140, 248, .12)','color' => '#818cf8'],
      ['name' => 'MySQL Database Essentials','instructor' => 'Ravi Sir','lessons' => 18,'progress' => 85,'emoji' => '🛢️','bg' => 'rgba(251, 191, 36, .12)','color' => '#fbbf24'],
    ];

    $upcomingDeadlines = [
      ['day' => '05','month' => 'APR','task' => 'Java Assignment Submission','course' => 'Java Full Stack Development','urgency' => 'pink'],
      ['day' => '08','month' => 'APR','task' => 'Frontend Mini Project','course' => 'Frontend Development Bootcamp','urgency' => 'amber'],
      ['day' => '12','month' => 'APR','task' => 'SQL Practice Test','course' => 'MySQL Database Essentials','urgency' => 'purple'],
    ];

    $recentActivities = [
      ['title' => 'Aapne Java module 5 complete kiya.','time' => 'Aaj, 10:30 AM','color' => '#6ee7b7'],
      ['title' => 'Frontend assignment upload kiya.','time' => 'Kal, 06:15 PM','color' => '#818cf8'],
      ['title' => 'SQL quiz me 82% score kiya.','time' => '2 din pehle','color' => '#f472b6'],
    ];

    return view('Student.dashboard', array_merge([
      'student' => $student,
      'stats' => $stats,
      'streak' => $streak,
      'enrolledCourses' => $enrolledCourses,
      'upcomingDeadlines' => $upcomingDeadlines,
      'recentActivities' => $recentActivities,
    ], $this->sidebarData()));
  }

  public function courses(Request $request): View
  {
    $student = $request->user();
    $courses = [
      ['name' => 'Java Full Stack Development','instructor' => 'Amit Sir','duration' => '12 Weeks','progress' => 65,'status' => 'Ongoing'],
      ['name' => 'Frontend Development Bootcamp','instructor' => 'Neha Ma’am','duration' => '8 Weeks','progress' => 40,'status' => 'Ongoing'],
      ['name' => 'MySQL Database Essentials','instructor' => 'Ravi Sir','duration' => '6 Weeks','progress' => 85,'status' => 'Almost Complete'],
    ];
    return view('Student.courses', array_merge(['student' => $student,'courses' => $courses], $this->sidebarData()));
  }

  public function assignments(Request $request): View
  {
    $student = $request->user();
    $assignments = [
      ['title' => 'Java OOP Assignment', 'course' => 'Java Full Stack Development', 'due_date' => '05 Apr 2026', 'status' => 'Pending'],
      ['title' => 'Frontend Landing Page', 'course' => 'Frontend Development Bootcamp', 'due_date' => '08 Apr 2026', 'status' => 'Pending'],
      ['title' => 'SQL Practice Sheet', 'course' => 'MySQL Database Essentials', 'due_date' => '12 Apr 2026', 'status' => 'Submitted'],
    ];
    return view('Student.assignments', array_merge(['student' => $student,'assignments' => $assignments], $this->sidebarData()));
  }

  public function quiz(Request $request): View
  {
    $student = $request->user();
    $quizzes = [
      ['title' => 'Java Basics Quiz', 'course' => 'Java Full Stack Development', 'marks' => '18/20', 'status' => 'Completed'],
      ['title' => 'HTML/CSS Quiz', 'course' => 'Frontend Development Bootcamp', 'marks' => 'Pending', 'status' => 'Upcoming'],
      ['title' => 'SQL Commands Quiz', 'course' => 'MySQL Database Essentials', 'marks' => '16/20', 'status' => 'Completed'],
    ];
    return view('Student.quiz', array_merge(['student' => $student,'quizzes' => $quizzes], $this->sidebarData()));
  }

  public function schedule(Request $request): View { $student = $request->user(); return view('Student.schedule', array_merge(['student' => $student], $this->sidebarData())); }
  public function results(Request $request): View { $student = $request->user(); return view('Student.results', array_merge(['student' => $student], $this->sidebarData())); }
  public function certificates(Request $request): View { $student = $request->user(); return view('Student.certificates', array_merge(['student' => $student], $this->sidebarData())); }
  public function announcements(Request $request): View { $student = $request->user(); return view('Student.announcements', array_merge(['student' => $student], $this->sidebarData())); }
  public function profile(Request $request): View { $student = $request->user(); return view('Student.profile', array_merge(['student' => $student], $this->sidebarData())); }
  public function changePassword(Request $request): View { $student = $request->user(); return view('Student.change_password', array_merge(['student' => $student], $this->sidebarData())); }

  public function updatePassword(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'current_password' => ['required', 'current_password:web'],
      'password' => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
    ]);

    $student = $request->user();
    $student->password = Hash::make($validated['password']);
    $student->save();
    $request->session()->regenerate();

    return redirect()->route('student.change_password')->with('success', 'Password changed successfully.');
  }
}
