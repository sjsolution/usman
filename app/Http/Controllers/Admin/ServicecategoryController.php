<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicecategory;
use App\Models\Category;
class ServicecategoryController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index(Request $request)
  {
      return view('admin.servicecategory.servicecategoryView');
  }

  public function getservicelist(Request $request)
  {

      $orderby = "id DESC";
      $limit = "10";
      $offset = "0";
      $category   = new Servicecategory();
      $category = $category->newQuery();
      $orderby = isset($request->jtSorting)?$request->jtSorting:$orderby;
      $limit = isset($request->jtPageSize)?$request->jtPageSize:$limit;
      $offset= isset($request->jtStartIndex)?$request->jtStartIndex:$offset;
      $category->select('id','name_en','name_ar');
      $category->where(['parent_id'=>0]);
      //$category->where();
      if ($request->isMethod('post')) {
          if ($request->has('keyword')) {
            if($request->input('keyword') !=''){
              $category->where('name_en','like', '%'.$request->input('keyword').'%');
              $category->orWhere('name_ar','like', '%'.$request->input('keyword').'%');
            }
         }
        $count = $category->count();
      }else{
        $count = $category->count();
      }
     $category->orderByRaw($orderby);
     $category->offset($offset);
     $category->limit($limit);
     $categorys = $category->get();
     return  response()->json([
        'Result' => 'OK',
        'TotalRecordCount' => $count,
        'Records' => $categorys,
      ],200);
  }


  public function createService(Request $request)
  {

      if ($request->isMethod('post')) {
        $request->validate([
          'name_en' => 'required|max:100',
        ]);
        $service   = new Servicecategory();
        $service->name_en     = $request->name_en;
        $service->name_ar     = $request->name_ar;
        $service->category_id = $request->category_id;
        $service->save();
        return redirect('admin/servicecategory')->with('message', 'Service category has been added succesfully !');
      }
      $categories = Category::get();
      return view('admin.servicecategory.servicecategoryCreate',compact('categories'));
  }

  public function updateCategory(Request $request,$id)
  {
    if ($request->isMethod('post')) {
        $request->validate([
          'name_en' => 'required|max:100',
        ]);
        $category   = Category::find($id);
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->save();
        return redirect('admin/servicecategory')->with('message', 'Category has been updated succesfully !');
    }
    $category = Category::where('id',$id)->first();
    return view('admin.servicecategory.servicecategoryEdit',compact('category'));
  }

  public function categoryUpdate(Request $request)
  {
      if ($request->isMethod('post')) {
          $id=$request->id;
          $category   = Category::find($id);
          if($request->type == 1){
            $message = 'Category has been delete successfully';
            //$category->is_delete = 1;
            Category::where(['id'=>$id])->delete();
          }else if($request->type == 2){
            if($request->activeStatus == 1){
              $category->is_active = 0;
              $message = 'Category has been inactive successfully';
            }else{
              $category->is_active = 1;
              $message = 'Category has been active successfully';
            }
          }
          $category->save();
          return response()->json(['status'=>'success','message'=>$message]);
          //return redirect('admin/category')->with('message', 'Category has been delete succesfully !');
      }
  }


  public function categoryDetails(Request $request,$id)
  {
    if($id){
        $category = Category::find($id);
        if($category){
          return view('admin.servicecategory.servicecategoryDetails',compact('category'));
        }else{
          return redirect('admin/servicecategory');
        }
    }else{
      return redirect('admin/servicecategory');
    }
  }
  public function getcategory($status){
   //dd($status);
   if($status==1)
   {
    $category=Category::where('type','1')->get();
   }
   if($status==2){
     $category=Category::where('type','2')->where('parent_id','0')->get();
   }
   if($status==3){
     $category=Category::where('type','3')->get();
   }
   $option='<option value=""> Select  category </option>';
   if(!empty($category)){

      foreach($category as $cate){
        $option.='<option value="'.$cate->id.'"> '.$cate->name_en.'</option>';
      }
   }
   return response()->json(array('status'=>'success','option' => $option));
      // return view('services.createservices',compact('category'));
 }
  public function getsubcategory($category){
    //dd($category);
    $subcategory = Category::where('parent_id',$category)->get();
    // echo "<pre>";print_r($subcategory);die;
    $option='<option value="">Select sub-category</option>';
    foreach($subcategory as $subcat){
      $option.='<option value="'.$subcat->id.'">'.$subcat->name_en.'</option>';
    }
    return response()->json(array('status'=>'success','option'=>$option));
  }
  
}
