<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendInvoiceMailable;
use Illuminate\Support\Facades\Mail;
use PDF;


class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $spEmail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order,$spEmail)
    {
        $this->order = $order;
        $this->spEmail = $spEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    
        if($this->order->category->type == 2){
            Mail::to($this->order->user->email)
            // ->bcc('a.salloum@maak.live')
            ->bcc('orders@maak.live')
            ->send(new SendInvoiceMailable($this->order));
        }
        else{

            Mail::to($this->order->user->email)->bcc($this->spEmail)->send(new SendInvoiceMailable($this->order));
        }
    }
}
