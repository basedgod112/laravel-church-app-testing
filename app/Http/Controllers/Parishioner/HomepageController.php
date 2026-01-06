<?php

namespace App\Http\Controllers\Parishioner;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Schedule;

class HomepageController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->take(5)->get();
        $schedules = Schedule::where('date', '>=', now())->orderBy('date')->take(5)->get();

        return view('parishioner.homepage', compact('announcements', 'schedules'));
    }
}
