<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): Mailable
    {
        return $this->subject('Response to your message')
                    ->view('emails.contact-reply')
                    ->with('data', $this->data);
    }
}
