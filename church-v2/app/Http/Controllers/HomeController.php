<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'Admin') {
                return redirect()->route('admin_dashboard');
            } else {
                return redirect()->route('parishioner_dashboard');
            }
        }
        $announcements = Announcement::all();

        return view('welcome', compact('announcements'));
    }
}
