<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Vehicletype;


class CarBrandsImport implements ToCollection,WithHeadingRow
{
   

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        // dd($collection->take(5)->toArray());
        // $vehicle = Vehicletype::updateOrCreate([
        //     'name_en'   => 'Sedan',
        // ],[
        //     'name_en'   => 'Sedan',
        //     'name_ar'   => 'Sedan_2',
        //     'image'     => 'vehicle/1575786955-vehicle-carcare.png',
        //     'is_active' => 1,
        //     'is_delete' => 0
        // ]);

        $vehicles = Vehicletype::get();

        if(!empty($vehicles)){

            foreach($vehicles as $vehicle)
            {
                foreach($collection as $key){

                  
         
                    $vBrand = $vehicle->brands()->updateOrCreate([
                        'name_en'  => $key['vehicle_brand']
                    ],[
                       'name_en'  => $key['vehicle_brand'],
                       'name_ar'  => $key['vehicle_brand'],
        
                    ]);
        
                    $vManufacture = $vBrand->brandmodel()->create([
                        'name_en'  => $key['vehicle_model'],
                        'name_ar'  => $key['vehicle_model']
                    ]);
        
                    $years = explode('-',$key['1st_year_last_year']);
                    
                    if(isset($years[1]) && strcasecmp($years[1],"Present") ){
                        $years[1] = date('Y');
                    }else{
                        $years[1] =  date('Y');
                    }
        
                    $vManufacture->manufacture()->create([
                        'from_year' => $years[0],
                        'to_year'   => $years[1]
                    ]);
        
                }
            }
            
    
        }
      
    }

    public function headingRow(): int
    {
        return 1;
    }
}
