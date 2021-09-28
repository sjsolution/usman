<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class AdminWalletTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('adminwallet')->insert([
          'admin_id' => "1",
          'amount' => "20",
          'credit_amount'=>"1",
      ]);
      DB::table('adminwallet')->insert([
          'admin_id' => "1",
          'amount' => "30",
          'credit_amount'=>"2",
      ]);
      DB::table('adminwallet')->insert([
          'admin_id' => "1",
          'amount' => "40",
          'credit_amount'=>"2",
      ]);

    }
}
