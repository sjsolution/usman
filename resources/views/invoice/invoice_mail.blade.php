<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

    <div>
            <table cellpadding="0" cellspacing="0" style="width: 100%; margin-top: 2%">
            <tr>
                <td>
                    <img style="float: left; margin-top: -15px; " src="{{ url('images/DashLogo.png') }}" alt="logo">
                    {{-- <img style="float: left; " src="data:image/png;base64,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" alt="logo"> --}}
                </td>
                <td style="font-weight: bold; font-size: 24px">
                    Invoice&nbsp;&nbsp;#{{ $order->order_number }}
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" style="width: 40%; margin-top: 2%">
        <tr>
            <td style="font-weight: bold; padding: 5px;">
                {{ $order->serviceProvider[0]->serviceProvider->full_name_en ?? $order->serviceProvider[0]->serviceProvider->full_name_ar }}:
            </td>
            <td style="padding: 5px;">
                {{  $order->serviceProvider[0]->serviceProvider->address }}
            </td>
        </tr>
            <tr>
                <td style="font-weight: bold; padding: 5px;">
                    Invoice to:
                </td>
                <td style="padding: 5px;">
                   <b>Name:</b> {{ $order->user->full_name_en ?? $order->user->full_name_ar }},
                    <br>Address Type : {{ $order->user->userlocation[0]->address_type=='1' ? 'Office' : ($order->user->userlocation[0]->address_type=='2' ? 'Apartment' : 'Home') }} 

                    <br><b>Area:</b> {{ $order->user->userlocation[0]->area }}, 
                    <b>Block:</b> {{ $order->user->userlocation[0]->block }} 
                    <br><b>Street:</b> {{ $order->user->userlocation[0]->street }} ,
                    <br><b>Building:</b> {{ $order->user->userlocation[0]->building }} 
                    @if(!empty($order->user->userlocation[0]->floor)) , <b>Floor:</b> {{$order->user->userlocation[0]->floor}} 
                    @endif

                    @if(!empty($order->user->userlocation[0]->avenue)) ,
                    <b>Avenue:</b> {{ $order->user->userlocation[0]->avenue }}
                    @endif
                    <br><b>Address:</b> {{ $order->user->userlocation[0]->address }}
                    @if(!empty($order->user->mobile_number))
                    <br>Mobile No. {{ $order->user->country_code.'-'.$order->user->mobile_number }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;padding: 5px;">
                    Booking Date:
                </td>
                <td style="padding: 5px;">
                    {{ $order->created_at }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;padding: 5px;">
                    Invoice Date:
                </td>
                <td style="padding: 5px;">
                    {{ $order->created_at }}
                </td>
            </tr>
        </table>
        <table  cellpadding="0" cellspacing="0" width="100%" style="    margin-top: 2%;">

            <tr style="border: 1px solid #ddd;">
                <td style="font-weight: bold;border: 1px solid #ddd;padding: 5px;">#</td>
                <td style="font-weight: bold;border: 1px solid #ddd;padding: 5px;">Service Name</td>
                <td style="font-weight: bold;border: 1px solid #ddd;padding: 5px;">Service cost</td>
                <td style="font-weight: bold;border: 1px solid #ddd;padding: 5px;">Total Amount</td>
            </tr>

            <tr>
                <td style="border: 1px solid #ddd;padding: 5px;">1</td>
                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->subOrder[0]->service->name_en  }}</td>
                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->subOrder[0]->sub_amount }} KWD</td>
                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->subOrder[0]->sub_amount }} KWD</td>
            </tr>
            @if(isset($order->subOrder[0]->addons[0]->subOrderAddon) && !empty($order->subOrder[0]->addons[0]->subOrderAddon))
                @php $i = 1; @endphp
                <tr>
                    <td style="border: 1px solid #ddd;padding: 5px;">Add-on services</td>
                    <td></td>
                    <td></td>
                    <td style="border: 1px solid #ddd;padding: 5px;"></td>
                </tr>
                @foreach($order->subOrder[0]->addons as $extraAddons)
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{  $i }}</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $extraAddons->subOrderAddon->name_en  }}</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $extraAddons->subOrderAddon->amount }} KWD</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $extraAddons->subOrderAddon->amount  }} KWD</td>
                    </tr>
                    @php $i++; @endphp
                @endforeach

            @endif

            @if(isset($order->extraAddonOrder) && !empty($order->extraAddonOrder->count()))
                @php $i = 1; @endphp
                <tr style="background:lightgray">
                    <td style="border: 1px solid #ddd;padding: 5px;">Extra Add-on services</td>
                    <td style="border: 1px solid #ddd;padding: 5px;"></td>
                    <td style="border: 1px solid #ddd;padding: 5px;"></td>
                    <td style="border: 1px solid #ddd;padding: 5px;"></td>
                </tr>
                @foreach($order->extraAddonOrder as $xtraAddons)
                    <tr class="text-right">
                        <td style="border: 1px solid #ddd;padding: 5px;">{{  $i }}</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $xtraAddons->serviceAddons->name_en  }}</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $xtraAddons->serviceAddons->amount }} KWD</td>
                        <td style="border: 1px solid #ddd;padding: 5px;">{{ $xtraAddons->serviceAddons->amount  }} KWD</td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
            @endif 
        </table>
            <table width="100%" cellpadding="0" cellspacing="0" style="    margin-bottom: 2%;">
            <tr>
                <td style="border: 1px solid #ddd;font-weight: bold;padding: 5px;
        width: 68.7%;">Sub Total amount</td>

                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->sub_amount + $order->extraAddonOrder->sum('amount') }} KWD</td>
            </tr>
            @if($order->is_apply_user_applicable_fee)
            <tr>
                <td style="border: 1px solid #ddd;font-weight: bold;padding: 5px;">User Applicable Fee</td>

                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->user_applicable_fee }}</td>
            </tr>
            @endif
            @if(!empty($order->coupon_amount))
            <tr>
                <td style="border: 1px solid #ddd;font-weight: bold;border: 1px solid #ddd;padding: 5px;">Coupon Discount</td>

                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->coupon_amount }} KWD</td>
            </tr>
            @endif
            <tr>
                <td style="border: 1px solid #ddd;font-weight: bold;border: 1px solid #ddd;padding: 5px;">Total</td>

                <td style="border: 1px solid #ddd;padding: 5px;">{{ $order->net_payable_amount  + $order->extraAddonOrder->sum('amount')}} KWD</td>
            </tr>

        </table>
        <!-- For insurance -->
        @if($order->category->type == 2)
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="font-weight: bold;padding: 15px  5px 15px 5px; font-size: 18px">Insurance Details</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Insurance start date: </td>
                    <td style="padding: 5px;">
                        {{$order->suborder[0]->insurance->insurance_start_date}}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Insurance type: </td>
                    <td style="padding: 5px;">
                        @if($order->suborder[0]->insurance->insurance_type == "1")
                            <span class="float-right text-muted">
                                                      New Policy
                                                  </span>

                        @else
                            <span class="float-right text-muted">
                                                      Old Policy
                                                  </span>
                        @endif
                    </td>
                </tr>
                <!-- <tr>
                    <td style="font-weight: bold;padding: 5px;">Mobile Number: </td>
                    <td style="padding: 5px;">        {{ $order->suborder[0]->insurance->mobile_number }}</td>
                </tr> -->
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Description: </td>
                    <td style="padding: 5px;"> {{ $order->suborder[0]->insurance->description }}</td>
                </tr>
                <!-- <tr>
                    <td style="font-weight: bold;padding: 5px;">Insurance Value: </td>
                    <td style="padding: 5px;"> {{ $order->suborder[0]->insurance->vehicle->vehicle_value }}</td>
                </tr> -->
                <tr>
                    <td style="font-weight: bold; padding: 5px;">Image: </td>
                    <td style="padding: 5px;">
                        @if($order->suborder[0]->insurance->images!='')
                            @foreach(explode(',', $order->suborder[0]->insurance->images) as $image)
                                <img src="{{ config('app.AWS_URL').$image }}" width="100px" height="100px" alt="img">
                            @endforeach
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold;padding: 5px;">Car registration number: </td>
                    <td style="padding: 5px;"> {{$order->suborder[0]->insurance->car_registration_number}}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Current car estimation value: </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->current_car_estimation_value }} KWD
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Chassis number: </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->chassis_number }}

                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Civil id front </td>
                    <td style="padding: 5px;">
                        <img src="{{ config('app.AWS_URL').$order->suborder[0]->insurance->civil_id_front }}" alt="civil_front" width="100px" height="100px">
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; padding: 5px;">Civil id back: </td>
                    <td style="padding: 5px;">
                        <img src="{{ config('app.AWS_URL').$order->suborder[0]->insurance->civil_id_back}}" height="100px" width="100px" alt="civil_back">
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Vehicle Type: </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->vehicle->vehilcesdata->name_en}}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Vehicle Model: </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->vehicle->models->name_en}}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">Vehicle Brand: </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->vehicle->brands->name_en }}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;padding: 5px;">
                        Manufacturing Year: 
                    </td>
                    <td style="padding: 5px;">
                        {{ $order->suborder[0]->insurance->vehicle->year_of_manufacture }}

                    </td>
                </tr>
            </table>
        @endif
    </div>

</body>
</html>



