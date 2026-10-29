<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationHead;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NotificationHeadController extends Controller
{
  public function index(): View
  {
    $notificationHeads = NotificationHead::orderBy('notification_head_id', 'desc')->get();

    return view('Admin.Notification.notification_heads', compact('notificationHeads'));
  }

  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'notification_head' => 'required|string|max:255|unique:tbl_notification_heads,notification_head',
    ], [
      'notification_head.required' => 'Notification head is required.',
      'notification_head.unique'   => 'This notification head already exists.',
    ]);

    $user = Auth::user();

    NotificationHead::create([
      'notification_head' => trim($request->notification_head),
      'created_by'        => $user->name ?? $user->email ?? 'Admin',
    ]);

    return redirect()
      ->route('admin.notification-heads.index')
      ->with('success', 'Notification head added successfully.');
  }

  public function update(Request $request, NotificationHead $notificationHead): RedirectResponse
  {
    $request->validate([
      'notification_head' => [
        'required',
        'string',
        'max:255',
        Rule::unique('tbl_notification_heads', 'notification_head')
          ->ignore($notificationHead->notification_head_id, 'notification_head_id'),
      ],
    ], [
      'notification_head.required' => 'Notification head is required.',
      'notification_head.unique'   => 'This notification head already exists.',
    ]);

    $notificationHead->update([
      'notification_head' => trim($request->notification_head),
    ]);

    return redirect()
      ->route('admin.notification-heads.index')
      ->with('success', 'Notification head updated successfully.');
  }

  public function destroy(NotificationHead $notificationHead): RedirectResponse
  {
    $notificationHead->delete();

    return redirect()
      ->route('admin.notification-heads.index')
      ->with('success', 'Notification head deleted successfully.');
  }
}
