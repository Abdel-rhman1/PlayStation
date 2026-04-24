<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public string $email;
    public string $token;
    public string $tenantName;
    public string $inviterName;

    public function __construct(string $email, string $token, string $tenantName, string $inviterName)
    {
        $this->email = $email;
        $this->token = $token;
        $this->tenantName = $tenantName;
        $this->inviterName = $inviterName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You have been invited to join {$this->tenantName} on PlayStation Pro",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }
}
