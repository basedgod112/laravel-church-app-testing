<?php

namespace App\Http\Controllers\Parishioner;

use App\Http\Controllers\Controller;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::where('date', '>=', now())->orderBy('date')->paginate(10);
        return view('parishioner.schedules.index', compact('schedules'));
    }

    public function show(Schedule $schedule)
    {
        return view('parishioner.schedules.show', compact('schedule'));
    }
}
