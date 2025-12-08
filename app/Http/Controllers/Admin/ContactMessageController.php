<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactMessageController extends Controller
{
    public function index(): Factory|View
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contact.index', compact('messages'));
    }

    public function show(ContactMessage $message): Factory|View
    {
        return view('admin.contact.show', compact('message'));
    }

    public function reply(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'reply_message' => 'required|string',
        ]);

        try {
            Mail::to($message->email)->send(new ContactReplyMail([
                'name' => $message->name,
                'original' => $message->message,
                'reply' => $data['reply_message'],
            ]));

            $message->reply_message = $data['reply_message'];
            $message->replied_at = now();
            $message->save();

            return redirect()->route('admin.contact.index')->with('success', 'Reply sent.');
        } catch (Throwable $e) {
            Log::error('Failed to send contact reply: '.$e->getMessage());
            return back()->withErrors('Could not send reply.');
        }
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.contact.index')->with('success', 'Message deleted.');
    }
}
