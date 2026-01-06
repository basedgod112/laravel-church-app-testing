<?php

namespace App\Http\Controllers\Clergy;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::all();
        return view('clergy.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('clergy.schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location' => 'nullable|string|max:255',
        ]);

        Schedule::create($request->all());

        return redirect()->route('clergy.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function show(Schedule $schedule)
    {
        return view('clergy.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        return view('clergy.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location' => 'nullable|string|max:255',
        ]);

        $schedule->update($request->all());

        return redirect()->route('clergy.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('clergy.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
