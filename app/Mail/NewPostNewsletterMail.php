<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPostNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Post $post,
        public string $subscriberEmail,
        public string $unsubscribeUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📝 Novo Artigo no Blog: '.$this->post->title.' | KL Tecnologia',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.new-post',
            with: [
                'post' => $this->post,
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
