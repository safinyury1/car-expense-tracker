<?php

namespace App\Mail;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpenseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $expense;
    public $user;
    public $car;

    public function __construct($expense, $user, $car)
    {
        $this->expense = $expense;
        $this->user = $user;
        $this->car = $car;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 Новый расход - AutoCost',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.expense-notification',
        );
    }
}