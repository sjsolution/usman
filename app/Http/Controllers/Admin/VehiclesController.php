<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Vehicletype;
use App\Models\Vehiclebrand;
use App\Models\Vehiclemodel;
use App\Models\Vehiclemanufacture;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;


class VehiclesController extends Controller
{
  public function __construct(
    Vehicletype        $vehicletype,
    Vehiclebrand       $vehicleBrand,
    Vehiclemodel       $vehicleModel,
    Vehiclemanufacture $vehicleManufacture
  )
  {
    $this->vehicletype        = $vehicletype;
    $this->vehicleBrand       = $vehicleBrand;
    $this->vehicleModel       = $vehicleModel;
    $this->vehicleManufacture = $vehicleManufacture;
  }

  public function getvehiclelist()
  {
    return view('admin.vehicletype.vehiclelist');
  }

  public function getvehiclelistdata(Request $request)
  {
    $vehicletype = $this->vehicletype->get();
      
    return Datatables::of($vehicletype)
      ->addColumn('banner_image',function($vehicletype){
        if(!empty($vehicletype->image)){
          return "<img class='tableimage' src='".config('app.AWS_URL').$vehicletype->image."'/>"; 
        }else{
          return '--';
        }
      })
     
      ->addColumn('status',function($vehicletype){
        if($vehicletype->is_active)
          return 'Active';
        else
          return 'In-Active';
      })
      ->addColumn('action',function($vehicletype){
        return "
        <a href='".url('admin/vehicle/update')."/".$vehicletype->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$vehicletype->id." data-id1='".$vehicletype->name_en."' class='btn btn-sm btn-gradient-danger deletevehicle' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);
  }

  public function createvehicle(Request $request)
  {

    if ($request->isMethod('post')) {

      $request->validate([
        'name_en' => 'required|max:100',
        'name_ar' => 'required|max:100',
        'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required'
      ]);

      $vehicle   = new Vehicletype();

      if ($request->hasFile('image')) {
          $file = $request->file('image');
          $extension = $request->file('image')->getClientOriginalExtension();
          $name = time() .'-vehicle-carcare.'.$extension;
          $filePath = 'vehicle/'.$name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
          $vehicle->image = $filePath;
        }
      $vehicle->name_en = $request->name_en;
      $vehicle->name_ar = $request->name_ar;

      $vehicle->save();
      return redirect('admin/settings/vehicle/list')->with('message', 'Vehicle has been added succesfully !');
    }

    return view('admin.vehicletype.addvehicle');

  }

  public function vehicleDelete(Request $request)
  {
    if ($request->isMethod('post')) {
      $id=$request->id;
      $vehicle   = Vehicletype::find($id);
      $message = 'Vehicle has been delete successfully';
      Vehicletype::where(['id'=>$id])->delete();
      return response()->json(['status'=>'success','message'=>$message]);
    }
  }

  public function vehicleUpdate(Request $request,$id)
  {
    $vehicle=Vehicletype::find($id);

    if($request->isMethod('post')){

        $request->validate([
          'name_en'     => 'required|max:20',
          'name_ar'       => 'required|max:20',
          'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        
        if($request->has('image')){
          $img = $vehicle->image;
          if(Storage::disk('s3')->exists($img)) {
            Storage::disk('s3')->delete($img);
          }
        }

        if ($request->hasFile('image')) {
          $file = $request->file('image');
          $extension = $request->file('image')->getClientOriginalExtension();
          $name = time() .'-vehicle-carcare.'.$extension;
          $filePath = 'vehicle/'.$name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
          $vehicle->image = $filePath;
        }

        $vehicle->name_en = $request->name_en;
        $vehicle->name_ar = $request->name_ar;
        $vehicle->save();
        $message = " Vehicle's  details has been updated successfully ";
     
        return redirect('admin/settings/vehicle/list')->with('success',$message);
    }
    
    return view('admin.vehicletype.vehicleDetailsUpdate',compact('vehicle'));

  }
 
  public function vehicleBrandList(Request $request)
  {
    $brand = $this->vehicleBrand->with('brandvehicle')->get();
      
    return Datatables::of($brand)
      ->addColumn('vehicle_type',function($brand){
        return $brand->brandvehicle->name_en;
      })  
      ->addColumn('status',function($brand){
        if($brand->is_active)
          return 'In-Active';
        else
          return 'Active';
      })
      ->addColumn('action',function($brand){
        return "
        <a href='".url('admin/settings/brand/updatebrand')."/".$brand->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$brand->id." data-id1='".$brand->name_en."' class='btn btn-sm btn-gradient-danger deletevehicle' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);
  }

  public function brandlist(Request $request)
  {
    return view('admin.brand.brand');
  }

  public function createbrand(Request $request)
  {
    if($request->isMethod('post')){
        $validator= Validator::make($request->all(),[
         'name_en' => 'required|max:100',
         'name_ar' => 'required|max:100',
         'vehicle' => 'required',
         'is_active' => 'required'
       ]);
       if($validator->fails()){
         return back()->with(['message' => $validator->errors()->first()]);
       }
       $brand = new Vehiclebrand;
       $brand->name_en=$request->name_en;
       $brand->name_ar=$request->name_ar;
       $brand->vehicle_type_id=$request->vehicle;
       $brand->is_active=$request->is_active;
       $brand->save();
       $message = "Brand has been added succesfully.";
       return redirect('admin/settings/brand/brandlist')->with('success',$message);
    }
    $vehicles = Vehicletype::where('is_active',1)->get();
    $brand = '';
    $title ="Add Vehicle brand";
    return view('admin.brand.addbrand',compact('vehicles','brand','title'));
  }

  public function updatebrand(Request $request,$id)
  {
    $brand= Vehiclebrand::find($id);
    if(!empty($brand)){
      if($request->isMethod('post')){
          $validator= Validator::make($request->all(),[
           'name_en' => 'required|max:100',
           'name_ar' => 'required|max:100',
           'vehicle' => 'required',
           'is_active' => 'required'
         ]);
         if($validator->fails()){
           return back()->with(['message' => $validator->errors()->first()]);
         }
         $brand->name_en=$request->name_en;
         $brand->name_ar=$request->name_ar;
         $brand->vehicle_type_id=$request->vehicle;
         $brand->is_active=$request->is_active;
         $brand->save();
         $message = "Brand has been updated succesfully.";
         return redirect('admin/settings/brand/brandlist')->with('success',$message);
      }
      $vehicles = Vehicletype::where('is_active',1)->get();
      $title ="Edit Vehicle brand";
      return view('admin.brand.addbrand',compact('vehicles','brand','title'));
    }else{
      return redirect('admin/settings/brand/brandlist');
    }
  }

  public function deleteBrand(Request $request)
  {
    if ($request->isMethod('post')) {
      $brand   = Vehiclebrand::find($request->id);
      $message = 'Vehicle brand has been delete successfully';
      $brand->delete();
      return response()->json(['status'=>'success','message'=>$message]);
    }
  }

  public function vehicleModelList(Request $request)
  {
    $brand = $this->vehicleModel->with('brands')->get();
      
    return Datatables::of($brand)
      ->addColumn('vehicle_brand',function($brand){
        return $brand->brands->name_en;
      })  
      ->addColumn('status',function($brand){
        if($brand->is_active)
          return 'In-Active';
        else
          return 'Active';
      })
      ->addColumn('action',function($brand){
        return "
        <a href='".url('admin/settings/model/updatemodel')."/".$brand->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$brand->id." data-id1='".$brand->name_en."' class='btn btn-sm btn-gradient-danger deletevehicle' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);
  }
  
  public function modellist(Request $request)
  {
    return view('admin.model.model');
  }
 
  public function createmodel(Request $request)
  {
    if($request->isMethod('post')){
        $validator= Validator::make($request->all(),[
        'name_en' => 'required|max:100',
        'name_ar' => 'required|max:100',
        'brand' => 'required',
        'is_active' => 'required'
      ]);
      if($validator->fails()){
        return back()->with(['message' => $validator->errors()->first()]);
      }
      $model = new Vehiclemodel;
      $model->name_en=$request->name_en;
      $model->name_ar=$request->name_ar;
      $model->vehicle_brand_id=$request->brand;
      $model->is_active=$request->is_active;
      $model->save();
      $message = "Brand model has been added succesfully.";
      return redirect('admin/settings/model/modellist')->with('success',$message);
    }
    $brand = Vehiclebrand::where('is_active',1)->get();
    $model = '';
    $title ="Add brand model";
    return view('admin.model.addmodel',compact('model','brand','title'));
  }

  public function updatemodel(Request $request,$id)
  {
    $model= Vehiclemodel::find($id);
    if(!empty($model)){
      if($request->isMethod('post')){
          $validator= Validator::make($request->all(),[
          'name_en' => 'required|max:100',
          'name_ar' => 'required|max:100',
          'brand' => 'required',
          'is_active' => 'required'
        ]);
        if($validator->fails()){
          return back()->with(['message' => $validator->errors()->first()]);
        }
        $model->name_en=$request->name_en;
        $model->name_ar=$request->name_ar;
        $model->vehicle_brand_id	=$request->brand;
        $model->is_active=$request->is_active;
        $model->save();
        $message = "Brand has been updated succesfully.";
        return redirect('admin/settings/model/modellist')->with('success',$message);
      }
      $brand = Vehiclebrand::where('is_active',1)->get();
      $title ="Edit brand model";
      return view('admin.model.addmodel',compact('model','brand','title'));
    }else{
      return redirect('admin/settings/model/modellist');
    }
  }

  public function deletemodel(Request $request)
  {
    if ($request->isMethod('post')) {
      $model   = Vehiclemodel::find($request->id);
      $message = 'Brand model has been delete successfully';
      $model->delete();
      return response()->json(['status'=>'success','message'=>$message]);
    }
  }

  public function vehicleManufactureList(Request $request)
  {
    $brand = $this->vehicleManufacture->with('vehicleModel')->get();
      
    return Datatables::of($brand)
      ->addColumn('vehicle_model_name',function($brand){
        if(!empty($brand->vehicleModel->name_en))
          return $brand->vehicleModel->name_en;
        else
          return '--';
      })
      ->addColumn('status',function($brand){
        if($brand->is_active)
          return 'In-Active';
        else
          return 'Active';
      })
      ->addColumn('action',function($brand){
        return "
        <a href='".url('admin/settings/manufacture/updatemanufacture')."/".$brand->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$brand->id." data-id1='".$brand->name_en."' class='btn btn-sm btn-gradient-danger deletevehicle' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);
  }

  public function manufacturelist(Request $request)
  {
    return view('admin.manufacture.manufacture'); 
  }

  public function createmanufacture(Request $request)
  {
    if($request->isMethod('post')){

        $validator= Validator::make($request->all(),[
          'from_year' => 'required',
          'to_year' => 'required',
          'vehicle' => 'required',
          'is_active' => 'required'
        ]);
        if($validator->fails()){
          return back()->with(['message' => $validator->errors()->first()]);
        }
        $manufacture = new Vehiclemanufacture;
        $manufacture->from_year=$request->from_year;
        $manufacture->to_year=$request->to_year;
        $manufacture->vehicle_model_id=$request->vehicle;
        $manufacture->is_active=$request->is_active;
        $manufacture->save();
        $message = "Manufacturing year has been added succesfully.";
        return redirect('admin/settings/manufacture/manufacturelist')->with('success',$message);
    }

    $vehicles = Vehiclemodel::where(['is_active'=>1])->get();
    $manufacture = '';
    $title ="Add manufacturing year";
    return view('admin.manufacture.addmanufacture',compact('vehicles','manufacture','title'));
  }

  public function updatemanufacture(Request $request,$id)
  {
    
    $manufacture= Vehiclemanufacture::find($id);

    if(!empty($manufacture))
    {
      if($request->isMethod('post')){

        $validator= Validator::make($request->all(),[
          'from_year' => 'required',
          'to_year' => 'required',
          'vehicle' => 'required',
          'is_active' => 'required'
        ]);

        if($validator->fails()){
          return back()->with(['message' => $validator->errors()->first()]);
        }

        $manufacture->from_year         = $request->from_year;
        $manufacture->to_year           = $request->to_year;
        $manufacture->vehicle_model_id	= $request->vehicle;
        $manufacture->is_active         = $request->is_active;
        $manufacture->save();
        $message = "Manufacturing year has been updated succesfully.";
        return redirect('admin/settings/manufacture/manufacturelist')->with('success',$message);
      }

      $vehicles = Vehiclemodel::where(['is_active'=>1])->get();
      $title ="Edit manufacturing year";
      return view('admin.manufacture.addmanufacture',compact('vehicles','manufacture','title'));
      
    }else{
      return redirect('admin/settings/manufacture/manufacturelist');
    }
  }

  public function deletemanufacture(Request $request)
  {
    if ($request->isMethod('post')) {
      $manufacture   = Vehiclemanufacture::find($request->id);
      $message = 'Manufacturing year has been delete successfully';
      $manufacture->delete();
      return response()->json(['status'=>'success','message'=>$message]);
    }
  }

  public function yearselection(Request $request)
  {
    if ($request->isMethod('post')) {
        $fromyear  = $request['fromyear'];
        $actualyear= range($fromyear,date("Y"));
        return view('admin.manufacture.yearscondition',compact(['actualyear']));
    }
  }

  public function coverage(Request $request)
  {
    $range = \App\RangeCoverage::where('status',1)->first();

    if ($request->isMethod('post')) {

      if($request->start_range < $request->end_range){

        \App\RangeCoverage::where('status',1)->update(['status' => 0 ]);
        $range   = \App\RangeCoverage::create([
          'start_range'  => $request->start_range,
          'end_range'    => $request->end_range
        ]);

      }else{
        return back()->with(['error' => 'End range should be greater than start range']);
      }

      return back()->with(['success' => 'Range Updated']);
      
    }

    return view('admin.coverage',compact('range'));
  }

}
