<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Collection<int, Order>
     */
    public Collection $orders;

    /**
     * @param  Collection<int, Order>|array<int, Order>|Order  $orders
     */
    public function __construct(
        public User $user,
        Collection|array|Order $orders
    ) {
        $this->orders = $orders instanceof Collection
            ? $orders
            : collect(is_array($orders) ? $orders : [$orders]);
    }

    public function envelope(): Envelope
    {
        $orderIds = $this->orders->pluck('id')->implode(', #');

        return new Envelope(
            subject: "Pedido #{$orderIds} Recebido - Aguardando Confirmação de Pagamento",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-pending',
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
