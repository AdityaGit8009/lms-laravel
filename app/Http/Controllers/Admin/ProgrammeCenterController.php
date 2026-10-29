<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Programme;
use App\Models\ProgrammeCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProgrammeCenterController extends Controller
{
  public function getProgrammesBySession(Request $request): JsonResponse
  {
    $request->validate([
      'session_id' => ['required', 'integer', 'exists:tbl_session,session_id'],
    ]);

    $programmes = Programme::where('session_id', $request->session_id)
      ->whereNull('deleted_at')
      ->orderBy('programme_name', 'asc')
      ->get(['programme_id', 'programme_name']);

    return response()->json([
      'status' => true,
      'data'   => $programmes,
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_id'   => ['required', 'integer', 'exists:tbl_session,session_id'],
      'programme_id' => ['required', 'integer', 'exists:tbl_programme,programme_id'],
      'center_ids'   => ['required', 'array', 'min:1'],
      'center_ids.*' => ['required', 'integer', 'exists:tbl_centers,center_id'],
    ], [
      'session_id.required'   => 'Session is required.',
      'programme_id.required' => 'Programme is required.',
      'center_ids.required'   => 'Please select at least one center.',
      'center_ids.array'      => 'Center selection is invalid.',
      'center_ids.min'        => 'Please select at least one center.',
    ]);

    $programme = Programme::where('programme_id', $validated['programme_id'])
      ->where('session_id', $validated['session_id'])
      ->whereNull('deleted_at')
      ->first();

    if (!$programme) {
      return response()->json([
        'status'  => false,
        'message' => 'Selected programme does not belong to selected session.',
        'errors'  => [
          'programme_id' => ['Selected programme does not belong to selected session.']
        ]
      ], 422);
    }

    try {
      DB::transaction(function () use ($validated) {
        $selectedCenterIds = array_values(array_unique($validated['center_ids']));
        $userName = $this->currentUserName();

        $alreadyAssigned = ProgrammeCenter::where('session_id', $validated['session_id'])
          ->where('programme_id', $validated['programme_id'])
          ->whereIn('center_id', $selectedCenterIds)
          ->pluck('center_id')
          ->toArray();

        $newCenterIds = array_values(array_diff($selectedCenterIds, $alreadyAssigned));

        if (empty($newCenterIds)) {
          $duplicateCenterNames = Center::whereIn('center_id', $alreadyAssigned)
            ->orderBy('center_name', 'asc')
            ->pluck('center_name')
            ->toArray();

          throw new \Exception(
            'All selected centers are already assigned: ' . implode(', ', $duplicateCenterNames) . '.'
          );
        }

        foreach ($newCenterIds as $centerId) {
          ProgrammeCenter::create([
            'session_id'   => $validated['session_id'],
            'programme_id' => $validated['programme_id'],
            'center_id'    => $centerId,
            'created_by'   => $userName,
            'updated_by'   => $userName,
          ]);
        }
      });

      return response()->json([
        'status'  => true,
        'message' => 'Centers assigned successfully.',
      ]);
    } catch (Throwable $e) {
      return $this->handleFriendlyException($e);
    }
  }

  public function updateGroup(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'old_session_id'   => ['required', 'integer'],
      'old_programme_id' => ['required', 'integer'],
      'session_id'       => ['required', 'integer', 'exists:tbl_session,session_id'],
      'programme_id'     => ['required', 'integer', 'exists:tbl_programme,programme_id'],
      'center_ids'       => ['nullable', 'array'],
      'center_ids.*'     => ['integer', 'exists:tbl_centers,center_id'],
    ], [
      'center_ids.array' => 'Center selection is invalid.',
    ]);

    $programme = Programme::where('programme_id', $validated['programme_id'])
      ->where('session_id', $validated['session_id'])
      ->whereNull('deleted_at')
      ->first();

    if (!$programme) {
      return response()->json([
        'status'  => false,
        'message' => 'Selected programme does not belong to selected session.',
        'errors'  => [
          'programme_id' => ['Selected programme does not belong to selected session.']
        ]
      ], 422);
    }

    try {
      DB::transaction(function () use ($validated) {
        $userName = $this->currentUserName();

        ProgrammeCenter::where('session_id', $validated['old_session_id'])
          ->where('programme_id', $validated['old_programme_id'])
          ->delete();

        $centerIds = array_values(array_unique($validated['center_ids'] ?? []));

        foreach ($centerIds as $centerId) {
          ProgrammeCenter::create([
            'session_id'   => $validated['session_id'],
            'programme_id' => $validated['programme_id'],
            'center_id'    => $centerId,
            'created_by'   => $userName,
            'updated_by'   => $userName,
          ]);
        }
      });

      return response()->json([
        'status'  => true,
        'message' => 'Center mapping updated successfully.',
      ]);
    } catch (Throwable $e) {
      return $this->handleFriendlyException($e);
    }
  }

  public function destroyGroup(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'session_id'   => ['required', 'integer', 'exists:tbl_session,session_id'],
      'programme_id' => ['required', 'integer', 'exists:tbl_programme,programme_id'],
    ]);

    ProgrammeCenter::where('session_id', $validated['session_id'])
      ->where('programme_id', $validated['programme_id'])
      ->delete();

    return response()->json([
      'status'  => true,
      'message' => 'Center mapping removed successfully.',
    ]);
  }

  private function currentUserName(): string
  {
    return trim((string)(auth()->user()->name ?? 'Admin')) ?: 'Admin';
  }

  private function handleFriendlyException(Throwable $e): JsonResponse
  {
    Log::error('ProgrammeCenterController error', [
      'message' => $e->getMessage(),
      'file'    => $e->getFile(),
      'line'    => $e->getLine(),
    ]);

    $message = $e->getMessage();

    if (
      str_contains($message, 'Duplicate entry') ||
      str_contains($message, 'Integrity constraint violation')
    ) {
      return response()->json([
        'status'  => false,
        'message' => 'This center is already assigned for the selected session and programme.',
        'errors'  => [
          'center_ids' => ['This center is already assigned for the selected session and programme.']
        ]
      ], 422);
    }

    if (
      str_contains($message, 'already assigned') ||
      str_contains($message, 'All selected centers are already assigned')
    ) {
      return response()->json([
        'status'  => false,
        'message' => $message,
        'errors'  => [
          'center_ids' => [$message]
        ]
      ], 422);
    }

    return response()->json([
      'status'  => false,
      'message' => 'Something went wrong. Please try again.',
    ], 500);
  }
}
