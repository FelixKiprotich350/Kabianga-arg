<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $project;
    public $report_type;

    public function __construct($user, $project, $report_type)
    {
        $this->user = $user;
        $this->project = $project;
        $this->report_type = $report_type;
    }

    public function build()
    {
        return $this->subject($this->report_type . ' Submitted - ' . $this->project->title)
                    ->view('emails.report-submitted');
    }
}