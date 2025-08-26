<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalApprovedNotification extends Notification
{
    use Queueable;
    // properties
    public $greeting;
    public $level;
    public $introLines;
    public $actionUrl;
    public $actionText;
    public $outroLines;
    public $salutation;
    public $subject;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject, $greeting, $level, $introLines, $actionUrl, $actionText, $outroLines, $salutation)
    {
        $this->greeting = $greeting;
        $this->level = $level;
        $this->introLines = $introLines;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->outroLines = $outroLines;
        $this->salutation = $salutation;
        $this->subject = $subject;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.proposal-approved', [
                'user' => $notifiable,
                'proposal' => $this->proposal ?? (object)['title' => 'Your Proposal', 'approved_amount' => 0, 'duration' => 12, 'id' => 1]
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
