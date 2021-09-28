<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentGatewaySetting;
use Yajra\Datatables\Datatables;
use DB;

class PaymentGatewaySettingController extends Controller
{

    public function __construct(PaymentGatewaySetting $pgs)
    {
        $this->pgs  = $pgs;
    }


    public function index(){
        
        return view('admin.payment_gateway_setting.payment_gateway_setting_list');

       
    }

    public function list(){

        $payment = $this->pgs->get();
        
        return Datatables::of($payment)
          ->addColumn('name_en',function($payment){
            return $payment->name_en; 
          })
          ->addColumn('name_ar',function($payment){
            return $payment->name_ar; 
          })
          ->addColumn('status',function($payment){
            if($payment->is_default == 1) {
                return "Active";
            }else{
                return "In-active";
            }
          })
          ->make(true);
    }

    public function setting(){
        return view('admin.payment_gateway_setting.payment_gateway_update');
    }

    public function store(Request $request){

        $payment = $this->pgs->get();
        if(!empty($payment)){
            
            if($request->payment_type == 1){
                // Active Fatorah
                $fatorah = $this->pgs->where('id', 1)->update(['is_default' => "0"]);
                $fatorah = $this->pgs->where('id', 2)->update(['is_default' => "1"]);
            }else{
                // Active Hisabe
                $fatorah = $this->pgs->where('id', 1)->update(['is_default' => "1"]);
                $fatorah = $this->pgs->where('id', 2)->update(['is_default' => "0"]);
            }
        }

        return redirect('/admin/settings/paymentgateway')->with('message', 'Setting has been Updated succesfully !');
        
    }
}
