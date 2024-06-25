<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $forgotUrl;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $forgotUrl)
    {
        $this->user = $user;
        $this->forgotUrl = $forgotUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset Password Email');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(markdown: 'emails.forgotPass');
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
    // public function build()
    // {
    //     return $this->from('yoemforever@gmail.com')
    //         ->view('mails.mail-notify')
    //         ->subject('Notification email');
    // }
}
