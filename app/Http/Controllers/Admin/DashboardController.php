<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\News;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        // 5 newest users for dashboard
        $users = User::orderByDesc('created_at')->take(5)->get();

        // latest news post for dashboard
        $latestNews = News::orderBy('created_at', 'desc')->first();

        // 5 messages per page, newest first
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(5);

        return view('admin.dashboard', compact('users', 'latestNews', 'messages'));
    }
}
