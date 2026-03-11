<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $title, public string $messageText)
    {
    }

    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.test-email');
    }
}

