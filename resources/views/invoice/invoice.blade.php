@extends('appsp')
@section('content')
<style>


   .ikn {
   cursor   : pointer;
   font-size: x-large;
   }



   .modal-content
   {
       background-color:#fff;
   }
   /* .modal .modal-dialog .modal-content .modal-body
   {
       padding-top:0px!important;
   } */
   .modal-lg, .modal-xl
   {
       width:70%!important;
   }
   .table th, .jsgrid .jsgrid-table th, .table td, .jsgrid .jsgrid-table td
{
    border-top:none!important;
    /* padding:5px 0px; */
}
#order_details_modal_form td
{
        padding:6px 0px!important;
}
.body_padding strong
{
    padding-top:35px!important;
}

</style>

 <div class="content-wrapper">
    <div class="page-header">
      {{-- <h3 class="page-title"> Invoice </h3> --}}
      <nav aria-label="breadcrumb"></nav>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="card px-2">
          <div class="card-body">
            <div class="container-fluid">
            <h3 class="text-right my-5"><img style="float: left; margin-top: -15px; " src="{{ url('images/DashLogo.png') }}" alt="logo"> Invoice&nbsp;&nbsp;#{{ $order->order_number}}</h3>
              <hr>
            </div>
            <div class="container-fluid d-flex justify-content-between">
              <div class="col-lg-3 pl-0">
                <p class="mt-5 mb-2"><b>{{ $order->serviceProvider[0]->serviceProvider->full_name_en ?? $order->serviceProvider[0]->serviceProvider->full_name_ar }}</b></p>
                <p>{{  $order->serviceProvider[0]->serviceProvider->address }}</p>
              </div>
              <div class="col-lg-3 pr-0">
                <p class="mt-5 mb-2 text-right"><b>Invoice to</b></p>
                <p class="text-right">{{ $order->user->full_name_en ?? $order->user->full_name_ar }},
                  <br>Address : {{ $order->user->userlocation[0]->block }} {{ $order->user->userlocation[0]->street }} ,
                  <br>{{ $order->user->userlocation[0]->address }},
                  <br>Ph No. {{ $order->user->country_code.'-'.$order->user->mobile_number }}
                </p>
              </div>
            </div>
            <div class="container-fluid d-flex justify-content-between">
              <div class="col-lg-3 pl-0">
              <p class="mb-0 mt-5">Invoice Date : {{ $order->created_at }}</p>
              </div>
            </div>
            <div class="container-fluid mt-5 d-flex justify-content-center w-100">
              <div class="table-responsive w-100">
                <table class="table">
                  <thead>
                    <tr class="bg-dark text-white">
                      <th>#</th>
                      <th class="text-left">Service Name</th>
                      <th class="text-right">Service cost</th>
                      <th class="text-right">Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-right">
                      <td class="text-left">1</td>
                      <td class="text-left">{{ $order->subOrder[0]->service->name_en  }}</td>
                      <td>{{ $order->subOrder[0]->sub_amount }} KWD</td>
                      <td>{{ $order->subOrder[0]->sub_amount }} KWD</td>
                    </tr>
                    @if(isset($order->subOrder[0]->addons[0]->subOrderAddon) && !empty($order->subOrder[0]->addons[0]->subOrderAddon))
                    @php $i = 1; @endphp
                    <tr style="background:lightgray">
                      <td>Add-on services</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    @foreach($order->subOrder[0]->addons as $extraAddons)
                    <tr class="text-right">
                      <td class="text-left">{{  $i }}</td>
                      <td class="text-left">{{ $extraAddons->subOrderAddon->name_en  }}</td>
                      <td>{{ $extraAddons->subOrderAddon->amount }} KWD</td>
                      <td>{{ $extraAddons->subOrderAddon->amount  }} KWD</td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach

                    @endif


                    @if(isset($order->extraAddonOrder) && !empty($order->extraAddonOrder->count()))
                    @php $i = 1; @endphp
                    <tr style="background:lightgray">
                      <td>Extra Add-on services</td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    @foreach($order->extraAddonOrder as $xtraAddons)
                    <tr class="text-right">
                      <td class="text-left">{{  $i }}</td>
                      <td class="text-left">{{ $xtraAddons->serviceAddons->name_en  }}</td>
                      <td>{{ $xtraAddons->serviceAddons->amount }} KWD</td>
                      <td>{{ $xtraAddons->serviceAddons->amount  }} KWD</td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
            <div class="container-fluid mt-5 w-100">
                <p class="text-right mb-2">Sub Total amount: {{ $order->sub_amount + $order->extraAddonOrder->sum('amount')  }} KWD</p>
                @if($order->is_apply_user_applicable_fee)
                  <p class="text-right">User Applicable Fee : {{ $order->user_applicable_fee }}</p>
                @endif
                @if(!empty($order->coupon_amount))
                  <p class="text-right">Coupon Discount  : - {{ $order->coupon_amount }} KWD</p>
                @endif
            <h4 class="text-right mb-5">Total : {{ $order->net_payable_amount + $order->extraAddonOrder->sum('amount') }} KWD</h4>
              <hr>
            </div>

             <!-- For insurance -->
              @if($order->category->type == 2)
                <div class="container-fluid mt-5 w-100">
                    <strong>Insurance Details</strong>
                    <hr>

                      <div class="row">
                          <div class="col-md-6">
                              <div class="py-4">
                                  <p class="clearfix">
                                      <span class="float-left">Insurance start date: </span>
                                      <span class="float-right text-muted">
                                        {{$order->suborder[0]->insurance->insurance_start_date}}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Insurance type: </span>
                                          @if($order->suborder[0]->insurance->insurance_type == "1")
                                              <span class="float-right text-muted">
                                                  New Policy
                                              </span>

                                          @else
                                              <span class="float-right text-muted">
                                                  Old Policy
                                              </span>
                                           @endif
                                      </span>
                                  </p>
                                  <!-- <p class="clearfix">
                                      <span class="float-left">Mobile Number</span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->mobile_number }}
                                      </span>
                                  </p> -->
                                  <p class="clearfix">
                                      <span class="float-left">Description: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->description }}
                                      </span>
                                  </p>
                                  <!-- <p class="clearfix">
                                      <span class="float-left">Insurance Value: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->vehicle->vehicle_value }}
                                      </span>
                                  </p> -->
                                  <p class="clearfix">
                                      <span class="float-left">Image: </span>
                                      <span class="float-right text-muted">
                                          @if($order->suborder[0]->insurance->images!='')
                                            @foreach(explode(',', $order->suborder[0]->insurance->images) as $image)
                                              <img src="{{ config('app.AWS_URL').$image }}" width="100px" height="100px" alt="img">
                                            @endforeach
                                          @endif
                                      </span>
                                  </p>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="py-4">
                                  <p class="clearfix">
                                      <span class="float-left">Car registration number: </span>
                                      <span class="float-right text-muted">
                                          {{$order->suborder[0]->insurance->car_registration_number}}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Current car estimation value: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->current_car_estimation_value }}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Chassis number: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->chassis_number }}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Civil id front: </span>
                                      <span class="float-right text-muted">
                                          <img src="{{ config('app.AWS_URL').$order->suborder[0]->insurance->civil_id_front }}" alt="civil_front" width="100px" height="100px">
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Civil id back: </span>
                                      <span class="float-right text-muted">
                                          <img src="{{ config('app.AWS_URL').$order->suborder[0]->insurance->civil_id_back}}" height="100px" width="100px" alt="civil_back">
                                      </span>
                                  </p>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="py-4">

                                  <p class="clearfix">
                                      <span class="float-left">Vehicle Type: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->vehicle->vehilcesdata->name_en}}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Vehicle Model: </span>
                                         {{ $order->suborder[0]->insurance->vehicle->models->name_en}}
                                      </span>
                                  </p>
                                  <p class="clearfix">
                                      <span class="float-left">Vehicle Brand: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->vehicle->brands->name_en }}
                                      </span>
                                  </p>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="py-4">
                                  <p class="clearfix">
                                      <span class="float-left">Manufacturing Year: </span>
                                      <span class="float-right text-muted">
                                          {{ $order->suborder[0]->insurance->vehicle->year_of_manufacture }}
                                      </span>
                                  </p>
                              </div>
                          </div>
                      </div>

                </div>
              @endif
            <div class="container-fluid w-100">
            <a href="{{ url('booking/invoice/download').'/'.$order->id }}" class="btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-printer mr-1"></i>Download</a>
              {{-- <a href="#" class="btn btn-success float-right mt-4"><i class="mdi mdi-telegram mr-1"></i>Send Invoice</a> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection
@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
@endsection
