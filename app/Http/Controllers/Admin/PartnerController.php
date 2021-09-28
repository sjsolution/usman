<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Partner;
use Session;
use Yajra\Datatables\Datatables;


class PartnerController extends Controller
{

  public function __construct(Partner $partner)
  {
    $this->middleware('auth');
    $this->partner  = $partner;
  }

  public function index()
  {
    return view('admin.ourpartners.ourpartnersView');
  }

  public function  createpartner(Request $request)
  {
    if ($request->isMethod('post')) {
      $request->validate([
        'full_name_en' => 'required|max:100',
        'full_name_ar' => 'required|max:100',
        'partner_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required'
      ]);
      
      $partner= new Partner();

      if ($request->hasFile('partner_image')) {
        $file = $request->file('partner_image');
        $extension = $request->file('partner_image')->getClientOriginalExtension();
        $name = time() .'-partners-carcare.'.$extension;
        $filePath = 'partner/'.$name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $partner->partner_image = $filePath;
      }

      $partner->partnername_en = $request->full_name_en;
      $partner->partnername_ar = $request->full_name_ar;
      $partner->is_active      = '1';
      $partner->save();
      $message = 'Partner created successfully.. :-)';
      
      return redirect('admin/settings/ourpartnerslist')->with('message', $message);
    }
    
    return view('admin.ourpartners.createpartner');
  
  }

  public function getpartnerslist(Request $request)
  {
    $partner = $this->partner->get();
      
    return Datatables::of($partner)
      ->addColumn('checkbox', function ($partner) {
          return '<input type="checkbox" id="'.$partner['id'].'" name="someCheckbox" class="userId" />';
      })
      ->addColumn('banner_image',function($partner){
        return "<img class='tableimage' src='".config('app.AWS_URL').$partner->partner_image."'/>"; 
      })
      ->addColumn('status',function($partner){
        if($partner->is_active)
          return 'Active';
        else
          return 'In-Active';
      })
      ->addColumn('action',function($partner){
        return "
        <a href='".url('admin/settings/ourpartner/details')."/".$partner->id."' class='btn btn-sm btn-gradient-info' title='View'><i class='fa fa-eye'></i></a>
        <a href='".url('admin/settings/ourpartner/update')."/".$partner->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$partner->id." data-id1='".$partner->partnername_en."' class='btn btn-sm btn-gradient-danger deletepartner' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);

  }

  public function ourpartnerDetails(Request $request,$id)
  {
       
    if($id)
    {
      $partner = Partner::find($id);
      $partner = Partner::where('id',$request->id)->first();
      if($request->id){
        return view('admin.ourpartners.ourpartnerDetails',compact('partner'));
      }else{
        return redirect('admin/post');
      }

    }else{
      return redirect('admin/post');
    }
  }

  public function ourpartnerUpdate(Request $request,$id)
  {
    $partner = Partner::find($id);

    if($request->isMethod('post'))
    {
      Session::flash('message', 'Details Updated!');
      Session::flash('alert-class', 'alert-danger');
      $id=$request->id;
      $request->validate([
        'full_name_en'     => 'required|max:50',
        'full_name_ar'       => 'required|max:50',
      ]);

      if($request->has('partner_image')){
          $img = $partner->partner_image;   
      // this returns the path of the file stored in the db
          if(Storage::disk('s3')->exists($img)) {
            Storage::disk('s3')->delete($img);
      
        }
      }
          
      if ($request->hasFile('partner_image')) {
        $file = $request->file('partner_image');
        $extension = $request->file('partner_image')->getClientOriginalExtension();
        $name = time() .'-partners-carcare.'.$extension;
        $filePath = 'partner/'.$name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $partner->partner_image = $filePath;
      }

      $partner->partnername_en = $request->full_name_en;
      $partner->partnername_ar = $request->full_name_ar;
      $partner->save();
      $message = " Partner's  details has been updated successfully ";
      Session::flash('$message');
      Session::flash('alert-class', 'alert-danger');
      return view('admin.ourpartners.ourpartnerEdit',compact('partner'));
    }
    
    return view('admin.ourpartners.ourpartnerEdit',compact('partner'));

  }

  public function ourpartnerDelete(Request $request)
  {
    $id = $request->id;
    $partner = Partner::where('id',$id)->first()->delete();
    return response()->json(['status'=>1, 'message'=>'success']);
  }

  public function selectedstatusupdate(Request $request)
  {
    if($request->status=='0'){
      $partner = Partner::whereIn('id',$request->id)->update(['is_active'=>'0']);
    }elseif($request->status=='1'){
      $serviceprovider = Partner::whereIn('id',$request->id)->update(['is_active'=>'1']);
    }
    return response()->json(['status'=>1, 'message'=>'success']); 
  }

}