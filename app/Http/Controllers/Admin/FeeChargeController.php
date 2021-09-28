<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FeeCharge;
Use Alert;

class FeeChargeController extends Controller
{
    public function __construct(FeeCharge $feeCharge)
    {
        $this->feeCharge = $feeCharge;
    } 

    public function index()
    {
        $charges = $this->feeCharge->where('status',1)->first();
        
        return view('admin.fee_charge.fee_charge',compact('charges'));
    }

    public function store(Request $request)
    {
        $this->feeCharge->where('status',1)->update(['status' => 0]);

        $charges = $this->feeCharge->create([
            'knet_fixed_charges'        => $request->fixed_price_knet,
            'knet_commission_charges'   => $request->commission_per_knet,
            'other_fixed_charges'       => $request->fixed_price_other,
            'other_commission_charges'  => $request->commission_per_other
        ]);

        toast('Commission fees setting successfully saved','success')->timerProgressBar();


        return view('admin.fee_charge.fee_charge',compact('charges'));

    }
    
}
