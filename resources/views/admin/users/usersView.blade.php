@extends('app')
@section('content')

        <div class="page-header">
          <!-- <h3 class="page-title"> Users  </h3>  -->
          {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Tables</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data table</li>
            </ol>
          </nav> --}} 
        </div>
        @if (session()->has('success'))
            <h1>{{ session('success') }}</h1>
        @endif
        <div class="card">
        
          <div class="card-body">

          <h3 class="page-title "> Users    </h3> 
            <div class="row"> 
              <div class="col-md-8"></div>
              <div class="col-md-2">
                <button style="float:right;" class="btn btn-gradient-danger btn-md btn-fill dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                  Action
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" name="Unblock" id="unblock" href="#"><span onclick="updateRecords(event,1);">Unblock</span></a>
                    <a class="dropdown-item" name="Block"  id="block" href="#"><span onclick="updateRecords(event,0);">Block</span></a>
                </div>
              </div>
              <div class="col-md-2">
                <button type="button" class="btn btn-gradient-danger btn-rounded btn-fw" onclick="exportReport(event);">Export</button>
              </div>
            </div>
            <br>
        

          <table class="table table-hover"  id="users-table">
              <thead> 
                 <tr>
                    <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Wallet Amount</th>
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
        <div class="modal fade" id="user_cashback_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel-2">Add Cashback Amount</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <form id="user_cashback_add" method="post" onsubmit="return addCashbackToUser(event)">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Amount (In KWD)</label>
                    <input type="text" class="form-control" min="0" id="cashback_amount" name="cashback_amount" required>
                </div>
            </div>
            <div class="modal-footer">
              <input class="btn btn-success" type="submit" value="Submit" id="btn-submit">
              <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{asset('js/fetch.js')}}"></script>
<script src="{{asset('js/tmpl.min.js')}}"></script>
<script>

$(function() {
       
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('user.getuserlist') !!}',
        "columns": [
            {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
            {data: 'full_name_en', name: 'full_name_en'},
            {data: 'email',    name: 'email'},
            {data: 'mobile_number', name: 'mobile_number'},
            {data: 'amount', name: 'amount'},
            {data: 'is_active', name: 'is_active'},
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

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

function exportReport(event)
{
    var vendorIds = [];

    $('input[name="someCheckbox"]:checked').each(function() {
        vendorIds.push( this.id);
    });


    var userReportRequest = fetchRequest('/admin/user/export');

    var formData = new FormData();
    formData.append("_token", "{{ csrf_token() }}");
    userReportRequest.setBody(formData);
    userReportRequest.post().then(function (response) {
        console.log(response);
        window.location = "{{url('/')}}/storage/user_reports.xlsx";
        if (response.status === 200) {
        // getCategory(page);

        }else if(response.status === 422){
            response.json().then((errors) => {
                console.log('Error');
            });
        }
    });
}

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
    var $button = $(this);
    var table = $('#users-table').DataTable(); 
    message = "Do you want to change the status for selected user."
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
            url: "{{route('user.bulkstatusUpdate')}}",
            method: 'POST',
            data:{id:vendorIds,status:status},
            type:"json",
            success: function(response) {
              if(response.status==1){
                table.row( $button.parents('tr') ).remove().draw();
              }
              if(response.status==0){
                swal({
                text: 'You dont have permission',
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

function changeStatus(event,id,status)
{
   
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
           var couponRequest = fetchRequest('/admin/user/userUpdate');
           var formData = new FormData();
           formData.append("_token", "{{ csrf_token() }}");
           formData.append("status", status);
           formData.append("user_id", id);
           couponRequest.setBody(formData);
           couponRequest.post().then(function (response) {
               console.log(response)
               if (response.status === 200) {
                   table.row( $button.parents('tr') ).remove().draw();
                   showInfoToast('Success','User status successfully changed');
               
                   
               }else if(response.status === 422){
                   response.json().then((errors) => {
                    showDangerToast('Error','You dont have a permission');
                   });
               }
           });
       } else {
           swal("change status operation failed!");
       }
   });

}

function deleteUser(event,id)
{

  swal({
      title: 'Do you want permanently delete this item?',
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
          var delVendorRequest = fetchRequest('/admin/user/delete/'+id);
          var formData = new FormData();
          formData.append("_token", "{{ csrf_token() }}");
          delVendorRequest.setBody(formData);
          delVendorRequest.post().then(function (response) {
              if (response.status === 200) {
                  table.row( $button.parents('tr') ).remove().draw();
                  showInfoToast('Success','User successfully deleted');
          
              }else if(response.status === 422){
                  response.json().then((errors) => {
                      console.log('Error');
                  });
              }
          });

      } else {
          swal("Delete operation failed!");
      }
  });

}
function addCashback(event,id)
{
    userId = id;
    $('#user_cashback_modal').modal('show');

}

function addCashbackToUser(event)
{
    event.preventDefault();

    $('#user_cashback_add').parsley().validate();

    if ($('#user_cashback_add').parsley().isValid()){

        var form = document.getElementById('user_cashback_add');

        var addCashbackRequest = fetchRequest('/admin/user/add/cashback');

        var formData = new FormData(form);

        formData.append("user_id", userId);

        addCashbackRequest.setBody(formData);

        addCashbackRequest.post().then(function (response) {

            if (response.status === 200) {
                response.json().then((errors) => {
                    $('#user_cashback_modal').modal('toggle');
                    showInfoToast('Success','Cashback amount successfully added with wallet');   
                    setTimeout(function(){
                        location.reload();
                    },2000)                
                });
                
            }else if(response.status === 422){
                response.json().then((errors) => {
                    console.log('Error');
                });
            }
        });

         return true;

    }else{
        return false;
    }
}

$(function () {
     $(document).on("click",".deleteuser",function()
     {
        let id = $(this).data('id');
        let name = $(this).data('id1');
        let type = 1;
        swal({
        title: 'Are you sure?',
        text: "You want to delete "+name+'!',
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
            url: "{{route('user.userUpdate')}}",
            method: 'POST',
            data:{id:id,type:type},
            type:"json",
            success: function(response) {
               if(response.status == 'success'){
                 	$('#usersTable').jtable('load');
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
  });


</script>
@endsection
