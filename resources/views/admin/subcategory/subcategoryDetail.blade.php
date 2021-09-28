@extends('app')

@section('content')
  
  <br>
  
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Dealer</h1>
    </section>
    <!-- Main content -->
    @include('message')
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-hover table-bordered">
              <tr>
                <th>Dealer Name</th>
                <td>{{ $dealer->name}}</td>
              </tr>
              <tr>
                <th>Dealer Email</th>
                <td>{{ $dealer->email}}</td>
              </tr>            
              <tr>
                <th>Dealer Phone No</th>
                <td>{{ $dealer->mobile_no}}</td>
              </tr>              
              <tr>
                <th>Dealer Type</th>
                <td>{{ $dealer->dealerType['type_name']}}</td>
              </tr>            
              <tr>
                <th>Dealer Status</th>
                <td>{{ $dealer->status ? "Active" : "Inactive"}}</td>
              </tr>            
            </table>
              <input type="button" class="btn btn-primary" onclick="window.history.back()" value="Back"> 
            </div>
            <!-- /.box-body -->
          </div>
          </div>
          </div>    
                    <!-- /.box -->
    
</section>
<!-- /.content -->
<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Image</h4>
    </div>
        <!-- Wrapper for slides -->
        <div class="carousel-inner"></div>


    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$('img').on('click', function() {
      $(".carousel-inner").html('<img width="100%" src="'+$(this).attr('src')+'">');
       $("#myModal").modal();
   });
</script>
@endsection
