@extends('appsp')
@section('content')
    <style>
        .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
            position: fixed;
            width: 77%;
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
            width: 75%;
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

        <h3 class="page-title "> User push notification list
            <a  href="{{ route('sp.pushnotification') }}" class="btn btn-gradient-info" style="float:right !important;" >Add Notification</a>
        </h3>
        <br>
        <table class="table table-hover"  id="users-table">
            <thead>
                <tr>
                {{-- <th><input type="checkbox" name="checkAll" id="checkAll"></th> --}}
                <th>Title</th>
                <th>عنوان</th>
                <th>Type</th>
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
        ajax: '{!! route('sp.push.send.list.view') !!}',
        "columns": [
            // {data: 'checkbox', name: 'checkbox',orderable: false, searchable: false},
            {data: 'title_en', name: 'title_en'},
            {data: 'title_ar', name: 'title_ar'},
            {data: 'send_to',  name: 'send_to'},
            {data: 'status',   name: 'sttaus'},
            {data: 'action',   name: 'action'}
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

function sendNotification(event,id)
{

   swal({
       title: 'Do you want send push notification?',
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
           $('#cover-spin').show(0)

           var $button = $(this);
           var table = $('#users-table').DataTable();
           var couponRequest = fetchRequest('send');
           var formData = new FormData();
           formData.append("_token", "{{ csrf_token() }}");
           formData.append("notification_id", id);
           couponRequest.setBody(formData);
           couponRequest.post().then(function (response) {
               console.log(response)
               if (response.status === 200) {
                  $('#cover-spin').hide(0)

                   table.row( $button.parents('tr') ).remove().draw();
                   showInfoToast('Success','Notification send successfully');


               }else if(response.status === 422){
                   $('#cover-spin').hide(0)

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


</script>
@endsection
