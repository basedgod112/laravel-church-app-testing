<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Post;

class PostController extends Controller
{
    protected $type;

    public function index(): Factory|View
    {
        $posts = Post::where('type', $this->type)->get();
        return view($this->type . '.' . $this->type, ['posts' => $posts]);
    }

    public function create(): Factory|View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view($this->type . '.form', ['post' => new Post()]);
    }

    public function edit($id): Factory|View
    {
        $post = Post::findOrFail($id);
        return view($this->type . '.form', compact('post'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required|string',
        ]);
        $post = new Post();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->published_at = now();
        $post->type = $this->type;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('images/' . $this->type, uniqid() . '.' . $request->file('image')->getClientOriginalExtension(), 'public');
            $post->image = 'images/' . $this->type . '/' . basename($imagePath);
        }
        $post->save();
        return redirect()->route($this->type . '.index')->with('success', ucfirst($this->type) . ' post created successfully.');
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required|string',
        ]);
        $post = Post::findOrFail($id);
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->storeAs('images/' . $this->type, uniqid() . '.' . $request->file('image')->getClientOriginalExtension(), 'public');
            $post->image = 'images/' . $this->type . '/' . basename($imagePath);
        }
        $post->save();
        return redirect()->route($this->type . '.index')->with('success', ucfirst($this->type) . ' post updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route($this->type . '.index')->with('success', ucfirst($this->type) . ' post deleted successfully.');
    }
}

