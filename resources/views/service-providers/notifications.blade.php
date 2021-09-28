@extends('appsp')
@section('content')

        <div class="page-header">
      
        </div>
        @if (session()->has('success'))
            <h1>{{ session('success') }}</h1>
        @endif
        <div class="card">
        
          <div class="card-body">

          <h3 class="page-title "> Notification List   
           
          
          </h3> 

          <table class="table table-hover"  id="users-table">
              <thead> 
                 <tr>
                    <th>Title</th>
                    <th>Description</th>
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
        ajax: '{!! route('sp.notification.list') !!}',
        "columns": [
            {data: 'title', name: 'title'},
            {data: 'description', name: 'description'},
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

</script>
@endsection
