<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewHostelRequestReceived extends Notification
{
    use Queueable;

    protected $hostelRequest;

    public function __construct($hostelRequest)
    {
        $this->hostelRequest = $hostelRequest;
    }

    public function via($notifiable)
    {
        // For now, we use database notification. Mail can be added easily.
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'hostel_name' => $this->hostelRequest->hostel_name,
            'requester_name' => $this->hostelRequest->first_name . ' ' . $this->hostelRequest->last_name,
            'email' => $this->hostelRequest->email,
            'message' => "Nouvelle demande d'inscription pour {$this->hostelRequest->hostel_name}",
            'action_url' => '/admin/hostel-requests', // Placeholder
        ];
    }
}
