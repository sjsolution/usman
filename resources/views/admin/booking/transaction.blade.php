@extends('app')
@section('content')
<style>
.ikn {
    cursor   : pointer;
    font-size: x-large;
    margin-right: 10px;

}
.daterangepicker {
    display: block;
    top: 170px !important;
    left: 651.111px;
    right: auto;
}
.daterangepicker.ltr.show-calendar.opensright{
    z-index: 9999999 !important;
}
.dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
    position: fixed;
    width: 76%;
    z-index: 99;
    height: 66px;
    padding-top: 95px;
    padding-bottom: 43px;
    background: #fff;
    margin-bottom: 58px !important;
}
.transaction_history_row {
    background: white;
    height: 110px;
    width: 76%;
    z-index: 999;
    position: fixed;
    padding-bottom: 10px;
    padding-top: 20px;
}
.card .card-body {
    padding: 0px 20px!important;
}
table#users-table {
    margin-top: 150px !important;
}
i.fa.fa-eye.ikn, i.fa.fa-money.ikn, i.fa.fa-download.ikn {
    background: linear-gradient(to right, #da8cff, #9a55ff);
    border: 0;
    -webkit-transition: opacity 0.3s ease;
    transition: opacity 0.3s ease;
    padding: 3px 8px 4px 8px;
    color: #fff;
}




#myInput {
  box-sizing: border-box;
  background-image: url('searchicon.png');
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 45px;
  border: none;
  border-bottom: 1px solid #ddd;
}

#myInput:focus {outline: 3px solid #ddd;}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  /* overflow: auto; */
  border: 1px solid #ddd;
  z-index: 1;
  height: 144px;
  top: 43px;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}
</style>
<div class="content-wrapper">
    <div class="page-header">

    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card pd-0">
            <div class="card">
                <div class="card-body table-responsive">
                    <div class="transaction_history_row">
                    <h3 class="page-title"> Transaction History </h3>
                    <div class="row">
                        
                        <div class="col-md-3">
                            <button type="button" class="btn btn-gradient-danger btn-rounded btn-fw" onclick="updateAllSettled(event);">Settle Transaction</button>
                        </div>

                        <div class="col-md-5">
                            <div id="reportrange3" style="background: #fff; cursor: pointer;padding: 10px 10px; border: 1px solid #ccc;width: 360px; float:right">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span id="date_calender3"></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-gradient-danger btn-rounded btn-fw" onclick="exportReport(event);">Export</button>
                        </div>

                        <div class="dropdown">
                            <button  onclick="myFunction()" type="button" class="btn btn-gradient-danger btn-rounded btn-fw">Filter <i class="fa fas fa-filter"></i></button>
                            <div id="myDropdown" class="row dropdown-content" style="left: -350px;">
                                {{-- <div class="row"> --}}
                                    <div class="col-md-6" style="top: 20px;">
                                        <select class="form-control" id="settled_status" name="settled_status">
                                            <option value="1">All</option>
                                            <option value="2">Settled</option>
                                            <option value="3">Unsettled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6"  style="margin-left: 279px;top: -18px;">
                                        <select class="form-control" id="service_provider" name="service_provider" style="width:200px">
                                            <option value="">Select Service Provider</option>
                                        
                                            @foreach($orders as $item)
                                            @if($item->service_provider_id){
                                            <option value="{{$item->service_provider_id}}">{{\App\User::find($item->service_provider_id)['full_name_en']}}</option>
                                            @endif 
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <button class="btn btn-gradient-danger btn-rounded" onclick="filterTransaction(event)">Submit</button>
                                    </div>
                                {{-- </div> --}}
                            </div>
                          </div>
                      
                       
                        {{-- <a data-toggle="tooltip" data-placement="bottom"  title="Filter mandoob" class="page-title-icon bg-gradient-primary text-white" href="#" style="padding: 8px 15px;
                        border-radius: 12px;
                        margin-top: 4px;
                        margin-right: -241px;
                        font-size: 20px;
                        position: absolute;
                        right: 230px;
                        top: 53px;"  
                        onclick="filterData()" class="show-data" id="show-data"><i class="fa fas fa-filter"></i> --}}
                    </a>
                    </div> 
                <br>
               
                       
            </div>
        <br>
            {{-- <h4 class="card-title">Users</h4> --}}
            <table class="table table-hover table-responsive"  id="users-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                        <th>Order No.</th>
                        {{-- <th>Payment Id</th>
                        <th>Payment Token</th> --}}
                        <th>Service Provider</th>
                        <th>Service Amount</th>
                        <th>Commission</th>
                        {{-- <th>Discount</th>
                        <th>Knet Fee</th>
                        <th>Others Fee</th>
                        <th>User Applicable Fees</th> --}}
                        <th>Net Payable</th>
                        <th>Date</th>
                        <th>To Be Paid</th>
                        <th>Settled Amount</th>
                        <th>Settle Status</th>
                        <th>Status</th>
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

<div id="transaction_details_model"></div>
<div id="settlement_details_model"></div>

@endsection
@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="{{ asset('backend/js/daterangepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/daterangepicker.css') }}" />
<script>

function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}


