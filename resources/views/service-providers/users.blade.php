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
                  Users List
               </h3>
               {{--
               <h4 class="card-title">Users</h4>
               --}}
               <table class="table "  id="users-table">
                  <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
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
            $(".card").css('margin-top', '-25px');
        } else {
            $(".card").css('margin-top', '0px');
        }
    });
$(function() {

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('sp.users.list') !!}',
        "columns": [
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},

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

</script>
@endsection
