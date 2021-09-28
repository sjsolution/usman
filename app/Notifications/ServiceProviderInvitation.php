<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ServiceProviderInvitation extends Notification
{
    use Queueable; 

    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user        = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('MAAK - Service Provider Login Credentails')
            ->greeting('Hello '.$this->user->full_name_en)
            ->line('') 
            ->line('Congratulation your account has been created successfully')
            ->line('Your Email Address : '.$this->user->email)
            ->line('Your Password : '.$this->user->password_txt)
            ->action('Login now', url('/userlogin'))
            ->line('Thank You')
            ->salutation('Maak');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
