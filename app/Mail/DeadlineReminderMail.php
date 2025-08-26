<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $item_type;
    public $title;
    public $due_date;
    public $days_remaining;
    public $description;
    public $action_url;

    public function __construct($user, $item_type, $title, $due_date, $days_remaining, $description, $action_url)
    {
        $this->user = $user;
        $this->item_type = $item_type;
        $this->title = $title;
        $this->due_date = $due_date;
        $this->days_remaining = $days_remaining;
        $this->description = $description;
        $this->action_url = $action_url;
    }

    public function build()
    {
        return $this->subject('Deadline Reminder - ' . $this->title)
                    ->view('emails.deadline-reminder');
    }
}