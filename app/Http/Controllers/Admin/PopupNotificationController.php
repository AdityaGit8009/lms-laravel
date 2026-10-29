<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class PopupNotificationController extends Controller
{
  public function index(): View
  {
    $sessions = DB::table('tbl_session')
      ->select('session_id', 'session_name')
      ->orderByDesc('session_id')
      ->get();

    return view('Admin.Notification.popup_notification', compact('sessions'));
  }

  public function list(): JsonResponse
  {
    $items = PopupNotification::orderByDesc('popup_notification_id')->get();

    $data = $items->map(function ($item) {
      $sessionIds    = $this->normalizeIds((array) ($item->session_ids ?? []));
      $centerIds     = $this->normalizeIds((array) ($item->center_ids ?? []));
      $programmeIds  = $this->normalizeIds((array) ($item->programme_ids ?? []));
      $technologyIds = $this->normalizeIds((array) ($item->technology_ids ?? []));
      $batchIds      = $this->normalizeIds((array) ($item->batch_ids ?? []));

      return [
        'popup_notification_id' => $item->popup_notification_id,
        'session_ids'           => $sessionIds,
        'center_ids'            => $centerIds,
        'programme_ids'         => $programmeIds,
        'technology_ids'        => $technologyIds,
        'batch_ids'             => $batchIds,
        'title'                 => $item->title,
        'popup_from'            => $item->popup_from,
        'popup_to'              => $item->popup_to,
        'popup_from_formatted'  => !empty($item->popup_from)
          ? Carbon::parse($item->popup_from)->format('d M Y, h:i A')
          : '-',
        'popup_to_formatted'    => !empty($item->popup_to)
          ? Carbon::parse($item->popup_to)->format('d M Y, h:i A')
          : '-',
        'popup_status'          => $this->getPopupStatus($item->popup_from, $item->popup_to),
        'image'                 => $item->image,
        'image_url'             => !empty($item->image) ? asset('uploads/popup_notifications/' . $item->image) : null,
        'description'           => $item->description ?? '',
        'created_by'            => $item->created_by ?? '-',
        'created_at_formatted'  => !empty($item->created_at)
          ? Carbon::parse($item->created_at)->format('d M Y, h:i A')
          : '-',
        'session_names'         => $this->getOrderedNames('tbl_session', 'session_id', 'session_name', $sessionIds),
        'center_names'          => $this->getOrderedNames('tbl_centers', 'center_id', 'center_name', $centerIds),
        'programme_names'       => $this->getOrderedNames('tbl_programme', 'programme_id', 'programme_name', $programmeIds),
        'technology_names'      => $this->getOrderedNames('tbl_technology', 'technology_id', 'technology_name', $technologyIds),
        'batch_names'           => $this->getOrderedNames('tbl_batch', 'batch_id', 'batch_name', $batchIds),
      ];
    })->values();

    return response()->json([
      'status' => true,
      'data'   => $data,
    ]);
  }

  public function show(PopupNotification $popupNotification): JsonResponse
  {
    return response()->json([
      'status' => true,
      'data'   => [
        'popup_notification_id' => $popupNotification->popup_notification_id,
        'session_ids'           => $this->normalizeIds((array) ($popupNotification->session_ids ?? [])),
        'center_ids'            => $this->normalizeIds((array) ($popupNotification->center_ids ?? [])),
        'programme_ids'         => $this->normalizeIds((array) ($popupNotification->programme_ids ?? [])),
        'technology_ids'        => $this->normalizeIds((array) ($popupNotification->technology_ids ?? [])),
        'batch_ids'             => $this->normalizeIds((array) ($popupNotification->batch_ids ?? [])),
        'title'                 => $popupNotification->title,
        'popup_from'            => !empty($popupNotification->popup_from)
          ? Carbon::parse($popupNotification->popup_from)->format('Y-m-d\TH:i')
          : '',
        'popup_to'              => !empty($popupNotification->popup_to)
          ? Carbon::parse($popupNotification->popup_to)->format('Y-m-d\TH:i')
          : '',
        'image'                 => $popupNotification->image,
        'image_url'             => !empty($popupNotification->image)
          ? asset('uploads/popup_notifications/' . $popupNotification->image)
          : null,
        'description'           => $popupNotification->description ?? '',
      ],
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_ids'      => ['required', 'array', 'min:1'],
      'session_ids.*'    => ['integer', 'exists:tbl_session,session_id'],
      'center_ids'       => ['required', 'array', 'min:1'],
      'center_ids.*'     => ['integer', 'exists:tbl_centers,center_id'],
      'programme_ids'    => ['required', 'array', 'min:1'],
      'programme_ids.*'  => ['integer', 'exists:tbl_programme,programme_id'],
      'technology_ids'   => ['required', 'array', 'min:1'],
      'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
      'batch_ids'        => ['required', 'array', 'min:1'],
      'batch_ids.*'      => ['integer', 'exists:tbl_batch,batch_id'],
      'title'            => ['required', 'string', 'max:255'],
      'popup_from'       => ['required', 'date'],
      'popup_to'         => ['required', 'date', 'after:popup_from'],
      'image'            => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
      'description'      => ['nullable', 'string'],
    ], [
      'session_ids.required'    => 'Please select at least one session.',
      'center_ids.required'     => 'Please select at least one center.',
      'programme_ids.required'  => 'Please select at least one programme.',
      'technology_ids.required' => 'Please select at least one technology.',
      'batch_ids.required'      => 'Please select at least one batch.',
      'title.required'          => 'Title is required.',
      'popup_from.required'     => 'Popup from is required.',
      'popup_to.required'       => 'Popup to is required.',
      'popup_to.after'          => 'Popup to must be after popup from.',
      'image.required'          => 'Image is required.',
    ]);

    $fileName = $this->uploadImage($request);

    PopupNotification::create([
      'session_ids'    => $this->normalizeIds($validated['session_ids']),
      'center_ids'     => $this->normalizeIds($validated['center_ids']),
      'programme_ids'  => $this->normalizeIds($validated['programme_ids']),
      'technology_ids' => $this->normalizeIds($validated['technology_ids']),
      'batch_ids'      => $this->normalizeIds($validated['batch_ids']),
      'title'          => trim($validated['title']),
      'popup_from'     => Carbon::parse($validated['popup_from'])->format('Y-m-d H:i:s'),
      'popup_to'       => Carbon::parse($validated['popup_to'])->format('Y-m-d H:i:s'),
      'image'          => $fileName,
      'description'    => $validated['description'] ?? null,
      'created_by'     => $this->currentUserName(),
      'updated_by'     => $this->currentUserName(),
      'created_at'     => now(),
      'updated_at'     => now(),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Popup notification added successfully.',
    ]);
  }

  public function update(Request $request, PopupNotification $popupNotification): JsonResponse
  {
    $validated = $request->validate([
      'session_ids'      => ['required', 'array', 'min:1'],
      'session_ids.*'    => ['integer', 'exists:tbl_session,session_id'],
      'center_ids'       => ['required', 'array', 'min:1'],
      'center_ids.*'     => ['integer', 'exists:tbl_centers,center_id'],
      'programme_ids'    => ['required', 'array', 'min:1'],
      'programme_ids.*'  => ['integer', 'exists:tbl_programme,programme_id'],
      'technology_ids'   => ['required', 'array', 'min:1'],
      'technology_ids.*' => ['integer', 'exists:tbl_technology,technology_id'],
      'batch_ids'        => ['required', 'array', 'min:1'],
      'batch_ids.*'      => ['integer', 'exists:tbl_batch,batch_id'],
      'title'            => ['required', 'string', 'max:255'],
      'popup_from'       => ['required', 'date'],
      'popup_to'         => ['required', 'date', 'after:popup_from'],
      'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
      'description'      => ['nullable', 'string'],
    ], [
      'session_ids.required'    => 'Please select at least one session.',
      'center_ids.required'     => 'Please select at least one center.',
      'programme_ids.required'  => 'Please select at least one programme.',
      'technology_ids.required' => 'Please select at least one technology.',
      'batch_ids.required'      => 'Please select at least one batch.',
      'title.required'          => 'Title is required.',
      'popup_from.required'     => 'Popup from is required.',
      'popup_to.required'       => 'Popup to is required.',
      'popup_to.after'          => 'Popup to must be after popup from.',
    ]);

    $fileName = $popupNotification->image;

    if ($request->hasFile('image')) {
      $this->deleteImage($popupNotification->image);
      $fileName = $this->uploadImage($request);
    }

    $popupNotification->update([
      'session_ids'    => $this->normalizeIds($validated['session_ids']),
      'center_ids'     => $this->normalizeIds($validated['center_ids']),
      'programme_ids'  => $this->normalizeIds($validated['programme_ids']),
      'technology_ids' => $this->normalizeIds($validated['technology_ids']),
      'batch_ids'      => $this->normalizeIds($validated['batch_ids']),
      'title'          => trim($validated['title']),
      'popup_from'     => Carbon::parse($validated['popup_from'])->format('Y-m-d H:i:s'),
      'popup_to'       => Carbon::parse($validated['popup_to'])->format('Y-m-d H:i:s'),
      'image'          => $fileName,
      'description'    => $validated['description'] ?? null,
      'updated_by'     => $this->currentUserName(),
      'updated_at'     => now(),
    ]);

    return response()->json([
      'status'  => true,
      'message' => 'Popup notification updated successfully.',
    ]);
  }

  public function destroy(PopupNotification $popupNotification): JsonResponse
  {
    $this->deleteImage($popupNotification->image);
    $popupNotification->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Popup notification deleted successfully.',
    ]);
  }

  public function getCenters(Request $request): JsonResponse
  {
    $sessionIds = $this->normalizeIds((array) $request->input('session_ids', []));

    if (empty($sessionIds)) {
      return response()->json(['status' => true, 'centers' => []]);
    }

    $centers = DB::table('tbl_programme_centers as pc')
      ->join('tbl_centers as c', 'c.center_id', '=', 'pc.center_id')
      ->whereIn('pc.session_id', $sessionIds)
      ->select('c.center_id', 'c.center_name')
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
    $sessionIds = $this->normalizeIds((array) $request->input('session_ids', []));
    $centerIds  = $this->normalizeIds((array) $request->input('center_ids', []));

    if (empty($sessionIds) || empty($centerIds)) {
      return response()->json(['status' => true, 'programmes' => []]);
    }

    $programmes = DB::table('tbl_programme_centers as pc')
      ->join('tbl_programme as p', 'p.programme_id', '=', 'pc.programme_id')
      ->whereNull('p.deleted_at')
      ->whereIn('pc.session_id', $sessionIds)
      ->whereIn('pc.center_id', $centerIds)
      ->select('p.programme_id', 'p.programme_name')
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
    $sessionIds   = $this->normalizeIds((array) $request->input('session_ids', []));
    $programmeIds = $this->normalizeIds((array) $request->input('programme_ids', []));

    if (empty($sessionIds) || empty($programmeIds)) {
      return response()->json(['status' => true, 'technologies' => []]);
    }

    $technologies = DB::table('tbl_technology')
      ->whereIn('session_id', $sessionIds)
      ->whereIn('programme_id', $programmeIds)
      ->select('technology_id', 'technology_name')
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
    $sessionIds    = $this->normalizeIds((array) $request->input('session_ids', []));
    $programmeIds  = $this->normalizeIds((array) $request->input('programme_ids', []));
    $technologyIds = $this->normalizeIds((array) $request->input('technology_ids', []));

    if (empty($sessionIds) || empty($programmeIds) || empty($technologyIds)) {
      return response()->json(['status' => true, 'batches' => []]);
    }

    $batches = DB::table('tbl_batch')
      ->whereIn('session_id', $sessionIds)
      ->whereIn('programme_id', $programmeIds)
      ->whereIn('technology_id', $technologyIds)
      ->select('batch_id', 'batch_name')
      ->distinct()
      ->orderBy('batch_name')
      ->get();

    return response()->json([
      'status'  => true,
      'batches' => $batches,
    ]);
  }

  private function normalizeIds(array $ids): array
  {
    return array_values(array_unique(array_map('intval', array_filter($ids, function ($value) {
      return $value !== null && $value !== '';
    }))));
  }

  private function getOrderedNames(string $table, string $idColumn, string $nameColumn, array $ids): string
  {
    if (empty($ids)) {
      return '-';
    }

    $records = DB::table($table)
      ->whereIn($idColumn, $ids)
      ->select($idColumn, $nameColumn)
      ->orderBy($nameColumn)
      ->get()
      ->pluck($nameColumn)
      ->toArray();

    return empty($records) ? '-' : implode(', ', $records);
  }

  private function getPopupStatus($from, $to): string
  {
    if (empty($from) || empty($to)) {
      return 'No Schedule';
    }

    $now = now();
    $from = Carbon::parse($from);
    $to   = Carbon::parse($to);

    if ($now->lt($from)) {
      return 'Upcoming';
    }

    if ($now->gt($to)) {
      return 'Expired';
    }

    return 'Active';
  }

  private function currentUserName(): string
  {
    $user = Auth::user();
    return $user->name ?? $user->email ?? 'Admin';
  }

  private function uploadImage(Request $request): ?string
  {
    if (!$request->hasFile('image')) {
      return null;
    }

    $destinationPath = public_path('uploads/popup_notifications');

    if (!File::exists($destinationPath)) {
      File::makeDirectory($destinationPath, 0755, true, true);
    }

    $file = $request->file('image');
    $fileName = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
    $file->move($destinationPath, $fileName);

    return $fileName;
  }

  private function deleteImage(?string $fileName): void
  {
    if (empty($fileName)) {
      return;
    }

    $fullPath = public_path('uploads/popup_notifications/' . $fileName);

    if (File::exists($fullPath)) {
      File::delete($fullPath);
    }
  }
}
