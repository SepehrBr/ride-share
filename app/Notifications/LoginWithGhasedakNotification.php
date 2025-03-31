<?php

namespace App\Notifications;

use App\Notifications\Channels\Ghasedak;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginWithGhasedakNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $phoneNumber){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [Ghasedak::class];
    }

    public function ghasedakSms($notifiable)
    {
        // generate code
        $code = random_int(10000, 99999);

        // store code in DB to be checked later
        $notifiable->update([
            'login_code' => $code
        ]);

        return [
            'message' => "code: {$code}",
            'phone' => $this->phoneNumber
        ];
    }
}
