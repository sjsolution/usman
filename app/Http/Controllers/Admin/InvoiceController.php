<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use PDF;

use App\Jobs\SendInvoiceEmailJob;

class InvoiceController extends Controller
{
    public function invoice(Orders $order)
    {
    	// echo"<pre>";print_r($order->subOrder);die;
        return view('admin.invoice.invoice',compact('order'));
    }

    public function downloadPDF(Orders $order)
    {
        $pdf = PDF::loadView('admin.invoice.invoice_mail', compact('order'));
        return $pdf->download('booking_invoice.pdf');
  
    }
}
