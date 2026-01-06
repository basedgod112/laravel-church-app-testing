<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SacramentalRecord;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index()
    {
        $records = SacramentalRecord::all();
        return view('admin.records.index', compact('records'));
    }

    public function create()
    {
        return view('admin.records.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sacrament_type' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        SacramentalRecord::create($request->all());

        return redirect()->route('admin.records.index')->with('success', 'Sacramental record created successfully.');
    }

    public function show(SacramentalRecord $record)
    {
        return view('admin.records.show', compact('record'));
    }

    public function edit(SacramentalRecord $record)
    {
        return view('admin.records.edit', compact('record'));
    }

    public function update(Request $request, SacramentalRecord $record)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sacrament_type' => 'required|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $record->update($request->all());

        return redirect()->route('admin.records.index')->with('success', 'Sacramental record updated successfully.');
    }

    public function destroy(SacramentalRecord $record)
    {
        $record->delete();
        return redirect()->route('admin.records.index')->with('success', 'Sacramental record deleted successfully.');
    }
}