function filterTransaction(event)
{
    event.preventDefault();
    $('#cover-spin').show(0)
    var transactionSettledStatus = $('#settled_status').val();
    var serviceProviderId        = $('#service_provider').val();
    var filterUrl = 'admin/transaction?settle_status='+transactionSettledStatus+'&service_provider_id='+serviceProviderId;

    var query_string ='filter_date='+document.getElementById('date_calender3').innerText;
    
    var table = $('#users-table').DataTable();
    table.ajax.url("{{ route('transaction.list') }}?"+query_string+'&service_provider_id='+serviceProviderId+'&settle_status='+transactionSettledStatus).load(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('#cover-spin').hide(0)

    });
   
}


    // query_string = '';

    // $("#service_provider").change(function(){
    //    var service_provider_id = $(this).val();
    //    var settled_status = $(this).val();
    //    reloadDatatable(service_provider_id,settled_status);

    // });

    // $("#settled_status").change(function(){
    //    var settled_status = $(this).val();
    //    var service_provider_id = $(this).val();

    //    reloadDatatable(service_provider_id,settled_status);
    // });

    // function reloadDatatable(service_provider_id='',settled_status=''){
    //     console.log(settled_status);
    //     var table = $('#users-table').DataTable();
    //     table.ajax.url("{{ route('transaction.list') }}?"+query_string+'&service_provider_id='+service_provider_id+'&settled_status='+settled_status).load(function(){
    //         $('[data-toggle="tooltip"]').tooltip();
    //     });
           
    // }

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".transaction_history_row").css('margin-top', '-4px');
        } else {
            $(".transaction_history_row").css('margin-top', '0px');
        } 
    });

    function exportReport(event)
    {
        var vendorIds = [];

        $('input[name="someCheckbox"]:checked').each(function() {
            vendorIds.push( this.id);
        });

        var date_range = document.getElementById('date_calender3').innerText;

        var vendorReportRequest = fetchRequest('/admin/transaction/export');

        var formData = new FormData();
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("settle_status", document.getElementById('settled_status').value);
        formData.append("date_range", date_range);
        vendorReportRequest.setBody(formData);
        vendorReportRequest.post().then(function (response) {
            console.log(response);
            window.location = "{{url('/')}}/storage/transaction_reports.xlsx";
            if (response.status === 200) {
            // getCategory(page);

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });
    }

    function updateAllSettled(event,status)
    {

        var transactionIds = [];

        $('input[name="someCheckbox"]:checked').each(function() {
            transactionIds.push( this.id);
        });

        console.log(transactionIds);

        if(transactionIds == '' ){

            swal({
                text: 'Please select any row',
                button: {
                text: "OK",
                value: true,
                visible: true,
                className: "btn btn-primary"
                }
            });

        }else{
            var $button = $(this);
            var table = $('#users-table').DataTable();
            message = "Do you want to settle the selected transaction."
            swal({
                title: 'Are you sure?',
                text: message,
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
                    className: "btn btn-warning",
                    closeModal: true,
                },
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-info",
                    closeModal: true,
                }
                }
            }).then(function(isConfirm){

                if(isConfirm){
                $.ajax({
                    url: "{{route('user.bulksettlementUpdate')}}",
                    method: 'POST',
                    data:{id:transactionIds,status:status},
                    type:"json",
                    success: function(response) {
                    if(response.status==1){
                        table.row( $button.parents('tr') ).remove().draw();
                    }
                    if(response.status==0){

                        swal({
                            text: 'Permission Denied',
                            button: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "btn btn-primary"
                            }
                        })
                    }
                    },
                    error:function(response){

                    swal({
                        text: response.responseJSON.message,
                        button: {
                        text: "OK",
                        value: true,
                        visible: true,
                        className: "btn btn-primary"
                        }
                    })
                    }
                });
                }
            });
        }

    }

    function settlement(event,transactionId){

        var settlementDetailRequest = fetchRequest('/admin/transaction/settlement/details');

        var formData = new FormData();

        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        formData.append("transaction_id", transactionId);

        settlementDetailRequest.setBody(formData);

        settlementDetailRequest.post().then(function (response) {

            if (response.status === 200) {
                response.json().then((errors) => {

                    document.getElementById('settlement_details_model').innerHTML = tmpl('settlement-details-list-tmpl',errors);
                    $('#settlement_details_modal_form').modal('show');


                });

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });
    }

    function transactionDetails(event,orderId){

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

    function settle(event,transactionId)
    {
        var settlementRequest = fetchRequest('/admin/transaction/settlement');

        var formData = new FormData();

        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        formData.append("transaction_id", transactionId);

        settlementRequest.setBody(formData);

        settlementRequest.post().then(function (response) {

            if (response.status === 200) {

                response.json().then((errors) => {
                    $("#users-table").dataTable().fnDestroy()
                    reloadTransaction();
                    $('#settlement_details_modal_form').modal('hide');
                    showInfoToast(errors.status,errors.message);

                });

            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });
    }
 
    function reloadTransaction(dateString='')
    {
       // table = $('#users-table').DataTable({
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('transaction.list') !!}'+'?filter_date='+dateString+'&settle_status='+document.getElementById('settled_status').value,
            "columns": [
                {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
                {data: 'order_id', name: 'order_id'},
                // {data: 'payment_id', name: 'payment_id'},
                // {data: 'payment_token', name: 'payment_token'},
                {data: 'sp_name', name: 'sp_name'},
                {data: 'service_amount', name: 'service_amount'},
                {data: 'commission', name: 'commission'},
                // {data: 'discount', name: 'discount'},
                // {data: 'knet_fees', name: 'knet_fees'},
                // {data: 'others_fees', name: 'others_fees'},
                // {data: 'user_applicable_fee', name: 'user_applicable_fee'},
                {data: 'net_payable', name: 'net_payable'},
                {data: 'created_at', name: 'created_at'},
                {data: 'to_be_paid', name: 'to_be_paid'},
                {data: 'settlement_amount', name: 'settlement_amount'},
                {data: 'is_settlement', name: 'is_settlement'},
                {data: 'status', name: 'status'},
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
    }


$(function() {

  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

//   $('select').on('change',function(){
//     date_range = document.getElementById('date_calender3').innerText;
//     reloadTransaction(date_range);
//     $("#users-table").dataTable().fnDestroy()
//   });


    var start = moment().subtract(29, 'days');
    var end = moment();

    var startToday = moment().subtract(29, 'days');
    var endToday = moment();

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(document).ready(function(){

        function cb3(startToday, endToday) {

            $('#reportrange3 span').html(startToday.format('MMMM D, YYYY') + ' - ' + endToday.format('MMMM D, YYYY'));
            var date_range = document.getElementById('date_calender3').innerText;
            reloadTransaction(date_range);

        }
        /*function cb3(startToday, endToday) {

            $('#reportrange3 span').html(startToday.format('MMMM D, YYYY') + ' - ' + endToday.format('MMMM D, YYYY'));
            var date_range = document.getElementById('date_calender3').innerText;
            query_string+='filter_date='+date_range;
            reloadTransaction(date_range,'','');
        }*/

        $('#reportrange3').daterangepicker({
            startDate: startToday,
            endDate: endToday,
            // ranges: {
            //    'Today': [moment(), moment()],
            //    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            //    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            //    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //    'This Month': [moment().startOf('month'), moment().endOf('month')],
            //    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            // }
        }, cb3);

        $('.applyBtn').click(function(){

            console.log(startToday.format('YYYY-MM-DD') + ' - ' + endToday.format('YYYY-MM-DD'));
            $("#users-table").dataTable().fnDestroy()
        });

        cb3(startToday, endToday);

    });

});

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
                                        <span class="float-left"> Service Amount </span>
                                        <span class="float-right text-muted">
                                            {%=item.service_amount%}
                                        </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left"> Actual Maak's share </span>
                                        <span class="float-right text-muted">
                                            {%=item.commission%} KWD
                                        </span>
                                    </p>
                                    <p class="clearfix">
                                        <span class="float-left"> Actual Service providers </span>
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
                                        <span class="float-left">  Service provider's share </span>
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



<script type="text/x-tmpl" id="settlement-details-list-tmpl">
    {% if(o.list.length) { %}
    <div class="modal fade" id="settlement_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Settlement Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {% for(var i in o.list){
                        var item=o.list[i];
                    %}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="py-4">
                                    <p class="clearfix">
                                        <span class="float-left"> Transaction-ID </span>
                                        <span class="float-right text-muted"> {%=item.id%} </span>
                                    </p>
                                    {{-- <p class="clearfix">
                                        <span class="float-left"> Service Provider </span>
                                        <span class="float-right text-muted"> {%=item.service_provider_id%} </span>
                                    </p> --}}

                                    <p class="clearfix">
                                        <span class="float-left"> Service Amount </span>
                                        <span class="float-right text-muted">
                                            {%=item.service_amount%} KWD
                                        </span>
                                    </p>

                                    <p class="clearfix">
                                        <span class="float-left"> Cash payable </span>
                                        <span class="float-right text-muted">
                                            {%=item.cash_payable%} KWD
                                        </span>
                                    </p>

                                    {{-- <p class="clearfix">
                                        <span class="float-left"> Commission </span>
                                        <span class="float-right text-muted"> {%=item.commission%} KWD</span>
                                    </p> --}}


                                    <p class="clearfix" style="margin-left:30%">
                                        <button type="button" class="btn btn-gradient-dark btn-rounded btn-fw" onclick="settle(event, {%=item.id%})">Settle</button>
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
