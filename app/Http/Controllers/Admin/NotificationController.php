<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class NotificationController extends Controller
{
  public function index(): View
  {
    $sessions = DB::table('tbl_session')
      ->select('session_id', 'session_name')
      ->orderByDesc('session_id')
      ->get();

    $notificationHeads = DB::table('tbl_notification_heads')
      ->orderBy('notification_head', 'asc')
      ->get(['notification_head_id', 'notification_head']);

    return view('Admin.Notification.notification', compact(
      'sessions',
      'notificationHeads'
    ));
  }

  public function list(): JsonResponse
  {
    $notifications = DB::table('tbl_notification as n')
      ->leftJoin('tbl_session as s', 's.session_id', '=', 'n.session_id')
      ->leftJoin('tbl_centers as c', 'c.center_id', '=', 'n.center_id')
      ->leftJoin('tbl_programme as p', 'p.programme_id', '=', 'n.programme_id')
      ->leftJoin('tbl_technology as t', 't.technology_id', '=', 'n.technology_id')
      ->leftJoin('tbl_batch as b', 'b.batch_id', '=', 'n.batch_id')
      ->leftJoin('tbl_notification_heads as nh', 'nh.notification_head_id', '=', 'n.notification_head_id')
      ->select(
        'n.notification_id',
        'n.session_id',
        'n.center_id',
        'n.programme_id',
        'n.technology_id',
        'n.batch_id',
        'n.notification_head_id',
        'n.title',
        'n.notification_file',
        'n.description',
        'n.created_by',
        'n.created_at',
        's.session_name',
        'c.center_name',
        'p.programme_name',
        't.technology_name',
        'b.batch_name',
        'nh.notification_head'
      )
      ->orderByDesc('n.notification_id')
      ->get()
      ->map(function ($item) {
        return [
          'notification_id'       => $item->notification_id,
          'session_id'            => $item->session_id,
          'center_id'             => $item->center_id,
          'programme_id'          => $item->programme_id,
          'technology_id'         => $item->technology_id,
          'batch_id'              => $item->batch_id,
          'notification_head_id'  => $item->notification_head_id,
          'session_name'          => $item->session_name ?? '-',
          'center_name'           => $item->center_name ?? '-',
          'programme_name'        => $item->programme_name ?? '-',
          'technology_name'       => $item->technology_name ?? '-',
          'batch_name'            => $item->batch_name ?? '-',
          'notification_head'     => $item->notification_head ?? '-',
          'title'                 => $item->title ?? '-',
          'notification_file'     => $item->notification_file,
          'file_url'              => !empty($item->notification_file)
            ? asset('uploads/notifications/' . $item->notification_file)
            : null,
          'description'           => $item->description ?? '',
          'created_by'            => $item->created_by ?? '-',
          'created_at'            => $item->created_at,
          'created_at_formatted'  => !empty($item->created_at)
            ? Carbon::parse($item->created_at)->format('d M Y, h:i A')
            : '-',
        ];
      })
      ->values();

    return response()->json([
      'status' => true,
      'data'   => $notifications,
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_id'           => ['required', 'integer', 'exists:tbl_session,session_id'],
      'center_id'            => ['required', 'integer', 'exists:tbl_centers,center_id'],
      'programme_id'         => ['required', 'integer', 'exists:tbl_programme,programme_id'],
      'technology_id'        => ['required', 'integer', 'exists:tbl_technology,technology_id'],
      'batch_id'             => ['required', 'integer', 'exists:tbl_batch,batch_id'],
      'notification_head_id' => ['required', 'integer', 'exists:tbl_notification_heads,notification_head_id'],
      'title'                => ['required', 'string', 'max:255'],
      'notification_file'    => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip', 'max:10240'],
      'description'          => ['nullable', 'string'],
    ], [
      'session_id.required'           => 'Please select session.',
      'center_id.required'            => 'Please select center.',
      'programme_id.required'         => 'Please select programme.',
      'technology_id.required'        => 'Please select technology.',
      'batch_id.required'             => 'Please select batch.',
      'notification_head_id.required' => 'Please select notification head.',
      'title.required'                => 'Title is required.',
    ]);

    $fileName = $this->uploadNotificationFile($request);
    $userName = $this->getUserName();

    Notification::create([
      'session_id'           => $validated['session_id'],
      'center_id'            => $validated['center_id'],
      'programme_id'         => $validated['programme_id'],
      'technology_id'        => $validated['technology_id'],
      'batch_id'             => $validated['batch_id'],
      'notification_head_id' => $validated['notification_head_id'],
      'title'                => trim($validated['title']),
      'notification_file'    => $fileName,
      'description'          => $validated['description'] ?? null,
      'created_by'           => $userName,
      'updated_by'           => $userName,
      'created_at'           => now(),
      'updated_at'           => now(),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Notification added successfully.',
    ]);
  }

  public function update(Request $request, Notification $notification): JsonResponse
  {
    $validated = $request->validate([
      'session_id'           => ['required', 'integer', 'exists:tbl_session,session_id'],
      'center_id'            => ['required', 'integer', 'exists:tbl_centers,center_id'],
      'programme_id'         => ['required', 'integer', 'exists:tbl_programme,programme_id'],
      'technology_id'        => ['required', 'integer', 'exists:tbl_technology,technology_id'],
      'batch_id'             => ['required', 'integer', 'exists:tbl_batch,batch_id'],
      'notification_head_id' => ['required', 'integer', 'exists:tbl_notification_heads,notification_head_id'],
      'title'                => ['required', 'string', 'max:255'],
      'notification_file'    => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip', 'max:10240'],
      'description'          => ['nullable', 'string'],
    ], [
      'session_id.required'           => 'Please select session.',
      'center_id.required'            => 'Please select center.',
      'programme_id.required'         => 'Please select programme.',
      'technology_id.required'        => 'Please select technology.',
      'batch_id.required'             => 'Please select batch.',
      'notification_head_id.required' => 'Please select notification head.',
      'title.required'                => 'Title is required.',
    ]);

    $fileName = $notification->notification_file;

    if ($request->hasFile('notification_file')) {
      $this->deleteNotificationFile($notification->notification_file);
      $fileName = $this->uploadNotificationFile($request);
    }

    $notification->update([
      'session_id'           => $validated['session_id'],
      'center_id'            => $validated['center_id'],
      'programme_id'         => $validated['programme_id'],
      'technology_id'        => $validated['technology_id'],
      'batch_id'             => $validated['batch_id'],
      'notification_head_id' => $validated['notification_head_id'],
      'title'                => trim($validated['title']),
      'notification_file'    => $fileName,
      'description'          => $validated['description'] ?? null,
      'updated_by'           => $this->getUserName(),
      'updated_at'           => now(),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Notification updated successfully.',
    ]);
  }

  public function destroy(Notification $notification): JsonResponse
  {
    $this->deleteNotificationFile($notification->notification_file);
    $notification->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Notification deleted successfully.',
    ]);
  }

  public function getCentersBySession($sessionId): JsonResponse
  {
    $centers = DB::table('tbl_programme_centers as pc')
      ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
      ->select('c.center_id', 'c.center_name')
      ->where('pc.session_id', $sessionId)
      ->distinct()
      ->orderBy('c.center_name')
      ->get();

    return response()->json([
      'status'  => true,
      'centers' => $centers,
    ]);
  }

  public function getProgrammes(Request $request): JsonResponse
  {
    $programmes = DB::table('tbl_programme_centers as pc')
      ->join('tbl_programme as p', 'p.programme_id', '=', 'pc.programme_id')
      ->whereNull('p.deleted_at')
      ->select('p.programme_id', 'p.programme_name')
      ->where('pc.session_id', $request->session_id)
      ->where('pc.center_id', $request->center_id)
      ->distinct()
      ->orderBy('p.programme_name')
      ->get();

    return response()->json([
      'status'     => true,
      'programmes' => $programmes,
    ]);
  }

  public function getTechnologies(Request $request): JsonResponse
  {
    $technologies = DB::table('tbl_technology')
      ->select('technology_id', 'technology_name')
      ->where('session_id', $request->session_id)
      ->where('programme_id', $request->programme_id)
      ->distinct()
      ->orderBy('technology_name')
      ->get();

    return response()->json([
      'status'       => true,
      'technologies' => $technologies,
    ]);
  }

  public function getBatches(Request $request): JsonResponse
  {
    $batches = DB::table('tbl_batch')
      ->select('batch_id', 'batch_name')
      ->where('session_id', $request->session_id)
      ->where('programme_id', $request->programme_id)
      ->where('technology_id', $request->technology_id)
      ->distinct()
      ->orderBy('batch_name')
      ->get();

    return response()->json([
      'status'  => true,
      'batches' => $batches,
    ]);
  }

  private function getUserName(): string
  {
    $user = Auth::user();
    return $user->name ?? $user->email ?? 'Admin';
  }

  private function uploadNotificationFile(Request $request): ?string
  {
    if (!$request->hasFile('notification_file')) {
      return null;
    }

    $destinationPath = public_path('uploads/notifications');

    if (!File::exists($destinationPath)) {
      File::makeDirectory($destinationPath, 0755, true, true);
    }

    $file = $request->file('notification_file');
    $fileName = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
    $file->move($destinationPath, $fileName);

    return $fileName;
  }

  private function deleteNotificationFile(?string $fileName): void
  {
    if (empty($fileName)) {
      return;
    }

    $fullPath = public_path('uploads/notifications/' . $fileName);

    if (File::exists($fullPath)) {
      File::delete($fullPath);
    }
  }
}
