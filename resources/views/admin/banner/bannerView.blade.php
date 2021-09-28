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
    .banner_row {
        background: white;
        height:25px;
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
        margin-top: 120px !important;
    }
</style>
<div class="card">
  <div class="card-body">

    <form id="search" onSubmit="return false">
        <div class="banner_row">
      <div class="row">
        <div class="col-md-3">
            <h3 class="page-title"> Banners  </h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-3 pull-right">
          <a id="add_banner" href="{{route('banner.createBanner')}}" class="btn btn-gradient-danger btn-md btn-fill pull-right">Add +</a>
        </div>
          <div class="col-md-2"></div>
      </div>
        </div>
    </form>
    <br>
    <table class="table table-hover "  id="users-table">
      <thead>
          <tr>
            <th>Banner Name</th>
            <th>اسم البانر</th>
            <th>Image</th>
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
            $(".banner_row").css('margin-top', '-4px');
        } else {
            $(".banner_row").css('margin-top', '0px');
        }
    });
$(function() {

 $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('banner.getbannerlist') !!}',
    "columns": [
       {data: 'title_en', name: 'title_en'},
       {data: 'title_ar', name: 'title_ar'},
       {data: 'banner_image', name: 'banner_image'},
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

$(function () {
  $(document).on("click",".deletebannner",function(){
      let id = $(this).data('id');
      let name = $(this).data('id1');
      let type = 1;
      swal({
      title: 'Are you sure?',
      text: "You want to delete the banner "+name+"!",
      //text: "You want to delete the Category",
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
          url: "{{route('banner.bannerUpdate')}}",
          method: 'POST',
          data:{id:id,type:type},
          type:"json",
          success: function(response) {
              if(response.status == 'success'){
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
           message = "You want to inactive banner "+name+"!";
         }else{
           //message = "You want to active category";
           message = "You want to active banner "+name+"!";
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
                  	$('#bannerTable').jtable('load');
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
@endsection
