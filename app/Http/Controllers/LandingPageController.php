<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BeVendor;
use Validator;
use App\Notifications\ContactUsRequest;
use App\User;
use Api;

class LandingPageController extends Controller
{
    public function homepage()
    {
        return view('homepage');
    }

    public function homepage_ar()
    {
        return view('homepage_ar');
    }


    public function terms()
    {
        return view('terms_conditions');
    }


    public function beVendor(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'         => 'email|required',
            'name'          => 'required',
            'phone_number'  => 'required'
        ]);

        if($validator->fails()){

            return response()->json(Api::apiValidationResponse($validator),422); 
        }

        BeVendor::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number
        ]); 

        $details = [
            'greeting'   => 'Hello',
            'body1'      => '',
            'body2'      => 'Name         : '.$request->name,
            'body3'      => 'Email        : '.$request->email,
            'body4'      => 'Phone Number : '.$request->phone_number,
            'body5'      => 'Message      : '.$request->description,
            'thanks'     => 'Thank You',
            'subject'    => 'Be a vendor'
        ]; 

        $users = new User;
        
        // if(config('app.env') == 'live')
        //     $users->email = 'hello@letspoundit.com';
        // else
            $users->email = 'sanu@o2onelabs.com';

        $users->notify(new ContactUsRequest($details));


        return response()->json(['msg' => 'Vendor details saved successfully']);
    }
    
}
