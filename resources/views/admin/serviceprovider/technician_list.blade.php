@extends('app')
@section('content')
<style>
   .jtable-title
   {
   background: #F16522 !important;
   }
   .jtable{
   text-align: center;
   }
   .jtable-page-number-active
   {
   background: #F16522 !important;
   border:none !important;
   }
</style>
<div class="page-header">



   <nav aria-label="breadcrumb">
   </nav>
</div>
@if (session()->has('success'))
<h1>{{ session('success') }}</h1>
@endif
<div class="card">
   <div class="card-body">
   <h3 class="page-title"> Technicians </h3>
      <form id="search" onSubmit="return false">
         <div class="row">
            <div class="col-md-3">
               <input type="text" placeholder="keyword" id="keyword" class="form-control"/>
               <div class="search_bar">
                  <span id="reset_button" class="btn btn-sm"><i class="fa fa-times" aria-hidden="true"></i></span>
                  <button type="submit" id="filter" class="btn btn-gradient-danger btn btn-sm"><i class="fa fa-search"></i></button>
               </div>
            </div>
            <div class="col-md-4"></div>
            <!-- <div class="col-md-3">
               <input type="text" placeholder="Search by name." id="keyword" name="keyword" class="form-control"/>
               </div>
               <div class="col-md-5">
               <div class="col-md-4">
                   <button type="submit" id="filter" class="btn btn-gradient-danger btn btn-sm" ><i class="fa fa-search"></i></button>
                   <span id="reset_button" class="btn btn-sm" ><i class="fa fa-times" style="margin-left:0px;" aria-hidden="true"></i></span>
               </div>
               </div> -->
            <div class="col-md-5 text-right">
               {{-- <a id="add_technician" href="{{route('sp.createTechnician')}}" class="btn btn-gradient-danger btn-md btn-fill">Add +</a> &nbsp;
               &nbsp;&nbsp;                <button class="btn btn-gradient-danger btn-md btn-fill dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >Action</button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" name="Unblock" id="unblock" href="#"><span onclick="updateRecords(event,1);">Unblock</span></a>
                  <a class="dropdown-item" name="Block"  id="block" href="#"><span onclick="updateRecords(event,0);">Block</span></a>
                  <a class="dropdown-item" name="Delete"  id="delete" href="#"><span onclick="updateRecords(event,2);">Delete</span></a>
               </div> --}}
            </div>
         </div>
      </form>
      <br>



      <div class="row" >

         <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="TechnicianTable"></div>
         </div>

      </div>





   </div>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function () {

       //Prepare jTable
   	$('#TechnicianTable').jtable({
   		title: 'Technicians',
          paging: true, //Enable paging
            pageSize: 10, //Set page size (default: 10)
            sorting: true, //Enable sorting
            selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            // selectingCheckboxes: true, //Show checkboxes on first column
            // selectOnRowClick: false, //Enable this to only select using checkboxes
            defaultSorting: 'id DESC', //Set default sorting
            actions: {
   			 listAction: '{{route('admin.sp.technician.list',$user->id)}}',
   			//createAction: 'PersonActionsPagedSorted.php?action=create',
   			//updateAction: 'PersonActionsPagedSorted.php?action=update',
   			//deleteAction: 'PersonActionsPagedSorted.php?action=delete'
   		},
   		fields: {
           id: {
                     key: true,
                     create: false,
                     edit: false,
                     list: false
                   },
   			full_name_en: {
   				title: 'Name',
             display:function(data){
              return data.record.full_name_en;
             },
   			},
           email: {
             title: 'Email',
             display:function(data){
              return data.record.email;
             },
           },
           mobile_number: {
             title: 'Phone no',
             display:function(data){
              return data.record.country_code+" "+data.record.mobile_number;
             },
           },
           password_txt: {
             title: 'Password',
             display:function(data){
              return (data.record.password_txt !=null) ? data.record.password_txt : '--'
             },
           },
           is_active: {
             title: 'Status',
             display:function(data){
               if(data.record.is_active ==1){
                 return "Unblock";
               }else if(data.record.is_active ==0)
               return 'Block';
             },
           },
   			// action: {
   			// 	title: 'Action',
            //  sorting: false,
            //  width: '11%',
            //  display:function(data){
            //    if(data.record.is_active ==1){
            //      var vartest= "<a href='javascript:void(0)' data-id="+data.record.id+" data-id1='"+data.record.is_active+"' data-id2='"+data.record.full_name_en+"' data-id3='1' class='btn btn-sm btn-gradient-success activestatus' title='Delete'><i class='fa fa-unlock-alt'></i></a>";
            //    }else if(data.record.is_active ==0){
            //    var vartest= "<a href='javascript:void(0)' data-id="+data.record.id+" data-id1='"+data.record.is_active+"' data-id2='"+data.record.full_name_en+"' data-id3='0' class='btn btn-sm btn-gradient-danger activestatus' title='Delete'><i class='fa fa-lock'></i></a>";
            //  }
            //    return vartest +" <a href='{{route('sp.technicianDetails')}}/"+data.record.id+"' class='btn btn-sm btn-gradient-primary' title='view'><i class='fa fa-eye'></i></a> <a href='{{route('sp.technicianUpadte')}}/"+data.record.id+"' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a> <a href='{{route('tech.techtimeslot')}}/"+data.record.id+"' class='btn btn-sm btn-gradient-info' title='Time Slot'><i class='fa fa-clock-o' aria-hidden='true'></i></a>";
            //  }
   			// },

         }
   	});


   	//Load person list from server
   	$('#TechnicianTable').jtable('load');
       $('#filter').click(function (e) {
           e.preventDefault();
           $('#TechnicianTable').jtable('load', {
               keyword: $('#keyword').val(),
           });
       });
         $('#reset_button').click(function (e) {
          $('#TechnicianTable').jtable('load');
          $('#search')[0].reset();
      });

   });

</script>
<script>
   // this function is used to change active status of category

   $(document).on("click",".activestatus",function(){
      let id = $(this).data('id');
      let activeStatus = $(this).data('id1');
      let name = $(this).data('id2');
      let type = $(this).data('id3');
      if(activeStatus == 1){
       // message = "You want to inactive service provider";
        message = "You want to block technician "+name+"!";
      }else{
        //message = "You want to active service provider";
        message = "You want to unblock technician "+name+"!";
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
          url: "{{route('tec.statusUpdate')}}",
          method: 'POST',
          data:{id:id,activeStatus:activeStatus},
          type:"json",
          success: function(response) {
             if(response.status){
                   $('#TechnicianTable').jtable('load');
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
</script>
<script>
   $(document).on("click",".deletetechnician",function(){
   let id = $(this).data('id');
   let name = $(this).data('id1');
   let type = 1;
   swal({
   title: 'Are you sure?',
   text: "You want to delete the technician "+name+"!",
   //text: "You want to delete the technician",
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
      url: "{{route('tec.delete')}}",
      method: 'POST',
      data:{id:id},
      type:"json",
      success: function(response) {
         if(response.status == '1'){
           	$('#TechnicianTable').jtable('load');
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
</script>
<script>
   // gets  comma seperated ids on click of action >> block.......
   function updateRecords(event,status)
     {
       var values = new Array();
         $.each($(".jtable-row-selected"),
               function () {
                     values.push($(this).attr('data-record-key'));
               });
         var ids = values.join(",");
         //console.log(ids);
   if(ids == '' ){
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
             data:{id:ids,status:status},
             type:"json",
             success: function(response) {
                if(response.status==1){
                    $('#TechnicianTable').jtable('load');
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
