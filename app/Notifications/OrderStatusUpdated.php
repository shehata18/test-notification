<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Models\Order;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Check if the user has push subscriptions
        if ($notifiable->pushSubscriptions()->exists()) {
            return [WebPushChannel::class];
        }

        return [];
    }

    /**
     * Get the web push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush($notifiable, $notification)
    {
        $status = $this->order->status ?? 'updated';
        $orderId = $this->order->id ?? '0';

        return (new WebPushMessage())
            ->title('Order Status Updated')
            ->icon('/favicon.ico') // Use a default icon that exists
            ->body("Your order #{$orderId} status has been updated to {$status}.")
            ->action('View Order', "/orders/{$orderId}")
            ->data(['url' => "/orders/{$orderId}"]);
    }
}
