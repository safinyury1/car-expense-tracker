<?php

namespace App\Mail;

use App\Models\Refueling;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefuelingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $refueling;
    public $user;
    public $car;

    public function __construct($refueling, $user, $car)
    {
        $this->refueling = $refueling;
        $this->user = $user;
        $this->car = $car;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⛽ Новая заправка - AutoCost',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refueling-notification',
        );
    }
}