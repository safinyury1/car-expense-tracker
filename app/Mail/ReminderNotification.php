<?php

namespace App\Mail;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reminder;
    public $user;
    public $car;

    public function __construct($reminder, $user, $car)
    {
        $this->reminder = $reminder;
        $this->user = $user;
        $this->car = $car;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Напоминание о ТО - AutoCost',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder-notification',
        );
    }
}