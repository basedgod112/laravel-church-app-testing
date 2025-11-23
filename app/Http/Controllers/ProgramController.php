<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * @method authorize(string $string)
 */
class ProgramController extends Controller
{
    protected function validateProgram(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string',
            'day_of_week' => 'string|max:50',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after_or_equal:start_time',
            'published' => 'nullable|boolean',
        ]);
    }

    // Public index
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $programs = Program::where('published', true)
            ->orderByRaw("FIELD(day_of_week, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('start_time')
            ->get();

        return view('program.index', compact('programs'));
    }

    // Admin manage view
    public function manage(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {

        $programs = Program::orderBy('day_of_week')->orderBy('start_time')->get();
        return view('program.manage', compact('programs'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $program = new Program();
        return view('program.create', compact('program'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $this->validateProgram($request);
        $data['published'] = !$request->has('published') || $request->published;
        Program::create($data);
        return Redirect::route('program.manage')->with('success', 'Program item created.');
    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $program = Program::findOrFail($id);
        return view('program.edit', compact('program'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $program = Program::findOrFail($id);
        $data = $this->validateProgram($request);
        $data['published'] = !$request->has('published') || $request->published;
        $program->update($data);
        return Redirect::route('program.manage')->with('success', 'Program item updated.');
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return Redirect::route('program.manage')->with('success', 'Program item deleted.');
    }
}

