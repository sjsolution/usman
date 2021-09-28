<?php

namespace App\Listeners;

use App\Events\WalletGift;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Models\RewardSetting;

class UserSignUpWalletGift
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WalletGift  $event
     * @return void
     */
    public function handle(WalletGift $event)
    {
        $rewards = RewardSetting::where('status',1)->where('is_active',1)
            ->whereDate('from_date', '<=', Carbon::today()->toDateString())
            ->whereDate('to_date', '>=', Carbon::today()->toDateString());

        if($rewards->exists()){
    
            $reward = $rewards->first();
            $event->user->amount += $reward->reward_amount;
            $event->user->save();
        }
       
    }
}
