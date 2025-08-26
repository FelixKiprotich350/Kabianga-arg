<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSubmittedMail extends Mailable
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
        return $this->subject('Proposal Submitted Successfully')
                    ->view('emails.proposal-submitted');
    }
}