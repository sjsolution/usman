<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Carbon\Carbon;
use App\User;

class UserReport implements FromCollection,WithHeadings
{
    public function __construct($data)
    {
        $this->data        = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $resposneArr = [];

        $result = User::where('is_delete',0)->where('user_type',2)->orderBy('id','desc')->get();

         foreach($result as $item){            
            
            $resposneArr[] = [
                'name'            => $item->full_name_en ?? $item->full_name_ar,
                'email'           => $item->email,
                'phone'           => $item->mobile_number,
                'wallet_balance'  => $item->amount ?? 0,
                'created_at'      => $item->created_at
            ];
        }

        return collect($resposneArr);
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Email',
            'Phone',
            'Wallet Amount',
            'Created At'
        ];
    }
}
