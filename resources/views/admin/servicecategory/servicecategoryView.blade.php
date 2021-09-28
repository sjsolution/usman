@extends('app')
@section('content')

        {{-- <div class="page-header"> --}}
          {{-- <h3 class="page-title"> Category  </h3> --}}
          {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Tables</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data table</li>
            </ol>
          </nav> --}}
        {{-- </div> --}}
        @if (session()->has('success'))
            <h1>{{ session('success') }}</h1>
        @endif
        <div class="card">
          <div class="card-body">
            <form id="search" onSubmit="return false">
            <div class="row">
              <div class="col-md-4">
                  <input type="text" placeholder="Category keyword" id="keyword" name="keyword" class="form-control"/>


              </div>


             <div class="col-md-4">
                <button type="submit" id="filter" class="btn btn-info btn-sm btn-fill">Search</button>
                <button id="reset_button" class="btn btn-info btn-sm btn-fill">Reset</button>
              </div>


            </div>
          </form>
          <br>
            <div class="row">
              <div class="col-12">
                <div id="categoryTable"></div>
              </div>
            </div>
          </div>
        </div>

@endsection
@section('scripts')

  <script>
		$(document).ready(function () {
		    //Prepare jTable
			$('#categoryTable').jtable({
				title: 'Categories',
			  paging: true, //Enable paging
        pageSize: 10, //Set page size (default: 10)
        sorting: true, //Enable sorting
        defaultSorting: 'id DESC', //Set default sorting
        actions: {
					listAction: '{{route('category.getcategorylist')}}',
					//createAction: 'PersonActionsPagedSorted.php?action=create',
					//updateAction: 'PersonActionsPagedSorted.php?action=update',
					//deleteAction: 'PersonActionsPagedSorted.php?action=delete'
				},
				fields: {
					name_en: {
						title: 'English Name',
            display:function(data){
              var name = data.record.name_en;
              if (name.length > 20) {
                  return  name.substring(0, 20) + " ...";
              }else{
                  return  name;
              }
            },
					},
          name_ar: {
            title: 'Arabic Name',
            display:function(data){
              var name1 = data.record.name_ar;
              if (name1.length > 20) {
                  return  name1.substring(0, 20) + " ...";
              }else{
                  return  name1;
              }
            },
          },
					action: {
						title: 'Action',
            sorting: false,
            display:function(data){

              return "<a href='category/categoryDetails/"+data.record.id+"' class='btn btn-sm btn-primary' title='view'><i class='fa fa-eye'></i></a> <a href='category/updateCategory/"+data.record.id+"' class='btn btn-sm btn-info' title='Edit'><i class='fa fa-pencil'></i></a> <a href='javascript:void(0)' data-id="+data.record.id+" data-id1='"+data.record.name_en+"'class='btn btn-sm btn-danger deletecategory' title='Delete'><i class='fa fa-trash'></i></a>";
            }
					},
				}
			});
			//Load person list from server
			$('#categoryTable').jtable('load');
      $('#filter').click(function (e) {
          e.preventDefault();
          $('#categoryTable').jtable('load', {
              keyword: $('#keyword').val(),
          });
      });
        $('#reset_button').click(function (e) {
         $('#categoryTable').jtable('load');
         $('#search')[0].reset();
     });

		});

	</script>
  <script>
    $(function () {
     $(document).on("click",".deletecategory",function(){
        let id = $(this).data('id');
        let name = $(this).data('id1');
        let type = 1;
        swal({
        title: 'Are you sure?',
        text: "You want to delete the category "+name+"!",
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
            url: "{{route('category.categoryUpdate')}}",
            method: 'POST',
            data:{id:id,type:type},
            type:"json",
            success: function(response) {
               if(response.status == 'success'){
                 	$('#categoryTable').jtable('load');
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
           message = "You want to inactive category "+name+"!";
         }else{
           //message = "You want to active category";
           message = "You want to active category "+name+"!";
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
             url: "{{route('category.categoryUpdate')}}",
             method: 'POST',
             data:{id:id,type:type,activeStatus:activeStatus},
             type:"json",
             success: function(response) {
                if(response.status == 'success'){
                  	$('#categoryTable').jtable('load');
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
