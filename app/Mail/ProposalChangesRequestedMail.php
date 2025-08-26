<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalChangesRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $proposal;
    public $changes;
    public $deadline;

    public function __construct($user, $proposal, $changes, $deadline = null)
    {
        $this->user = $user;
        $this->proposal = $proposal;
        $this->changes = $changes;
        $this->deadline = $deadline;
    }

    public function build()
    {
        return $this->subject('Changes Requested - ' . $this->proposal->title)
                    ->view('emails.proposal-changes-requested');
    }
}