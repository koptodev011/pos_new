<?php

namespace App\Mail;

use App\Models\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillRequested extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $orderData;
    public $currency;

    /**
     * Create a new message instance.
     */
    public function __construct($orderData,$currency)
    {
        $this->orderData = $orderData;
        $this->currency = $currency;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E Bill Requested',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.bill',
            with: [
                'orderData' => $this->orderData,
                'currency' => $this->currency,
                'type' => 'mail'
            ],
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
