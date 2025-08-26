<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FundingOpportunityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $grant;

    public function __construct($user, $grant)
    {
        $this->user = $user;
        $this->grant = $grant;
    }

    public function build()
    {
        return $this->subject('New Funding Opportunity - ' . $this->grant->title)
                    ->view('emails.funding-opportunity');
    }
}