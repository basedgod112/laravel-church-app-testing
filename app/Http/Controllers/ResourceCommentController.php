<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResourceCommentController extends Controller
{
    public function store(StoreCommentRequest $request, Resource $resource): RedirectResponse
    {
        $data = $request->validated();

        $resource->comments()->create([
            'body' => $data['body'],
            'user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Comment posted.');
    }

    public function destroy(Request $request, Resource $resource, Comment $comment): RedirectResponse
    {
        // ensure the comment belongs to this resource
        if ($comment->resource_id !== $resource->id) {
            abort(404);
        }

        // only owner or admin can delete
        $user = $request->user();
        if ($user->id !== $comment->user_id && ! ($user->is_admin ?? false)) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
