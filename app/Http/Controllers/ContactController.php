<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactFormRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;
use App\Models\ContactMessage;
use Throwable;

class ContactController extends Controller
{
    public function index(): Factory|View
    {
        return view('contact');
    }

    public function send(ContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // persist message
        ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $adminEmail = env('MAIL_ADMIN', config('mail.from.address'));

        try {
            Mail::to($adminEmail)->send(new ContactFormMail($data));

            if (method_exists(Mail::class, 'failures')) {
                $failures = Mail::failures();
                if (!empty($failures)) {
                    Log::error('Contact mail failures', ['failures' => $failures, 'admin' => $adminEmail]);
                    return redirect()->route('contact.index')->with('status', 'There was an error sending your message.');
                }
            }

            return redirect()->route('contact.index')->with('status', 'Thank you — your message has been sent.');
        } catch (Throwable $e) {
            Log::error('Contact mail exception: '.$e->getMessage(), [
                'exception' => $e,
                'admin' => $adminEmail,
                'data' => $data,
            ]);

            return redirect()->route('contact.index')->with('status', 'There was an error sending your message.');
        }
    }
}
