<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SPNotifyBookingNotification extends Notification
{
    use Queueable;

    protected $details = '';
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
       
        $url = url('/userlogin');

        return (new MailMessage) 
            ->subject($this->details['subject'])
            ->greeting($this->details['greeting'])
            ->line($this->details['body'])
            ->line('Booking ID : '.$this->details['booking_id'])
            ->line('User Name : '.$this->details['userName'])
            ->line('User Email : '.$this->details['userEmail'])
            ->line('Booking Amount : '.$this->details['booking_amt'])
            ->line('Booking Date : '.$this->details['bookingDate'])
            ->line('Booking Time : '.$this->details['bookingTime'])
            ->action('Login', url($url))
            ->line($this->details['thanks'])
            ->salutation('MAAK Support Team'); 
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
