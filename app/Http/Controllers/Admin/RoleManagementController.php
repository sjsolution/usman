<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Admin;
use App\Mail\ServiceProviderInvitation;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Validator;


class RoleManagementController extends Controller
{
    public function __construct(Admin $admin,Role $role)
    {
       $this->admin = $admin;
       $this->role  = $role;
    }

    public function index()
    {
        return view('admin.role_management.create');
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->role_name
        ]);

        foreach($request->menu as $item){
           
            $role->rolePermission()->create([
                'menu_id'   => $item['id'],
                'is_read'   => !empty($item['read'])  ? 1 : 0,
                'is_write'  => !empty($item['write']) ? 1 : 0
            ]);
        }

        toast('Role created successfully','success')->timerProgressBar();

        return back();

    } 

    public function userList()
    {
        return view('admin.role_management.users');
    }

    public function list(Request $request)
    {
        $users = $this->admin->where('type','2')->orderBy('id','desc')->get();
      
        return Datatables::of($users)
            // ->addColumn('checkbox', function ($users) {
            //     return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
            // })
            ->addColumn('is_active',function($users){
                if($users->is_active == 1)
                    return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$users->id.',0)">Active</button>'; 
                else
                    return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$users->id.',1)">In-active</button>'; 
            })
            ->addColumn('action',function($users){
                return "
                <a href='details/".$users->id."' class='btn btn-gradient-danger btn-sm' title='view'><i class='fa fa-eye'></i></a>
                <a href='edit/".$users->id."' class='btn btn-gradient-info btn-sm' title='Edit'><i class='fa fa-pencil'></i></a>";
            //  return '<i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="View" onclick="couponDetails(event,'.$users->id.')"></i>'; 
            })
            ->make(true);
    }

    public function addUser()
    {
        $roles = $this->role->get();
        return view('admin.role_management.add_user',compact('roles'));
    }

    public function userStore(Request $request)
    {
        $password = $this->generateRandomString();
         
        $coverPath = '';

        if ($request->hasFile('profile_image')) {
         
            $validator = Validator::make($request->all(), [
             'profile_image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
               return redirect()->back()->withErrors($validator)->withInput();
            }

            $rand1 = mt_rand(000,999);
            $file = $request->file('profile_image');
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $name = time().$rand1.'-admin-user.'.$extension;
            $coverPath = 'profile/'.$name;
            Storage::disk('s3')->put($coverPath, file_get_contents($file));
           
        }

        $admin = $this->admin->create([
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile_number' => $request->mobile_no,
            'type'          => '2',
            'password'      => bcrypt($password),
            'profile_pic'   => $coverPath
        ]);

        $admin->type = '2';
        $admin->save();

        $admin->role()->create([
            'role_id'  => $request->role
        ]);

        Mail::to($admin->email)->send(new ServiceProviderInvitation($admin,$password));

        toast('Admin Created successfully','success')->timerProgressBar();

        return redirect('admin/settings/role/management/users');

    }

    public function changeStatus(Request $request)
    {
        $user   = $this->admin->find($request->user_id);
        $user->is_active = $request->status;
        $user->save();
        return response()->json(['status'=>'success','message'=>'User status changed successfully']);
    }

    public function edit(Request $request,$id)
    {
        $admin = $this->admin->find($id);
        $roles = $this->role->get();
        return view('admin.role_management.edit_user',compact('roles','admin'));
        
    }

    public function update(Request $request,$id)
    {
        $admin = $this->admin->find($id);

        $coverPath = '';

        if ($request->hasFile('profile_image')) {
         
            $validator = Validator::make($request->all(), [
             'profile_image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
               return redirect()->back()->withErrors($validator)->withInput();
            }

            $rand1 = mt_rand(000,999);
            $file = $request->file('profile_image');
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $name = time().$rand1.'-admin-user.'.$extension;
            $coverPath = 'profile/'.$name;
            Storage::disk('s3')->put($coverPath, file_get_contents($file));
            $admin->profile_pic   = $coverPath;
           
        }

        $admin->name          = $request->name;
        $admin->mobile_number = $request->mobile_no;
        
        $admin->save();

        $admin->role[0]->role_id = $request->role;
        $admin->role[0]->save();

        toast('Admin profile successfully updated','success')->timerProgressBar();

        return back();
    }

    public function userDetails(Request $request,$id)
    {
        $admin = $this->admin->find($id);
        return view('admin.role_management.user_profile',compact('admin'));
    }

     /**
     * this method is to get areas for corresponding cities in sp creation blade
     */
    public function generateRandomString($length = 6) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function role()
    {
        return view('admin.role_management.list');
    }

    public function roleList(Request $request)
    {
        $users = $this->role->orderBy('id','desc')->get();
      
        return Datatables::of($users)
            // ->addColumn('checkbox', function ($users) {
            //     return '<input type="checkbox" id="'.$users['id'].'" name="someCheckbox" class="userId" />';
            // })
            ->addColumn('status',function($users){
                if($users->status == 1)
                    return '<button type="button" class="btn btn-gradient-primary btn-sm" onclick="changeStatus(event,'.$users->id.',0)">Active</button>'; 
                else
                    return '<button type="button" class="btn btn-gradient-danger btn-sm" onclick="changeStatus(event,'.$users->id.',1)">In-active</button>'; 
            })
            ->addColumn('action',function($users){
                return "
                <a href='edit/".$users->id."' class='btn btn-gradient-info btn-sm' title='Edit'><i class='fa fa-pencil'></i></a>";
            //  return '<i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="View" onclick="couponDetails(event,'.$users->id.')"></i>'; 
            })
            ->make(true);
    }

    public function roleEdit(Request $request,$id)
    {
        $roles = $this->role->with('rolePermission')->where('id',$id)->first();
        return view('admin.role_management.role_edit',compact('roles'));
    }

    public function roleUpdate(Request $request)
    {
        $role = $this->role->where('id',$request->role_id)->first();
        $role->name = $request->role_name;
        $role->save();

        foreach($request->menu as $item){
          
            $role->rolePermission()->updateOrCreate([
                'menu_id'   => $item['id']
            ],[
                'menu_id'   => $item['id'],
                'is_read'   => !empty($item['read'])  ? 1 : 0,
                'is_write'  => !empty($item['write']) ? 1 : 0
            ]);
        }

        toast('Role updated successfully','success')->timerProgressBar();

        return back();


    }

    public function roleStatus(Request $request)
    {
        $role         = $this->role->find($request->role_id);
        $role->status = $request->status;
        $role->save();
        return response()->json(['status'=>'success','message'=>'Role status changed successfully']);
    }
}
