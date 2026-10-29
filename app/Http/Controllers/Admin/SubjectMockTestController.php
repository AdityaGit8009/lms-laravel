<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectMockTest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SubjectMockTestController extends Controller
{
  public function index(): View
  {
    $mockTests = SubjectMockTest::leftJoin('tbl_subject as s', 's.subject_id', '=', 'tbl_subject_mock_tests.subject_id')
      ->select('tbl_subject_mock_tests.*', 's.subject_name')
      ->orderByDesc('tbl_subject_mock_tests.subject_mock_test_id')
      ->get();

    return view('Admin.Content.list_subject_mock', compact('mockTests'));
  }

  public function create(): View
  {
    $subjects = Subject::orderBy('subject_name', 'asc')->get();

    return view('Admin.Content.subject_mocktest', compact('subjects'));
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'subject_id'  => ['required', 'exists:tbl_subject,subject_id'],
      'title'       => ['required', 'string', 'max:255'],
      'upload_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip', 'max:10240'],
      'max_marks'   => ['required', 'integer', 'min:0'],
      'mock_test'   => ['required', 'string'],
    ]);

    $plainText = trim(strip_tags((string) $request->mock_test));
    if ($plainText === '') {
      return back()
        ->withErrors(['mock_test' => 'Question / Content is required.'])
        ->withInput();
    }

    $fileName = null;

    if ($request->hasFile('upload_file')) {
      $file = $request->file('upload_file');

      $uploadPath = public_path('uploads/mock-tests');
      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $fileName);
    }

    $actor = Auth::check() ? (Auth::user()->name ?? 'Admin') : 'Admin';

    SubjectMockTest::create([
      'subject_id'  => $request->subject_id,
      'title'       => trim($request->title),
      'upload_file' => $fileName,
      'max_marks'   => $request->max_marks,
      'mock_test'   => $request->mock_test,
      'status'      => 1,
      'created_by'  => $actor,
      'updated_by'  => $actor,
    ]);

    return redirect()
      ->route('admin.subject-mocktest.index')
      ->with('success', 'Subject mock test saved successfully.');
  }

  public function show($id): View
  {
    $mockTest = SubjectMockTest::leftJoin('tbl_subject as s', 's.subject_id', '=', 'tbl_subject_mock_tests.subject_id')
      ->select('tbl_subject_mock_tests.*', 's.subject_name')
      ->where('tbl_subject_mock_tests.subject_mock_test_id', $id)
      ->firstOrFail();

    return view('Admin.Content.show_Subject_mock', compact('mockTest'));
  }

  public function edit($id): View
  {
    $mockTest = SubjectMockTest::findOrFail($id);
    $subjects = Subject::orderBy('subject_name', 'asc')->get();

    return view('Admin.Content.edit_subject_mock', compact('mockTest', 'subjects'));
  }

  public function update(Request $request, $id): RedirectResponse
  {
    $mockTest = SubjectMockTest::findOrFail($id);

    $request->validate([
      'subject_id'  => ['required', 'exists:tbl_subject,subject_id'],
      'title'       => ['required', 'string', 'max:255'],
      'upload_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,zip', 'max:10240'],
      'max_marks'   => ['required', 'integer', 'min:0'],
      'mock_test'   => ['required', 'string'],
    ]);

    $plainText = trim(strip_tags((string) $request->mock_test));
    if ($plainText === '') {
      return back()
        ->withErrors(['mock_test' => 'Question / Content is required.'])
        ->withInput();
    }

    $fileName = $mockTest->upload_file;

    if ($request->hasFile('upload_file')) {
      $uploadPath = public_path('uploads/mock-tests');
      if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
      }

      if (!empty($mockTest->upload_file) && File::exists($uploadPath . '/' . $mockTest->upload_file)) {
        File::delete($uploadPath . '/' . $mockTest->upload_file);
      }

      $file = $request->file('upload_file');
      $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
      $file->move($uploadPath, $fileName);
    }

    $actor = Auth::check() ? (Auth::user()->name ?? 'Admin') : 'Admin';

    $mockTest->update([
      'subject_id'  => $request->subject_id,
      'title'       => trim($request->title),
      'upload_file' => $fileName,
      'max_marks'   => $request->max_marks,
      'mock_test'   => $request->mock_test,
      'updated_by'  => $actor,
    ]);

    return redirect()
      ->route('admin.subject-mocktest.show', $mockTest->subject_mock_test_id)
      ->with('success', 'Subject mock test updated successfully.');
  }

  public function destroy($id): RedirectResponse
  {
    $mockTest = SubjectMockTest::findOrFail($id);

    $uploadPath = public_path('uploads/mock-tests');

    if (!empty($mockTest->upload_file) && File::exists($uploadPath . '/' . $mockTest->upload_file)) {
      File::delete($uploadPath . '/' . $mockTest->upload_file);
    }

    $mockTest->delete();

    return redirect()
      ->route('admin.subject-mocktest.index')
      ->with('success', 'Subject mock test deleted successfully.');
  }
}
