@extends('app')
@section('content')
<div class="page-header"></div>
<div class="card">
    <div class="card-body">
        <div class="fixed_top_settings">
	        <h3 class="page-title "> Admin Wallet List
	        </h3>
        </div>
        <br>

        <table class="table table-hover"  id="users-table">
            <thead>
                <tr>
                    <!-- <th>Name</th> -->
                    <th>Amount</th>
                    <th>Credit Amount</th>
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
<script>
    $(document).ready(function() {
     
        $('#users-table').DataTable({
	      processing: true,
	      serverSide: true,
	      ajax: '{!! route('admin.getAdminwallet.list') !!}',
            "columns": [
		        // {data: 'admin_id', name: 'admin_id'},
		        {data: 'amount', name: 'amount'},
		        {data: 'credit_amount', name: 'credit_amount'},
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
</script>

@endsection