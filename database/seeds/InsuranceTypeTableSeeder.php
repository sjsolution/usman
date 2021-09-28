<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InsuranceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('insurancetype')->insert([
        'name_en' => "Issue New policy",
        'name_en' => "Issue New policy",
      ]);
      DB::table('insurancetype')->insert([
        'name_en' => "Issue Old policy",
        'name_en' => "Issue Old policy",
      ]);
    }
}
