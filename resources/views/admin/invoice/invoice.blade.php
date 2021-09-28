@extends('app')
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
                <p class="mt-5 mb-2"><b>{{ $order->serviceProvider[0]->serviceProvider->full_name_en }}</b></p>
                <p>{{  $order->serviceProvider[0]->serviceProvider->address }}</p>
              </div>
              <div class="col-lg-3 pr-0">
                <p class="mt-5 mb-2 text-right"><b>Invoice to </b></p>
                <p class="text-right">{{ $order->user->full_name_en }},<br>{{ $order->user->userlocation[0]->block }} {{ $order->user->userlocation[0]->street }}  ,<br>{{ $order->user->userlocation[0]->address }}.</p>
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
                      @if($order->category_type == 1)
                      <td>{{ $order->sub_amount }} KWD</td>
                      <td>{{ $order->sub_amount }} KWD</td>
                      @else
                      <td>{{ $order->subOrder[0]->service->amount }} KWD</td>
                      <td>{{ $order->subOrder[0]->service->amount }} KWD</td>
                      @endif
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
                <p class="text-right mb-2">Sub Total amount: {{ $order->sub_amount + $order->extraAddonOrder->sum('amount') }} KWD</p>
                @if($order->is_apply_user_applicable_fee)
                  <p class="text-right">User Applicable Fee : {{ $order->user_applicable_fee }}</p>
                @endif
                @if(!empty($order->coupon_amount))
                  <p class="text-right">Coupon Discount  : - {{ $order->coupon_amount }} KWD</p>
                @endif
            <h4 class="text-right mb-5">Total : {{ $order->net_payable_amount + $order->extraAddonOrder->sum('amount')}} KWD</h4>
              <hr>
            </div>
            <div class="container-fluid w-100">
            <a href="{{ url('admin/booking/invoice/download').'/'.$order->id }}" class="btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-printer mr-1"></i>Download</a>
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
