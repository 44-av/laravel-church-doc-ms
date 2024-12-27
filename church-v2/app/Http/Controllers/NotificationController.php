<?php

namespace App\Http\Controllers;

use App\Constant\MyConstant;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Services\useValidator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::query();

        if ($user->role == 'Parishioner') {
            $notifications->whereIn('type', ['Request', 'Donation', 'Announcement']);
        }

        $notifications = $notifications->get();
        $notifications->each(function ($notification) {
            $notification->message = $notification->message . ' by ' . Auth::user()->name;
        });

        return view('admin.notification', compact('notifications'));
    }

    public function store(Request $request)
    {
        $result = (new NotificationService(new useValidator))
            ->store($request);

        if ($result['error_code'] !== MyConstant::SUCCESS_CODE) {
            return response()->json([
                'error_code' => $result['error_code'],
                'message' => $result['message'],
            ], $result['status_code']);
        }

        return redirect()->back()->with([
            'error_code' => $result['error_code'],
            'message' => $result['message'],
        ]);
    }
}
