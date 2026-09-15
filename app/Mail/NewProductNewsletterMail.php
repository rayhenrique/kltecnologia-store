<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewProductNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public string $subscriberEmail,
        public string $unsubscribeUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Novo Produto Disponível: '.$this->product->name.' | KL Tecnologia',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.new-product',
            with: [
                'product' => $this->product,
                'subscriberEmail' => $this->subscriberEmail,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
