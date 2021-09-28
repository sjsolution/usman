@extends('appsp')
@section('content')
<style>
    .ikn {
       cursor   : pointer;
       font-size: x-large;
    }
    .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
        position: fixed;
        width: 70%;
        z-index: 99;
        height: 66px;
        padding-top: 60px;
        padding-bottom: 43px;
        background: #fff;
        margin-bottom: 58px !important;
    }
    .page-title {
        background: white;
        height:50px;
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
        margin-top: 120px !important;
    }
</style>
<div class="content-wrapper">
    <div class="page-header">

    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body table-responsive">
                <h3 class="page-title">
           Review List
        </h3>

                    {{-- <h4 class="card-title">Users</h4> --}}
                    <table class="table "  id="users-table">
                        <thead>
                            <tr>
                                <th>Order-ID.</th>
                                <th>Who Reviewed</th>
                                <th>To Whom Reviewed</th>
                                <th>Review Description</th>
                                <th>Ratings</th>
                                <th>Date</th>
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
<div class="row" id="review_details_model"></div>


@endsection

@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>


<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".card").css('margin-top', '-25px');
        } else {
            $(".card").css('margin-top', '0px');
        }
    });
function showOrderDetails(event,orderId){

    var technicianListRequest = fetchRequest('/serviceprovider/booking/order/details');

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




$(function() {

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('review.list') !!}',
        "columns": [
            {data: 'order_number', name: 'order_number'},
            {data: 'who_reviewed', name: 'who_reviewed'},
            {data: 'whom_reviwed', name: 'whom_reviwed'},
            {data: 'reviews', name: 'reviews'},
            {data: 'rating', name: 'rating'},
            {data: 'created_at', name: 'created_at'},
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

function changeStatus(event,orderId,status){

    var orderStatusRequest = fetchRequest('/serviceprovider/order/status/changed');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    formData.append("order_id", orderId);

    formData.append("status", status);

    orderStatusRequest.setBody(formData);

    orderStatusRequest.post().then(function (response) {

        if (response.status === 200) {

            response.json().then((errors) => {

                if(errors.status == 'Success'){
                    showInfoToast(errors.status,errors.message);
                    orderDetails(event,orderId);
                }else{
                    showDangerToast(errors.status,errors.message)
                }

            });

        }else if(response.status === 422){
            response.json().then((errors) => {
                showDangerToast('Error','Something went wrong')
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

        default :
           text = 'Pending';
           return text;

    return text;

   }
}

function reviewDetails(event,reviewId){

    var reviewRequest = fetchRequest('/serviceprovider/review/details');

    var formData = new FormData();

    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    formData.append("review_id", reviewId);

    reviewRequest.setBody(formData);

    reviewRequest.post().then(function (response) {

        if (response.status === 200) {
            response.json().then((errors) => {
                console.log(errors);
                document.getElementById('review_details_model').innerHTML = tmpl('review-details-list-tmpl',errors);
                $('#review_details_modal_form').modal('show');

            });

        }else if(response.status === 422){
            response.json().then((errors) => {
                console.log('Error');
            });
        }
    });

}


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

                        if(item.payment_type == 1)
                            pType = 'Wallet'
                        else if(item.payment_type == 2)
                            pType = 'Knet';
                        else if(item.payment_type == 3)
                            pType = 'Knet & Wallet'

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
                                    <span class="float-left"> Order-ID </span>
                                    <span class="float-right text-muted"> {%=item.order_number%} </span>
                                    </p>
                                    <p class="clearfix">
                                    <span class="float-left"> Username </span>
                                    <span class="float-right text-muted"> {%=item.username%} </span>
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
                                    <span class="float-left"> Technicain  </span>
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
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="py-4">
                                    <p class="clearfix">
                                        <span class="float-left"> Service Name </span>
                                        <span class="float-right text-muted"> {%=item.service%} </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left"> Total Amount </span>
                                        <span class="float-right text-muted"> {%=item.total_amount%} KWD</span>
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
                                        <span class="float-right text-muted">
                                            {%=userAddress.block%} {%=userAddress.street%} {%=userAddress.avenue%} {%=userAddress.building%}
                                            {%=userAddress.floor%} {%=userAddress.house%} {%=userAddress.office%} {%=userAddress.appartment_number%}
                                            {%=userAddress.address%}
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

                        {% if( item.extra_addon_order.length > 0 ){  %}
                        <strong>Extra Addons</strong>
                        <hr>
                        <table class="table table-striped">
                            <tr>
                                <th>SNo.</th>
                                <th>Addon Name</th>
                                <th>Amount</th>
                            </tr>

                            {% for(var y in item.extra_addon_order){
                                var extraAddonItem=item.extra_addon_order[y];
                            %}
                            <tr>
                                <td>{%=(y)%}</td>
                                <td>{%=extraAddonItem.service_addons.name_en%}</td>
                                <td>{%=extraAddonItem.amount%} KWD</td>
                            </tr>

                            {% } %}

                        </table>
                        <strong>Extra Addons Payment Status</strong>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-4">

                                    <p class="clearfix">
                                        <span class="float-left">Total Amount</span>
                                        <span class="float-right text-muted">
                                            {%=item.extra_addon_order_payment_history.amount%} KWD
                                        </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left">Payment Type</span>
                                        <span class="float-right text-muted">
                                            {% if(item.extra_addon_order_payment_history.payment_type == '1') { %}
                                               By Cash
                                            {% } else { %}
                                               Online Payment
                                            {% } %}
                                        </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left">Payment Status</span>
                                        <span class="float-right text-muted">
                                             {% if(item.extra_addon_order_payment_history.payment_status == '0') { %}
                                                Pending
                                             {% } else if(item.extra_addon_order_payment_history.payment_status == '1') { %}
                                                Success
                                             {% } else if(item.extra_addon_order_payment_history.payment_status == '2') { %}
                                                Fail
                                             {% } %}
                                        </span>
                                    </p>

                                </div>
                            </div>
                        </div>

                        <br>
                        {% } %}

                       {% if(item.category_type != 1){ %}



                        <strong>Booking Tracking Details</strong>
                        <hr>
                        <table class="table table-striped">
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
                            <td>{%=(j+1)%}</td>
                            <td>{%=orderStatusType(orderItem.status)%}</td>
                            <td> {%=orderItem.created_at%}</td>
                            <td>
                                {% if(orderItem.status == '0'){ %}
                                    <button type="button" class="btn btn-primary btn-icon-text btn-sm" >Accepted</button>
                                    <button type="button" class="btn btn-primary btn-icon-text btn-sm" >Rejected</button>
                                {% } %}

                                {% if(orderItem.status == '1'){ %}
                                    <button type="button" class="btn btn-success btn-icon-text btn-sm" >On the way</button>
                                {% } %}

                                {% if(orderItem.status == '2'){ %}
                                   <button type="button" class="btn btn-info btn-icon-text btn-sm" >In Progress</button>
                                {% } %}

                                {% if(orderItem.status == '3'){ %}
                                   <button type="button" class="btn btn-danger btn-icon-text btn-sm" >Completed</button>
                                {% } %}

                                {% if(orderItem.status == '4'){ %}
                                   <button type="button" class="btn btn-danger btn-icon-text btn-sm">Service Completed</button>
                                {% } %}

                                {% if(orderItem.status == '5'){ %}
                                    <button type="button" class="btn btn-danger btn-icon-text btn-sm">Service Rejected</button>
                                {% } %}
                            </td>
                        </tr>

                        {% } %}
                        </table>
                        {% } else { %}
                            <strong>Insurance Details</strong>
                            <hr>
                            {% for(var k in item.sub_order){
                                var insuranceItem=item.sub_order[k].insurance;
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
                                            <span class="float-left">Image</span>
                                            <span class="float-right text-muted">
                                                <img src="{%=insuranceItem.images%}" width="100px" height="100px" alt="img">
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
                                                <img src="{%=insuranceItem.civil_id_front%}" alt="civil_front" width="100px" height="100px">
                                            </span>
                                        </p>
                                        <p class="clearfix">
                                            <span class="float-left">Civil id back </span>
                                            <span class="float-right text-muted">
                                                <img src="{%=insuranceItem.civil_id_back%}" height="100px" width="100px" alt="civil_back">
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

<script type="text/x-tmpl" id="review-details-list-tmpl">
    {% if(o.list) { console.log(o); %}
        <div class="modal fade" id="review_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
     
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Review Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
     
                    <div class="modal-body">
                     
     
                        <div class="row form-group">
                            <div class="col-md-6">Ratings</div>
                            <div class="col-md-6" style="word-break: break-all;">{%=o.list.rating%}</div>
                        </div>
    
                        <div class="row form-group">
                            <div class="col-md-6">Review Descriptions</div>
                            <div class="col-md-6" style="word-break: break-all;">{%=o.list.reviews%}</div>
                        </div>
                    
     
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
