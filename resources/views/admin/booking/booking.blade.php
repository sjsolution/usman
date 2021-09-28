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
   .modal .modal-dialog .modal-content .modal-body
   {
       padding-top:0px!important;
   }
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
        padding:6px 0px;
}
   .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
       position: fixed;
       width: 76%;
       z-index: 99;
       height: 66px;
       padding-top: 53px;
       padding-bottom: 43px;
       background: #fff;
       margin-bottom: 58px !important;
   }
   .page-title {
       background: white;
       height: 50px;
       width: 70%;
       z-index: 999;
       position: fixed;
       padding-bottom: 10px;
       padding-top: 20px;
   }
   .card .card-body {
       padding: 0px 20px!important;
   }
   table#users-table {
       margin-top: 100px !important;
   }
   i.fa.fa-eye.ikn, i.fa.fa-money.ikn, i.fa.fa-download.ikn {
       background: linear-gradient(to right, #da8cff, #9a55ff);
       border: 0;
       -webkit-transition: opacity 0.3s ease;
       transition: opacity 0.3s ease;
       padding: 3px 8px 4px 8px;
       color: #fff;
   }
    i.fa.fa-download.ikn {
       margin-left: -9%;
    }
</style>
<div class="content-wrapper">
   <div class="page-header">
   </div>
   <div class="row">
      <!-- <div class="col-lg-12 grid-margin stretch-card"> -->
      <div class="col-lg-12 grid-margin stretch-card pd-0">
         <div class="card">
            <div class="card-body table-responsive">
               <h3 class="page-title">
                  Booking List
               </h3>
               {{--
               <h4 class="card-title">Users</h4>
               --}}

               <table class="table table-hover "  id="users-table">
                  <thead>
                     <tr>
                        <th>Order No.</th>
                        <th>Username</th>
                        <th>Category Name</th>
                        {{--
                        <th>Category Type</th>
                        --}}
                        <th>Service</th>
                        <th>Service Provider</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>

                        <th>Amount</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Created Date</th>
                        <th>Booking Status</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody id="category-list">
                     <tr>
                        <td colspan="6" style="text-align:center;">
                           <i class="fa fa-refresh fa-spin fa fa-fw"></i>
                           <span class="sr-only">Loading...</span>
                        </td>
                     <tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row" id="technician_assign_model"></div>
<div class="row" id="order_details_model"></div>
<div id="transaction_details_model"></div>

@endsection
@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".page-title").css('margin-top', '-4px');
        } else {
            $(".page-title").css('margin-top', '0px');
        }
    });
    
   function assignServiceProvider(event,bookingId){

       var technicianListRequest = fetchRequest('/admin/technician/assign/list');

       var formData = new FormData();

       formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

       formData.append("booking_id", bookingId);

       technicianListRequest.setBody(formData);

       technicianListRequest.post().then(function (response) {

           if (response.status === 200) {
               response.json().then((errors) => {

                   document.getElementById('technician_assign_model').innerHTML = tmpl('technician-assign-list-tmpl',errors);
                   $('#technician_modal_form').modal('show');
               });

           }else if(response.status === 422){
               response.json().then((errors) => {
                   console.log('Error');
               });
           }
       });

   }

   function technicianAssigned(event)
   {
       event.preventDefault();

       $('#technician_assignment').parsley().validate();

       if ($('#technician_assignment').parsley().isValid()){

           var form = document.getElementById('technician_assignment');

           var questionLinkUpdateRequest = fetchRequest('/admin/booking/technician/update');

           var formData = new FormData(form);

           formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

           questionLinkUpdateRequest.setBody(formData);

           questionLinkUpdateRequest.post().then(function (response) {

               if (response.status === 200) {
                   response.json().then((errors) => {
                       console.log(errors);
                       $('#technician_modal_form').modal('toggle');
                   });

               }else if(response.status === 422){
                   response.json().then((errors) => {
                       console.log('Error');
                   });
               }
           });

            return true;

       }else{
           return false;
       }
   }


   function orderDetails(event,orderId){

       var technicianListRequest = fetchRequest('/admin/booking/order/details');

       var formData = new FormData();

       formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

       formData.append("order_id", orderId);

       technicianListRequest.setBody(formData);

       technicianListRequest.post().then(function (response) {

           if (response.status === 200) {
               response.json().then((errors) => {

                   document.getElementById('order_details_model').innerHTML = tmpl('order-details-list-tmpl',errors);
                   $('#order_details_modal_form').modal('show');

               });

           }else if(response.status === 422){
               response.json().then((errors) => {
                   console.log('Error');
               });
           }
       });

   }


   function orderStatusType(id){
       var text = '';

      switch(id){
           case '1' :
              text = 'Accepted';
              return text;
              break;
           case '2' :
              text = 'On the way';
              return text;
              break;
           case '3' :
              text = 'In Progress';
              return text;
              break;
           case '4' :
              text = 'Completed';
              return text;
              break;

            case '5' :
                text = 'Rejected';
                return text;
                break;

            case '6' :
                text = 'Cancelled';
                return text;
                break;

           default :
              text = 'Pending';
              return text;

       return text;

      }
   }

    function transactionDetails(event,orderId)
    {

        var transactionDetailRequest = fetchRequest('/admin/booking/transaction/details');

        var formData = new FormData();

        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        formData.append("transaction_id", orderId);

        transactionDetailRequest.setBody(formData);

        transactionDetailRequest.post().then(function (response) {

            if (response.status === 200) {
                response.json().then((errors) => {

                    document.getElementById('transaction_details_model').innerHTML = tmpl('transaction-details-list-tmpl',errors);
                    $('#transaction_details_modal_form').modal('show');

                });

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });

    }




   $(document).ready(function() {

      
         table =  $('#users-table').DataTable({
              processing: true,
              serverSide: true,
               ajax: '{!! route('booking.list') !!}',
           "columns": [
               {data: 'order_number', name: 'order_number'},
               {data: 'username', name: 'username'},
               {data: 'category_name', name: 'category_name'},
               // {data: 'catgeory_type', name: 'catgeory_type'},
               {data: 'service', name: 'service'},
               {data: 'sp_name', name: 'sp_name'},
               {data: 'booking_date', name: 'booking_date'},
               {data: 'booking_time', name: 'booking_time'},
               {data: 'total_amount', name: 'total_amount'},
               {data: 'payment_type', name: 'payment_type'},
               {data: 'payment_status', name: 'payment_status'},
               {data: 'created_at', name: 'created_at'},
               {data: 'status', name: 'status'},
               {data: 'action', name: 'action'}
             ],
             
              "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
              "iDisplayLength": 25,
             order:[],
             // columnDefs: [ {
             //    'targets': [0], 
             //    'orderable': false, 
             // }],
              initComplete: function () {
                  (function($) {
                  'use strict';

                  $(function() {
                     
                      $('[data-toggle="tooltip"]').tooltip();

                  });
                  })(jQuery);
              }
          });
   });


</script>
<script type="text/x-tmpl" id="technician-assign-list-tmpl">
   {% if(o.list.length) { %}
   <div class="modal fade" id="technician_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
           <form method="post" id="technician_assignment" onsubmit="return technicianAssigned(event)">
           <div class="modal-header">
               <h5 class="modal-title" id="ModalLabel">Technician Assignment</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body pd-0">

               <div class="form-group">
                   <label for="recipient-name" class="col-form-label">Booking Id</label>
                   <input type="text" name="order_id" value="{% if(o.order_id!=='null') { %} {%=o.order_id%} {% } %}" class="form-control" readonly>
               </div>

               <h2 style="left: 198px; position: relative;"><i class="mdi mdi-debug-step-into"></i></h2>

               <div class="form-group">
                   <label for="message-text" class="col-form-label">Technician List (Select one)</label>
                   <select class="form-control" id="select-id"  data-parsley-required="true" data-parsley-required-message="You must select at least one option." name="technician_id">
                       <option value="">--select one--</option>
                       {% for(var i in o.list){
                           var item=o.list[i];
                       %}
                           <option value="{%=item.id%}">{%=item.full_name_en%}</option>
                       {% } %}
                   </select>
               </div>

           </div>
           <div class="modal-footer">
               <input type="submit" class="btn btn-success" value="Assign">
               <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
           </div>
           </div>
           </form>
       </div>
   </div>

   {% } else{ %}

   <div class="modal fade" id="technician_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">

           <div class="modal-header">
               <h5 class="modal-title" id="ModalLabel">Technician Assignment</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body body_padding">

               <strong>No Technician available</strong>

           </div>
           <div class="modal-footer">

               <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
           </div>
           </div>

       </div>
   </div>
   {% } %}

</script>

<script type="text/x-tmpl" id="transaction-details-list-tmpl">
    {% if(o.list.length) { %}
    <div class="modal fade" id="transaction_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Transaction Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {% for(var i in o.list){
                        var item=o.list[i];
                        var cStatus = '';

                        if(item.status == 0)
                            cStatus = 'Pending'
                        else if(item.status == 1)
                            cStatus = 'Success';
                        else if(item.status == 2)
                            cStatus = 'Failure'
                        else if(item.status == 3)
                            cStatus = 'Cancelled'

                    %}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-4">
                                    <p class="clearfix">
                                        <span class="float-left"> Transaction-ID </span>
                                        <span class="float-right text-muted"> {%=item.id%} </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Username </span>
                                        <span class="float-right text-muted"> {%=item.username%} </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Payment Token </span>
                                        <span class="float-right text-muted">
                                        {%=item.payment_token%}
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Original Amount </span>
                                        <span class="float-right text-muted">
                                            {%=item.service_amount%}
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Actual Maak's Share </span>
                                        <span class="float-right text-muted">
                                            {%=item.commission%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Actual Service provider's Share </span>
                                        <span class="float-right text-muted">
                                            {%=item.actual_sp_share%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Cash payable </span>
                                        <span class="float-right text-muted">
                                            {%=item.cash_payable%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> User applicable fee </span>
                                        <span class="float-right text-muted">
                                            {%=item.user_applicable_fee%}
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> KNET Fees </span>
                                        <span class="float-right text-muted">
                                            {%=item.knet_fees%} KWD
                                        </span>
                                    </p>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="py-4">
                                    <p class="clearfix">
                                        <span class="float-left"> Service Provider </span>
                                        <span class="float-right text-muted"> {%=item.service_provider_name%} </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left"> Payment Status </span>
                                        <span class="float-right text-muted"> {%=cStatus%} </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left"> Payment ID </span>
                                        <span class="float-right text-muted">
                                            {%=item.payment_id%}
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Net Amount </span>
                                        <span class="float-right text-muted"> {%=item.net_payable%} KWD</span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Net Maak's share </span>
                                        <span class="float-right text-muted">
                                            {%=item.net_commission%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Service provider's share </span>
                                        <span class="float-right text-muted">
                                            {%=item.net_sp_share%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Paid to service provider</span>
                                        <span class="float-right text-muted">
                                            {%=item.paid_amount%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Wallet Amount </span>
                                        <span class="float-right text-muted"> {%=item.order.wallet_amount%} KWD</span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Others Fees </span>
                                        <span class="float-right text-muted"> {%=item.others_fees%} KWD</span>
                                    </p>

                                </div>
                            </div>
                        </div>

                     {% } %}
                </div>

            </div>

        </div>
    </div>

    {% } else{ %}

    <div class="modal fade" id="technician_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Technician Assignment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <strong>No Technician available</strong>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>
    </div>
    {% } %}

</script>
<script type="text/x-tmpl" id="order-details-list-tmpl">
   {% if(o.list.length) { %}
   <div class="modal fade" id="order_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">

               <div class="modal-header">
                   <h5 class="modal-title" id="ModalLabel">Booking Details</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
               </div>

               <div class="modal-body">
                   {% for(var i in o.list){
                       var item=o.list[i];
                       var pType   = '';
                       var pStatus = '';
                       var cStatus = '';
                       var userAddress = item.user_address;
                       var totalAmount=0;

                        if(item.payment_type == 1)
                           pType = 'Wallet'
                        else if(item.payment_type == 2)
                            pType = 'Knet';
                        else if(item.payment_type == 3)
                           pType = 'Knet & Wallet';
                        else if(item.payment_type == 4)
                           pType = 'Credit Card';
                        else if(item.payment_type == 5)
                           pType = 'Credit Card & Wallet';

                       if(item.payment_status == 0)
                           pStatus = 'Pending'
                       else if(item.payment_status == 1)
                           pStatus = 'Processing';
                       else if(item.payment_status == 2)
                           pStatus = 'Success'
                       else if(item.payment_status == 3)
                           pStatus = 'Failed'

                       if(item.status == 0)
                           cStatus = 'Pending'
                       else if(item.status == 1)
                           cStatus = 'Start';
                       else if(item.status == 2)
                           cStatus = 'Completed'
                       else if(item.status == 3)
                           cStatus = 'Failed'
                       else if(item.status == 4)
                           cStatus = 'Cancelled'
                       else if(item.status == 5)
                           cStatus = ' On the way'
                       else if(item.status == 6)
                           cStatus = 'Accepted'
                       else if(item.status == 7)
                           cStatus = 'Rejected'

                   %}
                       <div class="row">
                           <div class="col-md-6">
                               <div class="py-4">
                                   <p class="clearfix">
                                   <span class="float-left"> Booking-ID </span>
                                   <span class="float-right text-muted"> {%=item.order_number%} </span>
                                   </p>
                                   <p class="clearfix">
                                   <span class="float-left"> Username </span>
                                   <span class="float-right text-muted"> {%=item.username%} </span>
                                   <p class="clearfix">
                                   <span class="float-left"> Phone No. </span>
                                   <span class="float-right text-muted"> {%=item.mobile%} </span>
                                   </p>
                                   <p class="clearfix">
                                   <span class="float-left"> Category </span>
                                   <span class="float-right text-muted"> {%=item.sub_order[0].service.category.name_en%} </span>
                                   </p>
                                   <p class="clearfix">
                                   <span class="float-left"> Service Provider </span>
                                   <span class="float-right text-muted">
                                       {%=item.service_provider_name%}
                                   </span>
                                   </p>
                                   <p class="clearfix">
                                   <span class="float-left"> Technician  </span>
                                   <span class="float-right text-muted">
                                           {%=item.technician%}
                                   </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Booking Date  </span>
                                       <span class="float-right text-muted">
                                               {%
                                                   var bookingDate = '';
                                                   if(item.service_provider.length)
                                                       bookingDate = item.service_provider[0].booking_date
                                                   else
                                                       bookingDate = '--';

                                               %}
                                               {%=bookingDate%}
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Booking Time  </span>
                                       <span class="float-right text-muted">
                                               {%
                                                   if(item.service_provider.length)
                                                       bookingTime = item.service_provider[0].booking_time
                                                   else
                                                       bookingTime = '--';
                                               %}
                                               {%=bookingTime%}

                                       </span>
                                   </p>

                                   <p class="clearfix">
                                       <span class="float-left"> Booking Time Duration  </span>
                                       <span class="float-right text-muted">
                                           {%=item.spend_time%} Minutes
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Paid to service provider</span>
                                       <span class="float-right text-muted">
                                           {%=item.transaction.paid_amount%} KWD
                                       </span>
                                   </p>
                               </div>
                           </div>
                           <div class="col-md-6">
                               <div class="py-4">
                                   <p class="clearfix">
                                       <span class="float-left"> Service Name </span>
                                       <span class="float-right text-muted"> {%=item.service%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Original Amount </span>
                                       <span class="float-right text-muted"> {%=item.total_amount%} KWD</span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Net Amount </span>
                                       <span class="float-right text-muted"> {%=(item.final_amount+item.wallet_amount)%} KWD</span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Wallet Amount </span>
                                       <span class="float-right text-muted"> {%=item.wallet_amount%} KWD</span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Code </span>
                                       <span class="float-right text-muted">
                                           {% if(item.coupon !=null)
                                                  code = item.coupon.code;
                                               else
                                                  code = '--';
                                           %}
                                           {%=code%}
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Amount </span>
                                       <span class="float-right text-muted"> {%=item.coupon_amount%} KWD</span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Cuurent Status </span>
                                       <span class="float-right text-muted"> {%=cStatus%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Address </span>
                                       <span class="float-right text-muted" style="width: 100%;">
                                          <b>Area:</b> {%=userAddress.area%}
                                          <b>Block:</b> {%=userAddress.block%}<br>
                                         <b>Street:</b> {%=userAddress.street%}<br>

                                         <b>avenue:</b> {%=userAddress.avenue%} 
                                         <br>
                                        {% if(userAddress.building!='') { %}  <br>

                                         <b>Building:</b> {%=userAddress.building%} <br>
                                         {%}%}

                                          {% if(userAddress.floor!='') { %}
                                          <b>Floor:</b>  {%=userAddress.floor%}
                                           {%}%} 
                                        {% if(userAddress.house!='') { %}
                                        <b>House:</b>{%=userAddress.house%} 
                                        {%}%} 
                                         {% if(userAddress.office!='') { %}
                                        <b>Office:</b>{%=userAddress.office%} 
                                        {%}%} 
                                         {% if(userAddress.address!='') { %}<br>
                                        <b>Address:{%=userAddress.appartment_number%}
                                           {%=userAddress.address%}
                                           {%}%} 
                                        {% if(userAddress.mobile_number!='') { %} <br>
                                        <b>Mobile No:</b> {%userAddress.country_code%}-{%userAddress.mobile_number%}

                                        {%}%}

                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Payment Type </span>
                                       <span class="float-right text-muted">
                                       {%=pType%}
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Payment Status </span>
                                       <span class="float-right text-muted">
                                           {%=pStatus%}
                                       </span>
                                   </p>
                               </div>
                           </div>
                       </div>

                       {% if( item.sub_order[0].addons.length > 0 ){  %}
                       <strong>Sub Addons</strong>
                       <hr>
                       <table class="table">
                           <tr>
                               <th>SNo.</th>
                               <th>Addon Name</th>
                               <th>Amount</th>
                           </tr> 
                           {%  var s = 1; %}
                           {% for(var y in item.sub_order[0].addons){
                               var subAddonItem=item.sub_order[0].addons[y];
                               console.log('subAddonItem',subAddonItem);
                              
                           %}
                           

                           <tr>
                               <td>{%=(s)%}</td>
                               <td>{%=subAddonItem.sub_order_addon.name_en%}</td>
                               <td>{%=subAddonItem.amount%} KWD</td>

                           </tr>
                            {% s++;%}
                           {% } %}

                       </table>
                       <br>
                       {% } %}

                       {% if( item.extra_addon_order.length > 0 ){ var v = 1;  %}
                       <strong>Extra Addons</strong>
                       <hr>
                       <table class="table">
                           <tr>
                               <th>SNo.</th>
                               <th>Addon Name</th>
                               <th>Amount</th>
                               <th>Payment Mode</th>
                               <th>Payment Status</th>
                           </tr>

                           {% for(var y in item.extra_addon_order){
                               var extraAddonItem=item.extra_addon_order[y];
                           %}


                           <tr>
                               <td>{%=(v)%}</td>
                               <td>{%=extraAddonItem.service_addons.name_en%}</td>
                               <td>{%=extraAddonItem.amount%} KWD</td>

                               <td>
                                {% if(extraAddonItem.extra_addon_order_payment_history_deatils.payment_type == '1') { %}
                                  By Cash
                                {% } else { %}
                                    Online Payment
                                {% } %}</td>


                                <td> {% if(extraAddonItem.extra_addon_order_payment_history_deatils.payment_status == '0') { %}
                                    Pending
                                {% } else if(extraAddonItem.extra_addon_order_payment_history_deatils.payment_status == '1') { %}
                                    Success 
                                {% } else if(extraAddonItem.extra_addon_order_payment_history_deatils.payment_status == '2') { %}
                                    Fail
                                {% } %}</td>
                           </tr>
                           {%  v++;  %}
                           {% } %}

                       </table>
                       <br>
                       
                       {% } %}

                      {% if(item.category_type != 0){ var u = 0;  %}



                       <strong>Booking Tracking Details </strong><br><br>

                       <table class="table ">
                           <tr>
                               <th>SNo.</th>
                               <th>Status</th>
                               <th>Date</th>
                               <th>Action</th>
                           </tr>

                       {% for(var j in item.order_status){
                           var orderItem=item.order_status[j];

                       %}
                       <tr>
                           <td>{%=(u)%}</td>
                           <td>{%=orderStatusType(orderItem.status)%}</td>
                           <td> {%=orderItem.created_at%}</td>
                           <td>
                               {% if(orderItem.status == '0'){ %}
                                   <button type="button" class="btn btn-primary btn-icon-text btn-sm">Accept</button>
                                   <button type="button" class="btn btn-primary btn-icon-text btn-sm">Reject</button>
                               {% } %}

                               {% if(orderItem.status == '1'){ %}
                                   <button type="button" class="btn btn-success btn-icon-text btn-sm">On the way</button>
                               {% } %}

                               {% if(orderItem.status == '2'){ %}
                                  <button type="button" class="btn btn-info btn-icon-text btn-sm">In Progress</button>
                               {% } %}

                               {% if(orderItem.status == '3'){ %}
                                  <button type="button" class="btn btn-danger btn-icon-text btn-sm">Complete</button>
                               {% } %}

                               {% if(orderItem.status == '4'){ %}
                                  <button type="button" class="btn btn-danger btn-icon-text btn-sm">Service Completed</button>
                               {% } %}

                               {% if(orderItem.status == '5'){ %}
                                   <button type="button" class="btn btn-danger btn-icon-text btn-sm">Service Rejected</button>
                               {% } %}

                                {% if(orderItem.status == '6'){ %}
                                    <button type="button" class="btn btn-danger btn-icon-text btn-sm">Service Cancelled</button>
                                {% } %}
                           </td>
                       </tr>

                       {% u++;} %}
                       </table>
                       {% } else { %}
                           
                       {% } %}

                       {% if(item.category_type == 1){ %}
                                <strong>Insurance Details</strong>
                                <hr>
                                {% for(var k in item.sub_order){
                                    var insuranceItem=item.sub_order[k].insurance;
                                    console.log('insuranceItem',insuranceItem);
                                %}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="py-4">

                                            <p class="clearfix">
                                                <span class="float-left">Insurance start date</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.insurance_start_date%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Insurance type</span>

                                                    {% if(insuranceItem.insurance_type == "1"){ %}
                                                        <span class="float-right text-muted">
                                                            New Policy
                                                        </span>
                                                    {% }else{ %}
                                                        <span class="float-right text-muted">
                                                            Old Policy
                                                        </span>
                                                    {% } %}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Mobile Number</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.mobile_number%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Description</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.description%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Insurance Value</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.vehicle.vehicle_value%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Image</span>
                                                <span class="float-right text-muted">
                                                    {% if(insuranceItem.images!=''){
                                                        var insImage = insuranceItem.images.split(",");

                                                    %}
                                                    {% for(var r in insImage){
                                                        var insImageItem=insImage[r];
                                                    %}
                                                    <img src="https://s3.us-east-2.amazonaws.com/maak-care/{%=insImageItem%}" width="100px" height="100px" alt="img">
                                                    {% } %}
                                                    {% } %}
                                                </span>
                                            </p>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="py-4">
                                            <p class="clearfix">
                                                <span class="float-left">Car registration number</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.car_registration_number%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Current car estimation value</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.current_car_estimation_value%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Chassis number</span>
                                                <span class="float-right text-muted">
                                                    {%=insuranceItem.chassis_number%}
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Civil id front </span>
                                                <span class="float-right text-muted">
                                                    <img src="https://s3.us-east-2.amazonaws.com/maak-care/{%=insuranceItem.civil_id_front%}" alt="civil_front" width="100px" height="100px">
                                                </span>
                                            </p>
                                            <p class="clearfix">
                                                <span class="float-left">Civil id back </span>
                                                <span class="float-right text-muted">
                                                    <img src="https://s3.us-east-2.amazonaws.com/maak-care/{%=insuranceItem.civil_id_back%}" height="100px" width="100px" alt="civil_back">
                                                </span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                {% } %}
                           {% } %}


                   {% } %}

               </div>

               <div class="modal-footer">
                   <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
               </div>
           </div>

       </div>
   </div>

   {% } else{ %}

   <div class="modal fade" id="technician_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">

           <div class="modal-header">
               <h5 class="modal-title" id="ModalLabel">Technician Assignment</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">

               <strong>No Technician available</strong>

           </div>
           <div class="modal-footer">

               <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
           </div>
           </div>

       </div>



   </div>
   {% } %}

</script>
@endsection
