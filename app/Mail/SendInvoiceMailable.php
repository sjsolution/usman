<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class SendInvoiceMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $data['order'] = $this->order;

        $pdf = PDF::loadView('invoice.invoice_mail',  $data);
        
        return $this->subject('Maak  :New Booking Received')
            ->view('invoice.invoice_mail')
            ->attachData($pdf->output(), "invoice.pdf");
    }
}
