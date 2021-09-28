<?php

use Illuminate\Database\Seeder;

class AdminTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       for($i=1;$i<=7;$i++){
        DB::table('admin_time_slot')->insert([
          'id'=>$i
        ]);
       }
         
    }
}
