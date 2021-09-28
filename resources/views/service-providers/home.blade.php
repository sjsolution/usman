@extends('appsp')
@section('content')

<style>
    .a-custom{
      color: white;
      margin-left: -11px;
    }

    .ikn {
       cursor   : pointer;
       font-size: x-large !important;
       margin-right : 10px;
    }
    i.fa.fa-address-book-o.ikn, i.fa.fa-eye.ikn, i.fa.fa-money.ikn, i.fa.fa-download.ikn {
    background: linear-gradient(to right, #da8cff, #9a55ff);
    border: 0;
    -webkit-transition: opacity 0.3s ease;
    transition: opacity 0.3s ease;
    padding: 3px 8px 4px 8px;
    color: #fff;
}

    @-webkit-keyframes invalid {
      from { background-color: red; }
      to { background-color: inherit; }
    }
    @-moz-keyframes invalid {
      from { background-color: red; }
      to { background-color: inherit; }
    }
    @-o-keyframes invalid {
      from { background-color: red; }
      to { background-color: inherit; }
    }
    @keyframes invalid {
      from { background-color: red; }
      to { background-color: inherit; }
    }
    .invalid {
      -webkit-animation: invalid 1s infinite; /* Safari 4+ */
      -moz-animation:    invalid 1s infinite; /* Fx 5+ */
      -o-animation:      invalid 1s infinite; /* Opera 12+ */
      animation:         invalid 1s infinite; /* IE 10+ */
    }

    @-webkit-keyframes on_the_way {
      from { background-color: coral; }
      to { background-color: inherit; }
    }
    @-moz-keyframes on_the_way {
      from { background-color: coral; }
      to { background-color: inherit; }
    }
    @-o-keyframes on_the_way {
      from { background-color: coral; }
      to { background-color: inherit; }
    }
    @keyframes on_the_way {
      from { background-color: coral; }
      to { background-color: inherit; }
    }

    .on_the_way {
      -webkit-animation: on_the_way 1s infinite; /* Safari 4+ */
      -moz-animation:    on_the_way 1s infinite; /* Fx 5+ */
      -o-animation:      on_the_way 1s infinite; /* Opera 12+ */
      animation:         on_the_way 1s infinite; /* IE 10+ */
    }

    @-webkit-keyframes pending {
      from { background-color: lightskyblue; }
      to { background-color: inherit; }
    }
    @-moz-keyframes pending {
      from { background-color: lightskyblue; }
      to { background-color: inherit; }
    }
    @-o-keyframes pending {
      from { background-color: lightskyblue; }
      to { background-color: inherit; }
    }
    @keyframes pending {
      from { background-color: lightskyblue; }
      to { background-color: inherit; }
    }

    .pending {
      -webkit-animation: pending 1s infinite; /* Safari 4+ */
      -moz-animation:    pending 1s infinite; /* Fx 5+ */
      -o-animation:      pending 1s infinite; /* Opera 12+ */
      animation:         pending 1s infinite; /* IE 10+ */
    }

    @-webkit-keyframes start {
      from { background-color: lightgreen; }
      to { background-color: inherit; }
    }
    @-moz-keyframes start {
      from { background-color: lightgreen; }
      to { background-color: inherit; }
    }
    @-o-keyframes start {
      from { background-color: lightgreen; }
      to { background-color: inherit; }
    }
    @keyframes start {
      from { background-color: lightgreen; }
      to { background-color: inherit; }
    }

    .start {
      -webkit-animation: start 1s infinite; /* Safari 4+ */
      -moz-animation:    start 1s infinite; /* Fx 5+ */
      -o-animation:      start 1s infinite; /* Opera 12+ */
      animation:         start 1s infinite; /* IE 10+ */
    }

    td {
        padding: 1em;
    }

    span.page-title-icon.bg-gradient-primary.text-white.mr-2 {
        display: none !important;
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
  <br><br>
  <div class="page-header">

    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white mr-2">
        <i class="mdi mdi-home"></i>
      </span> Dashboard </h3>

  </div>
  <div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">
              <a href="{{ url('serviceprovider/booking-view') }}" class="a-custom">Orders (Today / Total) <i class="mdi mdi-chart-line mdi-24px float-right"></i></a>
          </h4>
          <h2 class="mb-5" id="order_stat"> {{ $todayOrder }} / {{ $totalOrder }}</h2>

        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Revenue (Today / Total)<i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5" id="revenue_stat">{{ $todayRevenue }} / {{ $totalRevenue }} KWD</h2>

        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Customers (Today / Total) <i class="mdi mdi-diamond mdi-24px float-right"></i>
          </h4>
        <h2 class="mb-5" id="user_stat"> {{ $todayCustomer }} / {{ $totalCustomer}}</h2>

        </div>
      </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
          <div class="card-body">
            <img src="{{asset('images/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
            <h4 class="font-weight-normal mb-3">Total Active Services<i class="mdi mdi-diamond mdi-24px float-right"></i>
            </h4>
          <h2 class="mb-5" id="active_stat">{{ $activeService }}</h2>
          </div>
        </div>
      </div>
  </div>

  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Recent Bookings</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th> Order-ID </th>
                  <th> Username </th>
                  <th> Category </th>
                  <th> Service </th>
                  <th> Amount </th>
                  <th> Status </th>
                  <th> Booking Date </th>
                  <th> Action </th>
                </tr>
              </thead>
              <tbody id="order_list">
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

  <div class="row">
      <div class="col-md-12">
        <figure class="highcharts-figure">
            <div id="revenue_graph"></div>
        </figure>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <figure class="highcharts-figure">
            <div id="order_graph"></div>
        </figure>
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
<script src="{{asset('js/highcharts.js') }}"></script>
<script src="{{asset('js/series-label.js') }}"></script>
<script src="{{asset('js/exporting.js') }}"></script>
<script src="{{asset('js/export-data.js') }}"></script>
<script src="{{asset('js/accessibility.js') }}"></script>
<script src="{{asset('js/highcharts-3d.js') }}"></script>
<script src="{{asset('js/cylinder.js') }}"></script> 
<script>

$(document).ready(function() {
  orderList();
  revenueChart();
  orderChart();

});

$(document).ready(function(){

setInterval(function(){
    $.ajax({
        url:'/serviceprovider/dashboard/statistics',
        type:'GET',
        dataType:'json',
        success:function(response){
        
        if(response){
            $('#user_stat').html(response.todayCustomer+' / '+response.totalCustomer);
            $('#order_stat').html(response.todayOrder+' / '+response.totalOrder);
            $('#revenue_stat').html(response.todayRevenue.toFixed(3)+' / '+response.totalRevenue.toFixed(3) + 'KWD');
            $('#active_stat').html(response.activeService);
            orderList();
        }
        
        },error:function(err){

        }
    })
}, 5000);

});


function orderList(){

  var bookingListRequest = fetchRequest('/serviceprovider/dashboard/booking/order/list');

  var formData = new FormData();

  bookingListRequest.get().then(function (response) {

      if (response.status === 200) {
          response.json().then((errors) => {

              document.getElementById('order_list').innerHTML = tmpl('order-list-tmpl',errors);

          });

      }else if(response.status === 422){
          response.json().then((errors) => {
              console.log('Error');
          });
      }
  });

}

function revenueChart(){

  var revenueListRequest = fetchRequest('/serviceprovider/dashboard/revenue/graph');

  var formData = new FormData();

  revenueListRequest.get().then(function (response) {

      if (response.status === 200) {

          response.json().then((errors) => {

            revenueGraphRender(errors.data);

          });

      }else if(response.status === 422){
          response.json().then((errors) => {
              console.log('Error');
          });
      }
  });

}

function orderChart(){

  var orderListRequest = fetchRequest('/serviceprovider/dashboard/order/graph');

  var formData = new FormData();

  orderListRequest.get().then(function (response) {

      if (response.status === 200) {

          response.json().then((errors) => {

            orderGraphRender(errors.data);

          });

      }else if(response.status === 422){
          response.json().then((errors) => {
              console.log('Error');
          });
      }
  });
}

function revenueGraphRender(data){

  var monthArr   = [];
  var revenueArr = [];
  for(var i in data){
    monthArr.push(data[i].month)
    revenueArr.push(data[i].revenue)
  }

  Highcharts.chart('revenue_graph', {
    chart: {
        type: 'cylinder',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            depth: 50,
            viewDistance: 25
        }
    },
    title: {
        text: 'Revenue Chart'
    },
    xAxis: {
        categories: monthArr
    },
    plotOptions: {
        series: {
            depth: 25,
            colorByPoint: true
        }
    },
    series: [{
        data: revenueArr,
        name: 'Revenue',
        showInLegend: false
    }]
  });
}

function orderGraphRender(data){
  var orderMonthArr   = [];
  var orderCountArr   = [];
  for(var i in data){
    orderMonthArr.push(data[i].month)
    orderCountArr.push(data[i].order_count)
  }
  Highcharts.chart('order_graph', {

    title: {
        text: 'Booking charts'
    },

    yAxis: {
        title: {
            text: 'Number of Bookings'
        }
    },

    xAxis: {
        categories : orderMonthArr
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    series: [
    {
        name: 'Bookings',
        data: orderCountArr
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

  });
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
                   $('#order_details_modal_form').modal('hide');
                   orderDetails(event,orderId);
                //    location.reload();
                   var $button = $(this);
                   var table = $('#users-table').DataTable(); 
                   table.row( $button.parents('tr')).remove().draw();


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
                    showInfoToast('Technician successfully assigned')
                });

            }else if(response.status === 422){
                response.json().then((errors) => {
                    showDangerToast(errors.status,errors.message)
                });
            }
        });

            return true;

    }else{
        return false;
    }
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
            <div class="modal-body body_padding">
 
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Booking Id</label>
                    <input type="text" name="order_id" value="{% if(o.order_id!=='null') { %} {%=o.order_id%} {% } %}" class="form-control" readonly>
                </div>
 
                <h2 style="left: 198px; position: relative;"><i class="mdi mdi-debug-step-into"></i></h2>
 
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Technician List (Select one)</label>
                    <select class="form-control" id="select-id"  data-parsley-required="true" data-parsley-required-message="You must select at least one option." name="technician_id">
                        <option value="">--select one--</option>
                        <option style="background-color:lightgrey;" value="{%=o.booked_technician[0].id%}" disabled>{%=o.booked_technician[0].full_name_en%}</option>
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
                <h5 class="modal-title" id="ModalLabel">Technician  Assignment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body body_padding">
 
             <div class="form-group">
                 <label for="message-text" class="col-form-label">Technician List (Select one)</label>
                 <select class="form-control" id="select-id"  data-parsley-required="true" data-parsley-required-message="You must select at least one option." name="technician_id">
                     <option value="">--select one--</option>
                     <option style="background-color:lightgrey;" value="{%=o.booked_technician[0].id%}" disabled>{%=o.booked_technician[0].full_name_en%}</option>
                     {% for(var i in o.list){
                         var item=o.list[i];
                     %}
                         <option value="{%=item.id%}">{%=item.full_name_en%}</option>
                     {% } %}
                 </select>
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

