<?php

namespace App\Notifications;

use App\Channels\SendsTelegramMessages;
use App\Channels\TelegramChannel;
use App\Contracts\InteractsWithTelegram;
use App\Enums\TransactionStatus;
use App\Models\TronTransaction;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function PHPUnit\Framework\matches;

class TransactionStatusNotification extends Notification implements SendsTelegramMessages
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public TronTransaction $transaction)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }


    public function toTelegram(InteractsWithTelegram $notifiable): string
    {
        $amount = $this->transaction->amount;
        $address = $this->transaction->to;
        // Check if it's a pool

        return match ($this->transaction->status) {
            TransactionStatus::AwaitingConfirmation => __('tron-transactions.pending'),
            TransactionStatus::Approved => __('tron-transactions.approved', ['amount' => $amount]),
            TransactionStatus::Rejected => __('tron-transactions.rejected', ['amount' => $amount]),
            TransactionStatus::Retry => __('tron-transactions.retry'),
        };
    }
}
