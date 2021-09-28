@extends('app')
@section('content')
    <style>
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
        .fixed_top_settings {
            background: white;
            height:60px;
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
<div class="page-header"></div>
<div class="card">
    <div class="card-body">
        <div class="fixed_top_settings">
        <h3 class="page-title "> Admin Roles Management
            <a  href="{{ route('role.list') }}" class="btn btn-gradient-info" style="float:right;" > Role Settings</a>
            <a  href="{{ route('role.management.add.user') }}" class="btn btn-gradient-primary" style="float: right;
            margin-right: 10px;">User invitations</a>

            {{-- <button style="float:right;" class="btn btn-gradient-danger btn-md btn-fill dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                Action
            </button> --}}

            {{-- <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" name="Unblock" id="unblock" href="#"><span onclick="updateRecords(event,1);">Unblock</span></a>
                <a class="dropdown-item" name="Block"  id="block" href="#"><span onclick="updateRecords(event,0);">Block</span></a>
            </div> --}}

        </h3>
        </div>
        <br>

        <table class="table table-hover"  id="users-table">
            <thead>
                <tr>
                    {{-- <th><input type="checkbox" name="checkAll" id="checkAll"></th> --}}
                    <th>Name</th>
                    <th>Email</th>
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
            $(".fixed_top_settings").css('margin-top', '-4px');
        } else {
            $(".fixed_top_settings").css('margin-top', '0px');
        }
    });
$(function() {

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('user.role.listing') !!}',
        "columns": [
            // {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email',    name: 'email'},
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

// function updateRecords(event,status)
// {

//   var vendorIds = [];

//   $('input[name="someCheckbox"]:checked').each(function() {
//       vendorIds.push( this.id);
//   });

//   if(vendorIds == '' ){

//     swal({
//         text: 'Please select any row',
//         button: {
//           text: "OK",
//           value: true,
//           visible: true,
//           className: "btn btn-primary"
//         }
//     });

//   }else{
//     var $button = $(this);
//     var table = $('#users-table').DataTable();
//     message = "Do you want to change the status for selected user."
//     swal({
//         title: 'Are you sure?',
//         text: message,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3f51b5',
//         cancelButtonColor: '#ff4081',
//         confirmButtonText: 'Great ',
//         buttons: {
//           cancel: {
//             text: "Cancel",
//             value: null,
//             visible: true,
//             className: "btn btn-warning",
//             closeModal: true,
//           },
//           confirm: {
//             text: "OK",
//             value: true,
//             visible: true,
//             className: "btn btn-info",
//             closeModal: true,
//           }
//         }
//       }).then(function(isConfirm){
//         if(isConfirm){
//           $.ajax({
//             url: "{{route('user.bulkstatusUpdate')}}",
//             method: 'POST',
//             data:{id:vendorIds,status:status},
//             type:"json",
//             success: function(response) {
//               if(response.status==1){
//                 table.row( $button.parents('tr') ).remove().draw();
//               }
//             },
//             error:function(){
//               swal({
//                 text: 'Something went wrong',
//                 button: {
//                   text: "OK",
//                   value: true,
//                   visible: true,
//                   className: "btn btn-primary"
//                 }
//               })
//             }
//         });
//         }
//     });
//   }

// }

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
           var couponRequest = fetchRequest('user/status');
           var formData = new FormData();
           formData.append("_token", "{{ csrf_token() }}");
           formData.append("status", status);
           formData.append("user_id", id);
           couponRequest.setBody(formData);
           couponRequest.post().then(function (response) {
               if (response.status === 200) {
                   table.row( $button.parents('tr') ).remove().draw();
                   showInfoToast('Success','Admin status successfully changed');


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
