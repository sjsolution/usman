<?php

namespace App\Listeners;

use App\Events\SendOrderNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Pusher;
use App\Notification;

class SendOrderStatusNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    } 

    /**
     * Handle the event.
     *
     * @param  SendOrderNotification  $event
     * @return void
     */
    public function handle(SendOrderNotification $event)
    {
        //Remember to change this with your cluster name.
        $options = array(
            'cluster' => 'ap2', 
            'encrypted' => true
        );

        //Remember to set your credentials below.
        $pusher = new Pusher(
            'cb6a7799da02b4725fc1',
            'f9106a442c5ddc1d7d5a',
            '952881',
            $options
        ); 
	
        $notification['list'] = Notification::whereHas('notificationStatus',function($query){
           $query->where('is_admin_seen',0)->where('is_sp_seen',0);
        })->get();
		
        //Send a message to notify channel with an event name of notify-event
        $pusher->trigger('notify', 'notify-event', $notification);  

    }
}
