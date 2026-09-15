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

class AdminNewOrderMail extends Mailable
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
        public User $customer,
        Collection|array|Order $orders
    ) {
        $this->orders = $orders instanceof Collection
            ? $orders
            : collect(is_array($orders) ? $orders : [$orders]);
    }

    public function envelope(): Envelope
    {
        $orderIds = $this->orders->pluck('id')->implode(', #');
        $totalFormatted = number_format((float) $this->orders->sum('amount'), 2, ',', '.');
        $customerName = $this->customer->name;

        return new Envelope(
            subject: "🎉 [Nova Venda] Pedido #{$orderIds} - R$ {$totalFormatted} - {$customerName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-order',
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
