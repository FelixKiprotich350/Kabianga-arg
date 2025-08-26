<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $project;

    public function __construct($user, $project)
    {
        $this->user = $user;
        $this->project = $project;
    }

    public function build()
    {
        return $this->subject('Project Completed - ' . $this->project->title)
                    ->view('emails.project-completed');
    }
}