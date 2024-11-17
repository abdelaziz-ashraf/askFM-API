<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnansweredQuestionsNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $unansweredQuestionsCount;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $unansweredQuestionsCount)
    {
        $this->user = $user;
        $this->unansweredQuestionsCount = $unansweredQuestionsCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unanswered Questions Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.unanswered-questions-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
