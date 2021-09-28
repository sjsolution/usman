<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       DB::table('admins')->insert([
         'name' => "Admin",
         'email' => "admin@maak.live",
         'password' => bcrypt('O2onelabs@123'),
     ]);
    }
}