<script type="text/x-tmpl" id="order-list-tmpl">
  {% if(o.list.length) { %}

    {% for(var i in o.list){
        var item=o.list[i];

        var cStatus = '';
        var cColor  = 'blue';
        var bLing = '';

        if(item.status == 0){
          cStatus = 'Pending';
          cColor  = 'blue';
          bLing = 'pending';

        }else if(item.status == 1){
          cStatus = 'Start';
          cColor  = 'green';
          bLing = 'start';
        }else if(item.status == 2){
          cStatus = 'Completed';
          cColor  = 'cyan';
          bLing = '';
        }else if(item.status == 3){
          cStatus = 'Failed';
          cColor  = 'red';
          bLing = '';
        }else if(item.status == 4){
          cStatus = 'Cancelled';
          cColor  = 'red';
          bLing = '';
        }else if(item.status == 5){
          cStatus = 'On the way'
          cColor  = 'pink';
          bLing = 'on_the_way';
        }else if(item.status == 6){
          cStatus = 'Accepted';
          cColor  = 'green';
          bLing = '';
        }else if(item.status == 7){
          cStatus = 'Rejected';
          cColor  = 'red';
          bLing = '';
        }





    %}

      <tr class="{%=bLing%}">
        <td> {%=item.order_number%} </td>
        <td> {%=item.username%} </td>
        <td> {%=item.catgeory_id%}</td>
        <td> {%=item.service %}</td>
        <td> {%=item.sub_amount%} KWD</td>
        <td ><strong style="color:{%=cColor%}">{%=cStatus%}</strong></td>
        <td> {%=item.created_at%} </td>
        <td> 
            {% if(item.catgeory_id != 'Insurance') { %}
            <i class="fa fa-address-book-o ikn"  data-toggle="tooltip" data-placement="top" title="Re-assignment" onclick="assignServiceProvider(event,{%=item.id%})"></i>
            {% } %}
            <i class="fa fa-eye ikn"  data-toggle="tooltip" data-placement="top" title="Order Details" onclick="orderDetails(event,{%=item.id%})"></i>
            <i class="fa fa-money ikn"  data-toggle="tooltip" data-placement="top" title="Transaction Details" onclick="transactionDetails(event,{%=item.id%})"></i>
            <a href ="{{ url('/booking/invoice/')}}/{%=item.id%}" ><i class="fa fa-download ikn"  data-toggle="tooltip" data-placement="top" title="Invoice"></i></a>

        </td>
      </tr>

    {% } %}



  {% } else{ %}


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
{{-- 
<script type="text/x-tmpl" id="order-details-list-tmpl">
  {% if(o.list.length) { %}
  <div class="modal fade" id="order_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content" style="padding-top:0px;">

              <div class="modal-header">
                  <h5 class="modal-title" id="ModalLabel">Booking Details</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body" style="padding-top:0px;">
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
                      <table class="table">
                          <tr>
                              <th> S No.</th>
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

                     {% if(item.category_type != 1){  var u = 0;   %}



                      <strong>Booking Tracking Details</strong>
                      <hr>
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
                          <td>{%=(u+1)%}</td>
                          <td>{%=orderStatusType(orderItem.status)%}</td>
                          <td> {%=orderItem.created_at%}</td>
                          <td>
                              {% if(orderItem.status == '0'){ %}
                                  <button type="button" class="btn btn-outline-primary btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},1);">Accept</button>
                                  <button type="button" class="btn btn-outline-primary btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},5);">Reject</button>
                              {% } %}

                              {% if(orderItem.status == '1'){ %}
                                  <button type="button" class="btn btn-outline-success btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},2);">On the way</button>
                              {% } %}

                              {% if(orderItem.status == '2'){ %}
                                 <button type="button" class="btn btn-outline-info btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},3);">In Progress</button>
                              {% } %}

                              {% if(orderItem.status == '3'){ %}
                                 <button type="button" class="btn btn-outline-danger btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},4);">Completed</button>
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

                      {%  u++; } %}
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

              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
              </div> -->
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

</script> --}}



<script type="text/x-tmpl" id="order-details-list-tmpl">
    {% if(o.list.length) { %}
    <div class="modal fade" id="order_details_modal_form" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="padding-top:0px;">
 
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Booking Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
 
                <div class="modal-body" style="padding-top:0px;">
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
                                        <span class="float-left"> Current Status </span>
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
                        <table class="table">
                            <tr>
                                <th> S No.</th>
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
 
                       {% if(item.category_type != 0){  var u = 0;   %}
 
 
 
                        <strong>Booking Tracking Details</strong>
                        <hr>
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
                            <td>{%=(u+1)%}</td>
                            <td>{%=orderStatusType(orderItem.status)%}</td>
                            <td> {%=orderItem.created_at%}</td>
                            <td>
                                {% if(orderItem.status == '0'){ %}
                                    <button type="button" class="btn btn-outline-primary btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},1);">Accept</button>
                                    <button type="button" class="btn btn-outline-primary btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},5);">Reject</button>
                                {% } %}
 
                                {% if(orderItem.status == '1'){ %}
                                    <button type="button" class="btn btn-outline-success btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},2);">On the way</button>
                                {% } %}
 
                                {% if(orderItem.status == '2'){ %}
                                   <button type="button" class="btn btn-outline-info btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},3);">In Progress</button>
                                {% } %}
 
                                {% if(orderItem.status == '3'){ %}
                                   <button type="button" class="btn btn-outline-danger btn-icon-text btn-sm" onclick="return changeStatus(event,{%=item.id%},4);">Complete</button>
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
 
                        {%  u++; } %}
                        </table>
                         {% } else { %}
                             
 
                         {% } %}
 
                         {% if(item.category_type == 1){ %}
                                 <strong>Insurance Details</strong>
                                 <hr>
                                 {% for(var k in item.sub_order){
                                     var insuranceItem=item.sub_order[k].insurance;
                                     console.log('insuranceItem',insuranceItem.vehicle.models);
 
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
                                                 <span class="float-left">Vehicle Type</span>
                                                 <span class="float-right text-muted">
                                                     {%=insuranceItem.vehicle.vehilcesdata.name_en%}
                                                 </span>
                                             </p>
                                             <p class="clearfix">
                                                 <span class="float-left">Vehicle Brand</span>
                                                 <span class="float-right text-muted">
                                                     {%=insuranceItem.vehicle.brands.name_en%}
                                                 </span>
                                             </p>
                                             <p class="clearfix">
                                                 <span class="float-left">Vehicle Model</span>
                                                 <span class="float-right text-muted">
                                                     {%=insuranceItem.vehicle.models.name_en%}
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
 
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div> -->
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

@endsection
