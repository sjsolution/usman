<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $days = [
          ['id' => 1, 'name_en' => 'Sun',  'name_ar' => 'شمس'],
          ['id' => 2, 'name_en' => 'Mon',  'name_ar' => 'الإثنين'],
          ['id' => 3, 'name_en' => 'Tue',  'name_ar' => 'الثلاثاء'],
          ['id' => 4, 'name_en' => 'Wed',  'name_ar' => 'تزوج'],
          ['id' => 5, 'name_en' => 'Thus', 'name_ar' => 'وهكذا'],
          ['id' => 6, 'name_en' => 'Fri',  'name_ar' => 'الجمعة'],
          ['id' => 7, 'name_en' => 'Sat',  'name_ar' => 'جلسنا'],
      ];
      DB::table('days')->insert($days);
    }
}
