@extends('appsp')
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
        width: 77%;
        z-index: 999;
        height: 66px;
        padding-top: 60px;
        padding-bottom: 43px;
        background: #fff;
        margin-bottom: 58px !important;
    }
    .page-title {
        background: white;
        height:50px;
        width: 75%;
        z-index: 9999;
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
            <h3 class="page-title"> Services <a id="add_banner" style="float:right" href="{{route('sp.createservice')}}" class="btn btn-gradient-danger btn-md btn-fill">Add +</a> </h3>


          <br>
            <table class="table table-hover"  id="users-table">
                <thead>
                   <tr>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Sub-category</th>
                      <th>Amount/Premium(%)</th>
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
      ajax: '{!! route('sp.servicelisting') !!}',
      "columns": [
          {data: 'name_en', name: 'name_en'},
          {data: 'category',    name: 'category'},
          {data: 'sub_category', name: 'sub_category'},
          {data: 'amount', name: 'amount'},
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
           var couponRequest = fetchRequest('/serviceprovider/service/status');
           var formData = new FormData();
           formData.append("_token", "{{ csrf_token() }}");
           formData.append("status", status);
           formData.append("service_id", id);
           couponRequest.setBody(formData);
           couponRequest.post().then(function (response) {
               if (response.status === 200) {
                   table.row( $button.parents('tr') ).remove().draw();
                   showInfoToast('Success','Service status successfully changed');


               }else if(response.status === 422){
                   response.json().then((errors) => {
                       console.log('Error');
                   });
               }
           });
       } else {
           swal("change status operation failed!");
       }
   });

}

</script>
@endsection
