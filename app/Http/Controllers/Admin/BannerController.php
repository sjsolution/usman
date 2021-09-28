<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;


class BannerController extends Controller
{
  public function __construct(Banner $banner)
  {
    $this->middleware('auth');
    $this->banner  = $banner;
  }

  public function index(Request $request)
  {
    return view('admin.banner.bannerView');
  }

  public function getbannerlist(Request $request)
  {
    $banner = $this->banner->get();
       
    return Datatables::of($banner)
      ->addColumn('banner_image',function($banner){
        return "<img class='tableimage' src='".config('app.AWS_URL').$banner->banner_image."'/>"; 
      })
      ->addColumn('action',function($banner){
        return "
        <a href='".url('admin/settings/banner/bannerDetails')."/".$banner->id."' class='btn btn-sm btn-gradient-info' title='View'><i class='fa fa-eye'></i></a>
        <a href='".url('admin/settings/banner/updateBanner')."/".$banner->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$banner->id." data-id1='".$banner->title_en."' class='btn btn-sm btn-gradient-danger deletebannner' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);

  }

  public function createbanner(Request $request)
  {
    if ($request->isMethod('post')) {

      $request->validate([
        'title_en'           => 'required|max:250',
        'title_ar'           => 'required|max:250',
        'description_en'     => 'required',
        'description_ar'     => 'required',
        'type'               => 'required',
        'banner_image.*'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
        'banner_image_ar.*'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|required'

      ]);

      $banner   = new Banner();

      if ($request->hasFile('banner_image')) {
        $file = $request->file('banner_image');
        $extension = $request->file('banner_image')->getClientOriginalExtension();
        $name = time() .'-banner-carcare.'.$extension;
        $filePath = 'banner/'.$name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $banner->banner_image = $filePath;
      }


      if ($request->hasFile('banner_image_ar')) {
        $file = $request->file('banner_image_ar');
        $extension = $request->file('banner_image_ar')->getClientOriginalExtension();
        $name = time() .'-banner-carcare.'.$extension;
        $filePath = 'banner/'.$name;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        $banner->banner_image_ar = $filePath;
      }

      $banner->title_en = $request->title_en;
      $banner->title_ar = $request->title_ar;
      $banner->description_en = $request->description_en;
      $banner->description_ar = $request->description_ar;
      $banner->type = $request->type;
      $banner->save();
      return redirect('admin/settings/banner')->with('message', 'Banner has been added succesfully !');
    }

    return view('admin.banner.bannerCreate');

  }

  public function  updateBanner(Request $request,$id)
  {
    if ($request->isMethod('post')) {
     
      $request->validate([
        'title_en'       => 'required|max:250',
        'title_ar'       => 'required|max:250',
        'description_en' => 'required',
        'description_ar' => 'required',
        'type'           => 'required',
        'banner_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'banner_image_ar.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
      ]);

      if($request->file('banner_image') != ''){

        $request->validate([
          'banner_image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('banner_image')) {
          $file = $request->file('banner_image');
          $extension = $request->file('banner_image')->getClientOriginalExtension();
          $name = time() .'-banner-carcare.'.$extension;
          $filePath = 'banner/'.$name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
        }

      }else{
        $filePath = $request->existingImage;
      }

      if($request->file('banner_image_ar') != ''){

        $request->validate([
          'banner_image_ar.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('banner_image_ar')) {
          $file = $request->file('banner_image_ar');
          $extension = $request->file('banner_image_ar')->getClientOriginalExtension();
          $name = time() .'-banner-carcare.'.$extension;
          $filePathAr = 'banner/'.$name;
          Storage::disk('s3')->put($filePathAr, file_get_contents($file));
        }

      }else{
        $filePathAr = $request->existingImageAr;
      }

      

      $banner   = Banner::find($id);
      $banner->title_en = $request->title_en;
      $banner->title_ar = $request->title_ar;
      $banner->description_en = $request->description_en;
      $banner->description_ar = $request->description_ar;
      $banner->banner_image = $filePath;
      $banner->banner_image_ar = $filePathAr;
      $banner->type = $request->type;
      $banner->save();
      return redirect('admin/settings/banner')->with('message', 'Banner has been updated succesfully !');
    
    }

    $banner = Banner::where('id',$id)->first();
    return view('admin.banner.bannerEdit',compact('banner'));

  }

  public function bannerUpdate(Request $request)
  {
    if ($request->isMethod('post')) {
      $id=$request->id;
      $banner   = Banner::find($id);
      if($request->type == 1){
        $message = 'Banner has been delete successfully';
        Banner::where(['id'=>$id])->delete();
      }else if($request->type == 2){
        if($request->activeStatus == 1){
          $banner->is_active = 0;
          $message = 'Banner has been inactive successfully';
        }else{
          $banner->is_active = 1;
          $message = 'Banner has been active successfully';
        }
      }
      
      $banner->save();
      return response()->json(['status'=>'success','message'=>$message]);
    }
  }


  public function bannerDetails(Request $request,$id)
  {
    if($id){
      $banner = Banner::find($id);
      if($banner){
        return view('admin.banner.bannerDetails',compact('banner'));
      }else{
        return redirect('admin/settings/banner');
      }
    }else{
      return redirect('admin/settings/banner');
    }
  }

}
