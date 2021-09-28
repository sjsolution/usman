@extends('app')
@section('content')
<style>
   .ikn {
   cursor   : pointer;
   font-size: x-large;
   }
   .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
       position: fixed;
       width: 76%;
       z-index: 99;
       height: 66px;
       padding-top: 75px;
       padding-bottom: 43px;
       background: #fff;
       margin-bottom: 58px !important;
   }
   .coupon_list_row {
       background: white;
       height: 70px;
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
       margin-top: 125px !important;
   }
   i.fa.fa-eye.ikn, i.fa.fa-money.ikn, i.fa.fa-download.ikn {
       background: linear-gradient(to right, #da8cff, #9a55ff);
       border: 0;
       -webkit-transition: opacity 0.3s ease;
       transition: opacity 0.3s ease;
       padding: 3px 8px 4px 8px;
       color: #fff;
   }
</style>
<div class="content-wrapper">
   <div class="page-header">
   </div>
   <div class="row">
      <div class="col-lg-12 grid-margin stretch-card pd-0">
         <div class="card ">
            <div class="card-body  table-responsive">



               <div class=" add_button">
                   <div class="coupon_list_row">
                  <div class="row">

                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <h3 class="page-title"> Coupon List   </h3>
                      </div>


                      <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                          <a  class="btn btn-gradient-danger btn-md btn-fill "  href="{{ route('coupon.create') }}">Add +</a>
                      </div>
                  </div>
                  </div>
               </div>



               <table class="table table-hover"  id="users-table">
                  <thead>
                     <tr>
                        <th>Name</th>
                        <th>Coupon Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Valid Till</th>
                        <th>Min Value</th>
                        <th>Max Value</th>
                        <th>User Limit</th>
                        <th>Coupon Use Limit</th>
                        <th>Status</th>
                        <th>Display</th>
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
<div class="row" id="coupon_details_model"></div>
@endsection
@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".col-lg-12.grid-margin.stretch-card.pd-0").css('margin-top', '-16px');
        } else {
            $(".col-lg-12.grid-margin.stretch-card.pd-0").css('margin-top', '10px');
        }
    });
   $(function() {

       $('#users-table').DataTable({
           processing: true,
           serverSide: true,
           ajax: '{!! route('coupon.list') !!}',
           "columns": [
               {data: 'name_en', name: 'name_en'},
               {data: 'code',    name: 'code'},
               {data: 'type', name: 'type'},
               {data: 'coupon_value', name: 'coupon_value'},
               {data: 'valid_till', name: 'valid_till'},
               {data: 'coupon_min_value', name: 'coupon_min_value'},
               {data: 'coupon_max_value', name: 'coupon_max_value'},
               {data: 'user_limit', name: 'user_limit'},
               {data: 'coupon_per_user_limit', name: 'coupon_per_user_limit'},
               {data: 'status', name: 'status'},
               {data: 'is_display', name: 'is_display'},
               {data: 'action', name: 'action'}
           ],
           "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
           "iDisplayLength": 50,
           // "bStateSave": true,
           // "fnStateSave": function (oSettings, oData) {
           //     localStorage.setItem('offersDataTables', JSON.stringify(oData));
           // },
           // "fnStateLoad": function (oSettings) {
           //     return JSON.parse(localStorage.getItem('offersDataTables'));
           // }
           initComplete: function () {

           }

       });
   });

   function changeStatus(event,id,status){

       var stateText = (status == 0) ? 'Inactive' : 'Active';

       swal({
           title: 'Do you want change status to '+stateText+'?',
           text: "",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3f51b5',
           cancelButtonColor: '#ff4081',
           confirmButtonText: 'Great ',
           buttons: {
             cancel: {
               text: "Cancel",
               value: null,
               visible: true,
               className: "btn btn-danger",
               closeModal: true,
             },
             confirm: {
               text: "OK",
               value: true,
               visible: true,
               className: "btn btn-primary",
               closeModal: true,
             }
           }
         }).then((willDelete) => {
             if (willDelete) {
               var $button = $(this);
               var table = $('#users-table').DataTable();
               var couponRequest = fetchRequest('/admin/coupon/status');
               var formData = new FormData();
               formData.append("_token", "{{ csrf_token() }}");
               formData.append("status", status);
               formData.append("couponId", id);
               couponRequest.setBody(formData);
               couponRequest.post().then(function (response) {
                   if (response.status === 200) {
                       table.row( $button.parents('tr') ).remove().draw();
                       showInfoToast('Success','coupon status successfully changed');


                   }else if(response.status === 422){
                       response.json().then((errors) => {
                        swal("Permission denied!");
                       });
                   }
               });
           } else {
               swal("change status operation failed!");
           }
       });

   }

    function changeDisplayStatus(event,id,status){

        var stateText = (status == 0) ? 'Hide' : 'Show';

        swal({
            title: 'Do you want change status to '+stateText+'?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3f51b5',
            cancelButtonColor: '#ff4081',
            confirmButtonText: 'Great ',
            buttons: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: true,
                className: "btn btn-danger",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "btn btn-primary",
                closeModal: true,
            }
            }
        }).then((willDelete) => {
            if (willDelete) {
                var $button = $(this);
                var table = $('#users-table').DataTable();
                var couponRequest = fetchRequest('/admin/coupon/display/status');
                var formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("status", status);
                formData.append("couponId", id);
                couponRequest.setBody(formData);
                couponRequest.post().then(function (response) {
                    if (response.status === 200) {
                        table.row( $button.parents('tr') ).remove().draw();
                        showInfoToast('Success','coupon display status successfully changed');


                    }else if(response.status === 422){
                        response.json().then((errors) => {
                        swal("Permission denied!");
                        });
                    }
                });
            } else {
                swal("change status operation failed!");
            }
        });

    }

   function couponDetails(event,couponId){


       var couponDetailsRequest = fetchRequest('/admin/coupon/details');

       var formData = new FormData();

       formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

       formData.append("coupon_id", couponId);

       couponDetailsRequest.setBody(formData);

       couponDetailsRequest.post().then(function (response) {

           if (response.status === 200) {
               response.json().then((errors) => {

                   document.getElementById('coupon_details_model').innerHTML = tmpl('coupon-details-list-tmpl',errors);
                   $('#coupon_details_modal_form').modal('show');

               });

           }else if(response.status === 422){
               response.json().then((errors) => {
                   console.log('Error');
               });
           }
       });

   }


