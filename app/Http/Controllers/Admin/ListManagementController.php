<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListType;
use App\User;
use App\SPListRanking;
use Yajra\Datatables\Datatables;


class ListManagementController extends Controller
{

    public function __construct(ListType $listType,User $user,SPListRanking $spListRanking)
    {
        $this->listType      = $listType;
        $this->user          = $user;
        $this->spListRanking = $spListRanking;
    }

    public function index()
    { 
        return view('admin.listing.list');
    }

    public function create()
    {
        return view('admin.listing.create');
    }

    public function store(Request $request)
    {
        $this->listType->create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar
        ]);

        return back();
    }

    public function serviceProviders(Request $request)
    {
        $users = $this->user->where('user_type',1)->with('spRatings')->orderBy('id','desc')->get();
      
        return Datatables::of($users)
            ->addColumn('status',function($users){
                if($users->spRatings->count())
                   return '<strong style="color:green;">Ranked</strong>'; 
                else
                   return '<strong style="color:red;">Not Ranked</strong>';
            })
            ->addColumn('action',function($users){
                return '
                   <i class="fa fa-id-card ikn"  data-toggle="tooltip" data-placement="top" title="view" onclick="showListType(event,'.$users->id.')"></i>'; 
            })
            ->make(true);  
    }

    public function spListType(Request $request)
    { 
        $techicians['list']     =  $this->user->with(['category' => function($q){
            $q->join('categories',function($join){
                $join->on('categories.id','=','service_provider_with_category.category_id')
                    ->where('type','<>','2');
            });
        },'spListRanking'])
        ->where('id',$request->service_provider_id)
        ->select('full_name_en','id')
        ->get();
        
        $techicians['listType'] =  $this->listType->where('status',1)->get();
       
        return $techicians; 
    }

    public function spListTypeAssignment(Request $request)
    {
        $reqResponse = $request->all();

        $categoryId = $reqResponse['category_id'];
        $userId     =  $reqResponse['user_id'];


        foreach($reqResponse['list_type'] as $key => $value){

            $existSp = $this->spListRanking->where([
                'list_type_id' => $key,
                'category_id'  => $categoryId,
                'rank'         => $value
            ])->first();


            $newSp = $this->spListRanking->where([
                'list_type_id' => $key,
                'category_id'  => $categoryId,
                'user_id'      => $userId
            ])->first();

            if(!empty($existSp)){

                if(!empty($newSp)){
                    $existSp->rank = $newSp->rank;
                    $existSp->save();
                }else{
                    $existSp->rank = $value + 1;
                    $existSp->save();
                }
                
            }

            
            $this->spListRanking->updateOrCreate([
                'user_id'      => $userId,
                'list_type_id' => $key,
                'category_id'  => $categoryId
             ],[
                 'user_id'      => $userId,
                 'list_type_id' => $key,
                 'category_id'  => $categoryId,
                 'rank'         => !empty($value) ? $value : 0
             ]);
        }    

    
        return response()->json(['message' => 'Ranking update successfully']);

    }


}
