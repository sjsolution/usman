<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adminwallet;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class AddMoneyController extends Controller
{
    public function __construct(Adminwallet $Adminwallet)
	{
		$this->middleware('auth');
	    $this->Adminwallet  = $Adminwallet;
	}

  public function index(Request $request)
  {

    return view('admin.addmoney.list');
  }

  public function getAdminwalletlist(Request $request)
	{
	    $Adminwallet = $this->Adminwallet->get();
	      
	    return Datatables::of($Adminwallet)
	    	// ->addColumn('admin_id',function($Adminwallet){

      //          $admin = \App\Models\admin::find($Adminwallet->admin_id);
      //          return $admin->name? $admin->name: '--';
               
      //    	})
	      	->addColumn('action',function($Adminwallet){
	        return "
	        <a href='".url('admin/settings/addmoney/update')."/".$Adminwallet->id."' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>
	        "; 
	      })
	      ->make(true);
	}

    public function update(Request $request, $id)
	{
	    if ($request->isMethod('post')) {

	      $validator= Validator::make($request->all(),[
	        'label_name_en'=>'required',
	        'label_name_ar'=>'required',
	      ]);

	      $admin_wallet=Adminwallet::find($id);
	      $admin_wallet->amount 		= $request->amount;
	      $admin_wallet->credit_amount  = $request->credit_amount;
	      $admin_wallet->save();

	      return redirect('admin/settings/money');

	    }

	    $admin_wallet = Adminwallet::where('id',$id)->first();

	    return view('admin.addmoney.edit',compact('admin_wallet'));
	}
}
