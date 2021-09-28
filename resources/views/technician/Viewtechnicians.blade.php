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
                <h3 class="page-title"> Technicians </h3>
            </div>
            <div class="col-md-4"></div>

            <div class="col-md-5 text-right">
               <a id="add_technician" href="{{route('sp.createTechnician')}}" class="btn btn-gradient-danger btn-md btn-fill">Add +</a> &nbsp;
               &nbsp;&nbsp;                <button class="btn btn-gradient-danger btn-md btn-fill dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >Action</button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" name="Unblock" id="unblock" href="#"><span onclick="updateRecords(event,1);">Unblock</span></a>
                  <a class="dropdown-item" name="Block"  id="block" href="#"><span onclick="updateRecords(event,0);">Block</span></a>
               </div>
            </div>
         </div>
          </div>
      </form>
      <br>

      <table class="table table-hover"  id="users-table">
          <thead>
             <tr>
                <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone no</th>
                <th>Password</th>
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
        ajax: '{!! route('sp.technicianList') !!}',
        "columns": [
            {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
            {data: 'full_name_en', name: 'full_name_en'},
            {data: 'email',    name: 'email'},
            {data: 'mobile_number', name: 'mobile_number'},
            {data: 'password_txt', name: 'password_txt'},
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
           var couponRequest = fetchRequest('/serviceprovider/technician/statusupdate');
           var formData = new FormData();
           formData.append("_token", "{{ csrf_token() }}");
           formData.append("status", status);
           formData.append("technician_id", id);
           couponRequest.setBody(formData);
           couponRequest.post().then(function (response) {
               if (response.status === 200) {
                   table.row( $button.parents('tr') ).remove().draw();
                   showInfoToast('Success','User status successfully changed');


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


$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});


function updateRecords(event,status)
{
  var vendorIds = [];
  var $button = $(this);
  var table = $('#users-table').DataTable();

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

    if(status ==0){
        message = "Do you want to change the status for Users .";
    }else if(status ==1){
      message = "Do you want to change the status for Users .";
    }else if(status ==2){
      message = "Do you want to delete this user";
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
             url: "{{route('tech.bulkstatusUpdate')}}",
             method: 'POST',
             data:{id:vendorIds,status:status},
             type:"json",
             success: function(response) {
                if(response.status==1){
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
     });
  }
}

function changePasswordVisibility(id){

  var x = document.getElementById("user-"+id);

  x.innerText = $('#user-'+id).data('text');

}

</script>
@endsection
