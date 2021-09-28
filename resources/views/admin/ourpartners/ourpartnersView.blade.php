@extends('app')
@section('content')

<div class="page-header">
  <nav aria-label="breadcrumb"></nav>
</div>

@if (session()->has('success'))
  <h1>{{ session('success') }}</h1>
@endif
<style>
    .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
        position: fixed;
        width: 76%;
        z-index: 99;
        height: 66px;
        padding-top: 60px;
        padding-bottom: 43px;
        background: #fff;
        margin-bottom: 58px !important;
    }
    .fixed_top_settings {
        background: white;
        height:60px;
        width: 74%;
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

<div class="card">
   <div class="card-body">
      <form id="search" onSubmit="return false" method="POST">
          <div class="fixed_top_settings">
         <div class="row">
            <div class="col-md-3">
                <h3 class="page-title"> Our Partners  </h3>
            </div>
            <div class="col-md-5"></div>

            <div class="col-md-4 col-sm-2">
               <button class="btn btn-gradient-danger btn-md btn-fill dropdown-toggle "  style="position: relative; right: -90px;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
               Action
               </button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" name="Unblock" id="unblock" href="#"><span onclick="updateRecords(event,1);">Unblock</span></a>
                  <a class="dropdown-item" name="Block"  id="block" href="#"><span onclick="updateRecords(event,0);">Block</span></a>
               </div>
               <a id="add_ourpartner " href="{{route('op.createPartner')}}" class="btn btn-gradient-danger btn-md btn-fill pull-right">Add +</a>
            </div>
         </div>
          </div>
      </form>
      <div>
         <br>
         <div>
            @if (Session::has('message'))
            <div class="alert alert-success alert-block">
               <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{Session::get('message')}}</strong>
            </div>
            @endif
         </div>
      </div>
      <br>
      <table class="table table-hover "  id="users-table">
        <thead>
            <tr>
              <th><input type="checkbox" name="checkAll" id="checkAll"></th>
              <th>Name</th>
              <th>اسم</th>
              <th>Image</th>
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
@endsection
@section('scripts')
<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".fixed_top_settings").css('margin-top', '-4px');
        } else {
            $(".fixed_top_settings").css('margin-top', '0px');
        }
    });
$(function() {

 $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('op.getpartnerslist') !!}',
    "columns": [
       {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
       {data: 'partnername_en', name: 'partnername_en'},
       {data: 'partnername_ar', name: 'partnername_ar'},
       {data: 'banner_image', name: 'banner_image'},
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

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

});

   $(function () {
    $(document).on("click",".deletepartner",function(){
       let id = $(this).data('id');
       let name = $(this).data('id1');
       let type = 1;
       swal({
       title: 'Are you sure?',
       text: "You want to delete the partner "+name+"!",
       //text: "You want to delete the partner",
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
           className: "btn btn-primary",
           closeModal: true,
         },
         confirm: {
           text: "OK",
           value: true,
           visible: true,
           className: "btn btn-danger",
           closeModal: true,
         }
       }

     }).then(function(isConfirm){
       if(isConfirm){
         $.ajax({
           url: "{{route('op.ourpartnerDelete')}}",
           method: 'POST',
           data:{id:id},
           type:"json",
           success: function(response) {
              if(response.status == '1'){
                var $button = $(this);
                var table = $('#users-table').DataTable();
                table.row( $button.parents('tr') ).remove().draw();
              }
           },
           error:function(){
             swal({
               text: 'Something went wrong',
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
     })

     });

     // this function is used to change active status of category
     $(document).on("click",".activstatus",function(){
        let id = $(this).data('id');
        let activeStatus = $(this).data('id1');
        let name = $(this).data('id2');
        let type = 2;
        if(activeStatus == 1){
         // message = "You want to inactive category";
          message = "You want to inactive partner "+name+"!";
        }else{
          //message = "You want to active category";
          message = "You want to active partner "+name+"!";
        }
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
            url: "{{route('banner.bannerUpdate')}}",
            method: 'POST',
            data:{id:id,type:type,activeStatus:activeStatus},
            type:"json",
            success: function(response) {
               if(response.status == 'success'){
                 	$('#partnerTable').jtable('load');
               }
            },
            error:function(){
              swal({
                text: 'Something went wrong',
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
      })
      });
   })
</script>
<script>
   // gets  comma seperated ids on click of action >> block.......
  function updateRecords(event,status)
  {
    var vendorIds = [];

    $('input[name="someCheckbox"]:checked').each(function() {
        vendorIds.push( this.id);
    });

    if(vendorIds == '' ){

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

      message = "Do you want to change the status for Users ."

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
             url: "{{route('op.bulkstatusUpdate')}}",
             method: 'POST',
             data:{id:vendorIds,status:status},
             type:"json",
             success: function(response) {
                if(response.status==1){
                    $('#partnerTable').jtable('load');
                }
             },
             error:function(){
               swal({
                 text: 'Something went wrong',
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

</script>
@endsection
