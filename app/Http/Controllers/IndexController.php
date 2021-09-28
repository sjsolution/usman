<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    // change the language arabic to english and english to arabic
    public function changeLanguage($lang){
        // start to destroy the session
        if($lang == 'ar')
            session()->forget('en');
        else
            session()->forget('ar');
        // end to destroy the session

        // create new session with the selected language
        session()->put($lang, $lang);
        return back();
    }

    // return home page of the application
    public function index(){
        // if there is no session the defualt language is english
        if(empty(session()->has('ar')) && empty(session()->has('en'))){
            session()->put('en', 'en');
        }

        // if session is en then return english template
        if(session()->has('en')){
            return view('frontend.en.index');
        }

        // if session is ar then return arabic template
        if(session()->has('ar')){
            return view('frontend.ar.index');
        }
    }

    // return site design page of the application
    public function siteDesign(){
        // if session is en then return english template
        if(session()->has('en')){
            return view('frontend.en.website');
        }

        // if session is ar then return arabic template
        if(session()->has('ar')){
            return view('frontend.ar.website');
        }
    }

    // return store design page of the application
    public function storeDesign(){
        // if session is en then return english template
        if(session()->has('en')){
            return view('frontend.en.e-commerce');
        }

        // if session is ar then return arabic template
        if(session()->has('ar')){
            return view('frontend.ar.e-commerce');
        }
    }

    // return app design page of the application
    public function appDesign(){
        // if session is en then return english template
        if(session()->has('en')){
            return view('frontend.en.mobile-app');
        }

        // if session is ar then return arabic template
        if(session()->has('ar')){
            return view('frontend.ar.mobile-app');
        }
    }

    // return e-marketing page of the application
    public function eMarketing(){
        // if session is en then return english template
        if(session()->has('en')){
            return view('frontend.en.social-media');
        }

        // if session is ar then return arabic template
        if(session()->has('ar')){
            return view('frontend.ar.social-media');
        }
    }
}
