<?php
namespace App\Mail;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Shop $shop
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ تم تأكيد طلبك ' . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmed',
            with: [
                'order' => $this->order,
                'shop' => $this->shop,
                'url' => url('/order-success/' . $this->order->id),
            ],
        );
    }
}
