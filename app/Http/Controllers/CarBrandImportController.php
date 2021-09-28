<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CarBrandsImport;
use Maatwebsite\Excel\Facades\Excel;

class CarBrandImportController extends Controller
{
    public function index()
    {
        return view('car_brand_import');
    } 

    public function import()
    {
        Excel::import(new CarBrandsImport,request()->file('file'));
           
        return back();
    }
}
