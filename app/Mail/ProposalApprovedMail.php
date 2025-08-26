<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $proposal;

    public function __construct($user, $proposal)
    {
        $this->user = $user;
        $this->proposal = $proposal;
    }

    public function build()
    {
        return $this->subject('Congratulations! Your Proposal Has Been Approved')
                    ->view('emails.proposal-approved');
    }
}