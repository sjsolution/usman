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
        padding:6px 0px!important;
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
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body table-responsive">
                <h3 class="page-title">
                  Transaction History
                </h3>
                    {{-- <h4 class="card-title">Users</h4> --}}
                    <table class="table "  id="users-table">
                        <thead>
                            <tr>
                                <th>Order No.</th>
                                {{-- <th>Payment Id</th>
                                <th>Payment Token</th> --}}
                                <th>Service Provider</th>
                                <th>Service Amount</th>
                                <th>Commission</th>
                                <th>Discount</th>
                                <th>Knet Fee</th>
                                <th>Others Fee</th>
                                <th>User Applicable Fee</th>
                                <th>Net Payable</th>
                                <th>Date</th>
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
$(function() {

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('sp.transaction.list') !!}',
        "columns": [
            {data: 'order_id', name: 'order_id'},
            // {data: 'payment_id', name: 'payment_id'},
            // {data: 'payment_token', name: 'payment_token'},
            {data: 'sp_name', name: 'sp_name'},
            {data: 'service_amount', name: 'service_amount'},
            {data: 'commission', name: 'commission'},
            {data: 'discount', name: 'discount'},
            {data: 'knet_fees', name: 'knet_fees'},
            {data: 'others_fees', name: 'others_fees'},
            {data: 'user_applicable_fee', name: 'user_applicable_fee'},
            {data: 'net_payable', name: 'net_payable'},
            {data: 'created_at', name: 'created_at'},
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
});

function transactionDetails(event,orderId){

    var transactionDetailRequest = fetchRequest('/serviceprovider/booking/transaction/details');

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
                                        <span class="float-left"> Cash Receivable </span>
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
                                        <span class="float-left"> Net Maak's Share </span>
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
@endsection
