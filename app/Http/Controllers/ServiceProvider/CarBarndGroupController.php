<?php

namespace App\Http\Controllers\ServiceProvider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\ProviderGroup;
use DB;

class CarBarndGroupController extends Controller
{
    public function __construct(Group $group,ProviderGroup $providerGroup)
  {
    $this->middleware('auth');
    $this->group = $group;
    $this->providerGroup = $providerGroup;
    
  } 
  public function index(){
  	$groups = $this->group->get();
  	$provider = \Auth::user()->id;
  	$providerGroup = ProviderGroup::where('user_id',$provider)->get()->keyBy('group_id');
  		// echo"<pre>";print_r($providerGroup->toArray());die;

  	return view('service-providers.car_groups',compact('groups','providerGroup'));
  }

  public function store(Request $request){
  	try{
  		// echo"<pre>";print_r($request->toArray());die;
  		DB::beginTransaction();
  			$provider = \Auth::user()->id;
  			ProviderGroup::where('user_id',$provider)->delete();

  			if(!empty($request->group)){
  				foreach ($request->group as $group => $percentage) {
  					$pGroup = new ProviderGroup();
  					$pGroup->user_id = $provider;
  					$pGroup->group_id = $group;
  					$pGroup->percentage = $percentage;
  					$pGroup->save();
  				}
  			DB::commit();
  			}
  			return redirect()->route('cargroup.view');

  	}catch(\Exception $e){
  		DB::rollback();
  		throw $e;
  	}
  }
}
