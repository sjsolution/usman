<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use PDF;

use App\Jobs\SendInvoiceEmailJob;

class InvoiceController extends Controller
{
    public function invoice(Orders $order)
    {
        return view('invoice.invoice',compact('order'));
    } 

    public function downloadPDF(Orders $order)
    { 
        $pdf = PDF::loadView('invoice.invoice_mail', compact('order'));
        return $pdf->download('invoice.pdf');
  
    }
}
