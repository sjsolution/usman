<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Session;
use Yajra\Datatables\Datatables;


class CategoryController extends Controller
{
  public function __construct(Category $category)
  {
    $this->middleware('auth');
    $this->category = $category;
  }

  public function index(Request $request)
  {
    return view('admin.category.categoryView');
  }

  public function getcategorylist(Request $request)
  {
    $category = $this->category->where('parent_id',0)->get();
      
    return Datatables::of($category)
      ->addColumn('banner_image',function($category){
        if(!empty($category->image)){
          return "<img class='tableimage' src='".config('app.AWS_URL').$category->image."'/>"; 
        }else{
          return '--';
        }
      })
      ->addColumn('sub_category',function($category){
        return $category->subcategory->count();
      })
      ->addColumn('status',function($category){
        if($category->is_active)
          return 'Active';
        else
          return 'In-Active';
      })
      ->addColumn('action',function($category){
        return "
        <a href='".url('admin/category/categoryDetails')."/".$category->id."' class='btn btn-sm btn-gradient-info' title='View'><i class='fa fa-eye'></i></a>
        <a href='".url('admin/category/updateCategory')."/".$category->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$category->id." data-id1='".$category->name_en."' class='btn btn-sm btn-gradient-danger deletecategory' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);

  }


  public function createCategory(Request $request)
  {
      
      if ($request->isMethod('post')) {
        
        $request->validate([
          'name_en'    => 'required|max:100',
          'name_ar'    => 'required|max:100',
          'type'       => 'required',
          'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        $category   = new Category();

        if ($request->hasFile('filename')) {
          $file = $request->file('filename');
          $extension = $request->file('filename')->getClientOriginalExtension();
          $name = time() .'-category-carcare.'.$extension;
          $filePath = 'category/'.$name;
          Storage::disk('s3')->put($filePath, file_get_contents($file));
          $category->image = $filePath;
        }

  
        $category->name_en                  = $request->name_en;
        $category->name_ar                  = $request->name_ar;
        $category->parent_id                = 0;
        $category->type                     = $request->type;
        $category->fixed_price              = !empty($request->fixed_price)        ? $request->fixed_price : 0;
        $category->commission_percent       = !empty($request->commission_percent) ? $request->commission_percent : 0;
        $category->user_applicable_fee_name = !empty($request->field_name) ? $request->field_name : null;
        $category->user_applicable_fee_name_ar = !empty($request->field_name_ar) ? $request->field_name_ar : null;
        $category->is_apply_user_app_fee    = !empty($request->is_apply) ? 1 : 0;
        $category->save();

        return redirect('admin/settings/categoryView')->with('message', 'Category has been added succesfully !');
      
      }

      return view('admin.category.categoryCreate');
  }

  public function updateCategory(Request $request,$id)
  {
    if ($request->isMethod('post')) {
        $request->validate([
          'name_en' => 'required|max:100',
          'name_ar' => 'required|max:100',
        ]);
        $category   = Category::find($id);
        if($request->file('filename') != ''){
            $request->validate([
              'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if ($request->hasFile('filename')) {
                $file = $request->file('filename');
                $extension = $request->file('filename')->getClientOriginalExtension();
                $name = time() .'-category-carcare.'.$extension;
                $filePath = 'category/'.$name;
                Storage::disk('s3')->put($filePath, file_get_contents($file));
                $category->image = $filePath;
              }
        }else{
          $category->image = $request->existingImage;
        }
        $category->name_en                  = $request->name_en;
        $category->name_ar                  = $request->name_ar;
        $category->type                     = $request->type;
        $category->fixed_price              = !empty($request->fixed_price)        ? $request->fixed_price : 0;
        $category->commission_percent       = !empty($request->commission_percent) ? $request->commission_percent : 0;
        $category->user_applicable_fee_name = !empty($request->field_name) ? $request->field_name : null;
        $category->user_applicable_fee_name_ar = !empty($request->field_name_ar) ? $request->field_name_ar : null;
        $category->is_apply_user_app_fee    = !empty($request->is_apply) ? 1 : 0;
        $category->save();
        return redirect('admin/settings/categoryView')->with('message', 'Category has been updated succesfully !');
    }
    $category = Category::where('id',$id)->first();
    return view('admin.category.categoryEdit',compact('category'));
  }

  public function categoryUpdate(Request $request)
  {
      if ($request->isMethod('post')) {
          $id=$request->id;
          $category   = Category::find($id);
          if($request->type == 1){
            $message = 'Category has been delete successfully';
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
      $subcategory = Category::where('parent_id',$id)->get();
      //dd($subcategory);
      if($category){
        return view('admin.category.categoryDetails',compact('category','subcategory'));
      }else{
        return redirect('admin/post');
      }
    }else{
      return redirect('admin/post');
    }
  }

  public function subcategory(Request $request)
  {
    return view('admin.subcategory.subcategoryView');
  }

  public function getsubcategorylist(Request $request)
  {

    $category = $this->category->with('categories')->where('parent_id','!=','0')->get();
      
    return Datatables::of($category)
      ->addColumn('category_name',function($category){
         return $category->categories->name_en;
      })
      ->addColumn('status',function($category){
        if($category->is_active)
          return 'Active';
        else
          return 'In-Active';
      }) 
      ->addColumn('action',function($category){
        return "
        <a href='".url('admin/settings/category/updateSubCategory')."/".$category->id."' class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
        <a href='javascript:void(0)' data-id=".$category->id." data-id1='".$category->name_en."' class='btn btn-sm btn-gradient-danger deletecategory' title='Delete'><i class='fa fa-trash'></i></a>"; 
      })
      ->make(true);

  }


  public function createSubCategory(Request $request)
  {
      if ($request->isMethod('post')) {

        $request->validate([
          'name_en' => 'required|max:100',
          'parent_id' => 'required',
          'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = explode('/',$request->parent_id);

        for($i=0; $i<sizeof($request->name_en); $i++)
        {
          $category   = new Category();
          $category->name_en = $request->name_en[$i];
          $category->name_ar = $request->name_ar[$i];
          $category->parent_id = $data[0];
          $category->image = "";
          //$category->type = $data[1];
          $category->type = 0;
          $category->save();
        }

          return redirect('admin/settings/category/subcategory')->with('message', 'Sub Category has been added succesfully !');
        }

    $categories = Category::where(['parent_id'=>0])->where('type','!=','2')->get();
    return view('admin.subcategory.subcategoryCreate',compact('categories'));
  }

  public function updateSubCategory(Request $request,$id)
  {
    if ($request->isMethod('post')) {

        $request->validate([
          'name_en' => 'required|max:100',
          'name_ar' => 'required|max:100',
        ]);

        if($request->file('filename') != ''){
            $request->validate([
              'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if ($request->hasFile('filename')) {
                $file = $request->file('filename');
                $extension = $request->file('filename')->getClientOriginalExtension();
                $name = time() .'-subcategory-carcare.'.$extension;
                $filePath = 'category/'.$name;
                Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
              }
              
        }else{
          $filePath = $request->existingImage;
        }
        $category   = Category::find($id);
        $category->name_en = $request->name_en;
        $category->name_ar = $request->name_ar;
        $category->parent_id = $request->parent_id;
        $category->image = $filePath;
        $category->save();
        return redirect('admin/settings/category/subcategory')->with('message', 'Sub Category has been updated succesfully !');
    }

    $category = Category::where('id',$id)->first();
    $categories = Category::where(['parent_id'=>0])->where('type','!=','2')->get();
    return view('admin.subcategory.subcategoryEdit',compact('category','categories'));
  }



  public function subcategoryUpdate(Request $request)
  {
    if ($request->isMethod('post')) {
        $id=$request->id;
        $lan = $request->language;
        $subcategory   = Category::find($id);
        $subcategory->save();
        $message = 'External attribute has been delete successfully';
        $subcategory = Category::find($id);
        $html = view("admin.subcategory.subcategoryAttribute",compact('subcategory'))->render();
        return response()->json(['status'=>'success', 'message'=>$message, 'html'=>$html,]);
    }
  }


  public function categorylist(Request $request)
  {
    if($request->isMethod('post'))
    {
      $category = Category::select('id','name_en','name_ar')->where('parent_id',0)->where('type',$request->value)->get();
      $html = view("admin.subcategory.getCategory",compact('category'))->render();
      return Response()->json(['html'=>$html,'status'=>1]);
    }
  }

}
