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

          <h3 class="page-title "> User wallet histories  
           
          </h3> 

          <table class="table table-hover"  id="users-table">
              <thead> 
                 <tr>
                    <th>History-ID</th>
                    <th>Description (English)</th>
                    <th>Description (Arabic)</th>
                    <th>Type</th>
                    <th>Transaction Amount</th>
                    <th>Closing Amount</th>
                    <th>Created At</th>
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

$(function() {
       
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('user.transaction.histories',$user->id) !!}',
        "columns": [
            
            {data: 'id', name: 'id'},
            {data: 'description',    name: 'description'},
            {data: 'description_ar', name: 'description_ar'},
            {data: 'status', name: 'status'},
            {data: 'transaction_amount', name: 'transaction_amount'},
            {data: 'closing_amount', name: 'closing_amount'},
            {data: 'created_at', name: 'created_at'}
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
