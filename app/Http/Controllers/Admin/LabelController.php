<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\LabelExport;
use App\Imports\LabelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Labels;
use Illuminate\Support\Facades\Validator;
use App\Models\Toprated;
use Yajra\Datatables\Datatables;


class LabelController extends Controller
{
  public function __construct(Labels $label)
  {
    $this->middleware('auth');
    $this->label  = $label;
  }
    
  public function index(Request $request)
  {
    return view('admin.label.labelView');
  }

  public function getlabelslist(Request $request)
  {
    $labels = $this->label->get();
      
    return Datatables::of($labels)
      ->addColumn('action',function($labels){
        return "
        <a href='".url('admin/settings/label/update')."/".$labels->id."' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>
        "; 
      })
      ->make(true);
  }
   
  public function export()
  {
    return Excel::download(new LabelExport, 'labels.xlsx');
  }

   
  public function import(Request $request)
  {
    if($request->isMethod('post')){
      $validator= Validator::make($request->all(),[
        //'file'=>'required|max:50000|mimes:xlsx,CSV,ods,odt,odp,application/csv,application/excel,application/vnd.ms-excel, application/vnd.msexcel,text/csv',
        'file'=>'required',
      ]);
      if($validator->fails()){
        return back()->withErrors($validator);
        //return response()->json(['status'=>0,'message' => $validator->errors()->first()]);
      }
      $fileName = $request->file('file');
      if ($_FILES["file"]["size"] > 0) {
      if (($handle = fopen ( $fileName, 'r' )) !== FALSE) {
      while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
            $labelData = Labels::where('label_key', $data[0])->get();
            Labels::updateOrCreate([
              'label_key' => $data[0]
            ],[
              'label_key' => $data[0],
              'name_en'   => $data[1],
              'name_ar'   => $data[2],
              'type'      => $data[3]
            ]);
            // if(sizeof($labelData)==0){
            //   $label = new Labels;
            //   $label->label_key = $data[0];
            //   $label->name_en = $data[1];
            //   $label->name_ar = $data[2];
            //   $label->type = $data[3];
            //   //dd($label);
            //   $label->save();
            // }
          }
          fclose ( $handle );
          return back()->with('message', 'Label has been added succesfully !');
      }
    }
      // Excel::import(new LabelImport,request()->file('file'));
      // return back();
    }
  }

  public function update(Request $request, $id)
  {
    if ($request->isMethod('post')) {

      $validator= Validator::make($request->all(),[
        'label_name_en'=>'required',
        'label_name_ar'=>'required',
      ]);

      $label=Labels::find($id);
      $label->name_en = $request->label_name_en;
      $label->name_ar = $request->label_name_ar;
      $label->save();

      return redirect('admin/settings/label');

    }

    $label = Labels::where('id',$id)->first();
    return view('admin.label.labeledit',compact('label'));
  }

  public function create()
  {
    return view('admin.label.create');
  }

  public function store(Request $request)
  {
  
    $validator= Validator::make($request->all(),[
      'label_name_en'=>'required',
      'label_name_ar'=>'required',
    ]);
   
    Labels::create([
      'name_en'    => $request->label_name_en,
      'name_ar'    => $request->label_name_ar,
      'type'       => $request->type,
      'label_key'  => $request->label_key
    ]);
    

    return redirect('admin/settings/label');
  }

   /**
     * Function to check unique username id
     * @param Request
     * @return Boolean
     * @access public
     */

    public function uniqueLabelKey(Request $request)
    { 
        $flag     = false;
        $label    = Labels::where('label_key', $request->value)->first();
       
        if ($label) {
            $flag = 'true';
        }

        echo $flag;
    }

}
