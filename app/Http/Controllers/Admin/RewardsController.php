<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RewardSetting;

class RewardsController extends Controller
{
    public function __construct(RewardSetting $rewardSetting)
    {
        $this->rewardSetting = $rewardSetting;
    }

    public function index()
    {
        $rewards = $this->rewardSetting->where('status',1)->where('is_active',1)->first();
        
        return view('admin.reward_settings',compact('rewards'));
    }

    public function store(Request $request)
    {
        $date = explode("to",$request->from_date);

        if(isset($date[0]) && isset($date[1])){

            $this->rewardSetting->where('is_active',1)->update(['is_active' => 0]);

            $rewards = $this->rewardSetting->create([
                'from_date'        => $date[0],
                'to_date'          => $date[1],
                'status'           => $request->status,
                'reward_amount'    => $request->reward_amount
            ]);
    
            toast('User signup reward setting successfully saved','success')->timerProgressBar();
    
            return view('admin.reward_settings',compact('rewards'));
            
        }else{

            toast('Please select valid date range','error')->timerProgressBar();

            return redirect('admin/settings/rewards');
        }
              

    }
} 
