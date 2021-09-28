<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateway_settings')->insert([
            'name_en'    => "Hisabe",
            'name_ar'    => "هيسابي",
            'is_default' => 1,
          ]);
          DB::table('payment_gateway_settings')->insert([
            'name_en' => "Fatorah",
            'name_ar' => "فتورة",
            'is_default' => 0,
          ]);
    }
}
