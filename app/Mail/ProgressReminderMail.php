<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgressReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    public $dueDate;
    public $reportType;
    public $daysRemaining;

    public function __construct($user, $project, $dueDate, $reportType = 'Progress Report', $daysRemaining = 0)
    {
        $this->user = $user;
        $this->project = $project;
        $this->dueDate = $dueDate;
        $this->reportType = $reportType;
        $this->daysRemaining = $daysRemaining;
    }

    public function build()
    {
        return $this->subject('Progress Report Reminder - ' . $this->project->title)
                    ->view('emails.progress-reminder');
    }
}