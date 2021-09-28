@extends('app')
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
    .labels_row {
        background: white;
        height:25px;
        width: 76%;
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



      <div class="row">
         <div class="col-md-12">

            <form id="formdata" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
               @csrf
                <div class="labels_row">
               <div class="row">
                  <div class="col-md-3">
                      <h3 class="page-title"> Labels  </h3>
                  </div>
                 
                  <div class="col-md-3 col-sm-4 col-xs-12">
                     <div class="form-group">
                        <input type="file" name="file" class="file-upload-default" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        <div class="input-group col-xs-12">
                           <input type="text" class="form-control file-upload-info h-42" disabled="" placeholder="Upload Excel or CSV">
                           <span class="input-group-append">
                           <button class="file-upload-browse btn btn-md-3 btn-gradient-danger" type="button">Upload</button>
                           </span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-2 col-sm-4 col-xs-12">
                     <button type="submit" class="btn btn-gradient-danger btn-md-2">Import Label</button>
                  </div>
                  <div class="col-md-2 col-sm-4 col-xs-12">
                     <a class="btn btn-gradient-primary" href="{{ route('export') }}">Export Label</a>
                  </div>
                  <div class="col-md-2 col-sm-4 col-xs-12">
                     <a class="btn btn-gradient-info" href="{{ route('label.create') }}">Add Label</a>
                  </div>
               </div>
                </div>
            </form>
         </div>
      </div>
      <br>
      <table class="table table-hover "  id="users-table">
         <thead>
            <tr>
               <th>Key</th>
               <th>Name</th>
               <th>اسم</th>
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

<script type="text/javascript">
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".labels_row").css('margin-top', '-4px');
        } else {
            $(".labels_row").css('margin-top', '0px');
        }
    });
$(function() {

   $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{!! route('label.getlabelslist') !!}',
      "columns": [
         {data: 'label_key', name: 'label_key'},
         {data: 'name_en', name: 'name_en'},
         {data: 'name_ar', name: 'name_ar'},
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



$("input[value=Submit]").click(function(event) {
   if($("#formdata").parsley().validate()){
      $(this).attr("disabled",true);
      $('#formdata').submit();
   }
});

</script>
@endsection
