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
          <!-- <h3 class="page-title"> Services  </h3> -->
           <nav aria-label="breadcrumb">

          </nav>
          </div>
        @if (session()->has('success'))
            <h1>{{ session('success') }}</h1>
        @endif
        <div class="card">
          <div class="card-body">
          <h3 class="page-title"> Services  </h3> 
            <form id="search" onSubmit="return false">
            <div class="row">


            <div class="col-md-3">
                  <input type="text" placeholder="keyword" id="keyword" class="form-control"/>
                  <div class="search_bar">
                    <span id="reset_button" class="btn btn-sm"><i class="fa fa-times" aria-hidden="true"></i></span>
                    <button type="submit" id="filter" class="btn btn-gradient-danger btn btn-sm"><i class="fa fa-search"></i></button>
                  </div>
              </div>
<div class="col-md-5"></div>
              
              <!-- <div class="col-md-3">
                  <input type="text" placeholder="Search by name." id="keyword" name="keyword" class="form-control"/>
              </div>
 -->


              <!-- <div class="col-md-4">
                  <button type="submit" id="filter" class="btn btn-gradient-danger btn btn-sm" style=   "margin-top: -1px; margin-left: -15px; height: 37px;"><i class="fa fa-search"></i></button>
                  <span id="reset_button" class="btn btn-sm" style="margin-top: -10px; margin-left: -112px; height: 26px;"><i class="fa fa-times" style="margin-left:0px;" aria-hidden="true"></i></span>
              </div> -->


            <div class="col-md-4  text-right">
                
            </div>
          </div>
          </form>
          <br>
            <div class="row">

              <div class="col-12">
                <div id="serviceTable"></div>
              </div>
            </div>
          </div>
        </div>

@endsection
@section('scripts')
  <script>

		    $(document).ready(function () {
        //Prepare jTable
			  $('#serviceTable').jtable({
				title: 'Services',
			  paging: true, //Enable paging
        pageSize: 10, //Set page size (default: 10)
        sorting: true, //Enable sorting
        selecting: true, //Enable selecting
        multiselect: true, //Allow multiple selecting
        // selectingCheckboxes: true, //Show checkboxes on first column
        // selectOnRowClick: false, //Enable this to only select using checkboxes
        defaultSorting: 'id DESC', //Set default sorting
        actions: {
					listAction: '{{route('admin.sp.service.list',$user->id)}}',
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
					  name_en: {
						title: 'Name',
            display:function(data){
              var name = data.record.name_en ;
                  return  name;

            }, 
					},
          category:{
            title: 'Category',
            display:function(data){
              if (data.record.category.name_en != null) {
                return  data.record.category.name_en;
              }
            },
          },
          subcategory:{
            title: 'Sub-category',
            display:function(data){
              if (data.record.subcategory != null) {
                return  data.record.subcategory.name_en;
              }
              else{
                return 'N/A';
              }
            },
          },
          amoumt:{
            title:'Amount / Premium(%)',
            display:function(data){
              var amount = (data.record.type == 2) ? data.record.insurance_percentage+'%' :  data.record.amount+' KWD';
              return amount;
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
        //   action: {
		// 				title: 'Action',
        //     sorting: false,
        //     display:function(data){
        //       if(data.record.is_active ==1){
        //         var vartest= "<a href='javascript:void(0)' data-id="+data.record.id+" data-id1='"+data.record.is_active+"' data-id2='"+data.record.name_en+"' data-id3='1' class='btn btn-sm btn-gradient-success activestatus' title='Delete'><i class='fa fa-unlock-alt'></i></a>";
        //       }else if(data.record.is_active ==0){
        //       var vartest= "<a href='javascript:void(0)' data-id="+data.record.id+" data-id1='"+data.record.is_active+"' data-id2='"+data.record.name_en+"' data-id3='0' class='btn btn-sm btn-gradient-danger activestatus' title='Delete'><i class='fa fa-lock'></i></a>";
        //     }
        //       return vartest+"<a href='servicedetails/"+data.record.id+"' class='btn btn-sm btn-gradient-primary' title='view'><i class='fa fa-eye'></i></a> <a href='updateservice/"+data.record.id+"' class='btn btn-sm btn-gradient-info' title='Edit'><i class='fa fa-pencil'></i></a>";
        //     }
		// 			},
        }
			});


			//Load person list from server
			$('#serviceTable').jtable('load');
      $('#filter').click(function (e) {
          e.preventDefault();
          $('#serviceTable').jtable('load', {
              keyword: $('#keyword').val(),
          });
      });
        $('#reset_button').click(function (e) {
         $('#serviceTable').jtable('load');
         $('#search')[0].reset();
     });

		});

       
   $(document).on("click",".activestatus",function(){
      let id = $(this).data('id');
      let activeStatus = $(this).data('id1');
      let name = $(this).data('id2');
      let type = $(this).data('id3');
      if(activeStatus == 1){
       // message = "You want to inactive service provider";
        message = "You want to block service :  "+name+"!";
      }else{
        //message = "You want to active service provider";
        message = "You want to unblock service :  "+name+"!";
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
          url: "{{route('sp.service.statusUpdate')}}",
          method: 'POST',
          data:{id:id,activeStatus:activeStatus},
          type:"json",
          success: function(response) {
             if(response.status){
                   $('#serviceTable').jtable('load');
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

@endsection
