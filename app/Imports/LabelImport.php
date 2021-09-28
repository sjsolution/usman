<?php

namespace App\Imports;

use App\Models\Labels;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Labels([
          'label_key' => $row['label_key'],
          'name_en'  => $row['name_en'],
          'name_ar' => $row['name_ar'],
        ]);
    }
}