</script>
<script type="text/x-tmpl" id="coupon-details-list-tmpl">
   {% if(o.list.length) { %}
   <div class="modal fade" id="coupon_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg" role="document">
           <div class="modal-content">

               <div class="modal-header">
                   <h5 class="modal-title" id="ModalLabel">Coupon Details</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                   </button>
               </div>

               <div class="modal-body">
                   {% for(var i in o.list){
                       var item=o.list[i];
                       var pType = '';
                       var pStatus = '';
                       if(item.type == 1)
                           pType = 'Percentage'
                       else
                           pType = 'Amount';

                       if(item.status == 0)
                           pStatus = 'In-active'
                       else if(item.status == 1)
                           pStatus = 'Active';

                   %}
                       <div class="row">
                           <div class="col-md-6">
                               <div class="py-4">
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Name (In English)</span>
                                       <span class="float-right text-muted"> {%=item.name_en%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Code </span>
                                       <span class="float-right text-muted"> {%=item.code%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Type </span>
                                       <span class="float-right text-muted"> {%=pType%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Min Value </span>
                                       <span class="float-right text-muted"> {%=item.coupon_min_value%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> User Limit </span>
                                       <span class="float-right text-muted"> {%=item.user_limit%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Description (In English) </span>
                                       <span class="float-right text-muted"> {%=item.description_en%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Image </span>
                                       <span class="float-right text-muted"> {%=item.image%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Customer Name </span>
                                       <span class="float-right text-muted">
                                           {% for(var j in item.assigned_user){
                                               var userItem=item.assigned_user[j];
                                           %}
                                               {%=userItem.user.full_name_en%},

                                           {% } %}
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Service Providers </span>
                                       <span class="float-right text-muted">
                                           {% for(var l in item.assigned_service_provider){
                                               var spItem=item.assigned_service_provider[l];
                                           %}
                                               {%=spItem.user.full_name_en%},

                                           {% } %}
                                       </span>
                                   </p>

                               </div>
                           </div>
                           <div class="col-md-6">
                               <div class="py-4">
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Name (In Arabic)</span>
                                       <span class="float-right text-muted"> {%=item.name_ar%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Amount </span>
                                       <span class="float-right text-muted"> {%=item.coupon_value%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Valid Till </span>
                                       <span class="float-right text-muted"> {%=item.valid_till%}</span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Max Value </span>
                                       <span class="float-right text-muted"> {%=item.coupon_max_value%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Coupon Use Limit </span>
                                       <span class="float-right text-muted"> {%=item.coupon_per_user_limit%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Description (In Arabic) </span>
                                       <span class="float-right text-muted"> {%=item.description_ar%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Status </span>
                                       <span class="float-right text-muted"> {%=pStatus%} </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Service category </span>
                                       <span class="float-right text-muted">
                                           {% for(var k in item.assigned_category){
                                               var categoryItem=item.assigned_category[k];
                                           %}
                                               {%=categoryItem.category.name_en%}

                                           {% } %}
                                       </span>
                                   </p>
                                   <p class="clearfix">
                                       <span class="float-left"> Services </span>
                                       <span class="float-right text-muted">
                                           {% for(var m in item.assigned_services){
                                               var sItem=item.assigned_services[m];
                                           %}
                                               {%=sItem.service.name_en%},

                                           {% } %}
                                       </span>
                                   </p>

                               </div>
                           </div>
                       </div>

                   {% } %}

               </div>

               <div class="modal-footer">
                   <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
               </div>
           </div>

       </div>
   </div>

   {% } else{ %}

   <div class="modal fade" id="coupon_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
