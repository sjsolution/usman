<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserReportNotitfication;



class UserReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $data = [
            'subject'  => 'User Report',
            'greeting' => 'Hello',
            'body'     => $this->report->order->serviceProvider[0]->serviceProvider->full_name_en .'('.$this->report->user->full_name_en.') has reported '. $this->report->order->user->full_name_en .' for this booking : '. $this->report->order->order_number,
            'thanks'   => 'Thank You'
 
        ];

        $users = new \App\User;
        $users->email = 'sanu@o2onelabs.com';

        $users->notify(new UserReportNotitfication($data));

    }
}
