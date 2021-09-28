<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       DB::table('users')->insert([
         'full_name_en' => "Developer",
         'email' => "serviceprovider@yopmail.com",
         'user_type'=>"1",
         'is_verified_email'=>"1",
         'is_verified_mobile'=>"1",
         'is_active' =>"1",
         'country_code' =>"+965",
         'mobile_number' =>"123456789",
         'password' => bcrypt('O2onelabs@123'),
     ]);

     DB::table('users')->insert([
        'full_name_en' => "Developer",
        'email' => "user@yopmail.com",
        'user_type'=>"2",
        'is_verified_email'=>"1",
        'is_verified_mobile'=>"1",
        'is_active' =>"1",
        'country_code' =>"+965",
        'mobile_number' =>"0987654",
        'password' => bcrypt('O2onelabs@123'),
     ]);

     DB::table('users')->insert([
        'full_name_en' => "Developer",
        'email' => "customer@yopmail.com",
        'user_type'=>"3",
        'is_verified_email'=>"1",
        'is_verified_mobile'=>"1",
        'is_active' =>"1",
        'country_code' =>"+965",
        'mobile_number' =>"435678",
        'password' => bcrypt('O2onelabs@123'),
     ]);

    DB::table('users')->insert([
        'full_name_en' => "Developer",
        'email' => "technician@yopmail.com",
        'user_type'=>"4",
        'is_verified_email'=>"1",
        'is_verified_mobile'=>"1",
        'is_active' =>"1",
        'country_code' =>"+965",
        'mobile_number' =>"90124376",
        'password' => bcrypt('O2onelabs@123'),
     ]);
    }
}
