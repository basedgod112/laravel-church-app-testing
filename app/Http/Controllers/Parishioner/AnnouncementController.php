<?php

namespace App\Http\Controllers\Parishioner;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('parishioner.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        return view('parishioner.announcements.show', compact('announcement'));
    }
}
