<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $actionUrl;
    public $actionText;

    public function __construct($subject, $content, $actionUrl = null, $actionText = 'Click Here')
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.simple');
    }
}