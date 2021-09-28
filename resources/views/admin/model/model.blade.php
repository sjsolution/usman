@extends('app')
@section('content')

<div class="page-header">
  <nav aria-label="breadcrumb"></nav>
</div>

@if (session()->has('success'))
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>
    <h6>{{ session('success') }}</h6>
  </strong>
</div>
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

    <form id="search" onSubmit="return false">
        <div class="fixed_top_settings">
    <div class="row">
      <div class="col-md-3">
          <h3 class="page-title">Vehicle brand model list</h3>
      </div>
      <div class="col-md-5"></div>
      <div class="col-md-4 text-right">
        <a id="add_vehicle" href="{{route('vehicle.createmodel')}}" class="btn btn-gradient-danger btn-md btn-fill">Add +</a>
    </div>
  </div>
        </div>
  </form>
  <br>
  <table class="table table-hover"  id="users-table">
    <thead>
        <tr>
          <th>Vehicle Name</th>
          <th>Name</th>
          <th>اسم</th>
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
      ajax: '{!! route('vehicle.model.modellist') !!}',
      "columns": [
          {data: 'vehicle_brand', name: 'vehicle_brand'},
          {data: 'name_en', name: 'name_en'},
          {data: 'name_ar', name: 'name_ar'},
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

$(function () {
      $(document).on("click",".deletevehicle",function(){
        let id = $(this).data('id');
        let name = $(this).data('id1');
        swal({
        title: 'Are you sure?',
        text: "You want to delete the brand "+name+"!",
        //text: "You want to delete the Vehicle",
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
            url: "{{route('vehicle.deletemodel')}}",
            method: 'POST',
            data:{id:id},
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
})
</script>
@endsection
