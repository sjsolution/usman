@extends('app')
@section('content')
<div class="page-header"></div>
  <section class="content pad-20">
      <div class="card"> 
        <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">
            
              <table class="table table-striped">
              <tr>
                <th>Name</th>
                <td><?php  echo wordwrap($admin->name,30,"<br>\n", true); ?></td>
                               
       
              </tr>
              <tr>
                <th>Email Address</th>
                <td><?php  echo wordwrap($admin->email,30,"<br>\n", true); ?></td>
                <th>Mobile Number</th>
                <td>{{'+965-'.$admin->mobile_number}}</td>
              </tr>
              <tr>
                <th>Active Status</th>
                <td> 
                @if($admin->is_active == 1)
                    <label class="badge badge-gradient-success">Unblock</label>
                @elseif($admin->is_active == 0)
                    <label class="badge badge-gradient-info">Block</label>
                @endif</td>
                
              </tr>
              <tr>
                <th>Profile Pic</th>
                <td>  @if($admin->profile_pic)
                      <img src="{{config('app.AWS_URL').$admin->profile_pic}}" alt="">
                @else
                    <img src="{{asset('images/defaultProfile.png')}}" class="img img-round">
                @endif</td>
              </tr>

            </table><br>
              <input type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">
            </div>
            <!-- /.box-body -->
          </div>
          </div>
          </div>
                    <!-- /.box -->
</div>
</section>

<script>
$(document).ready(function(){
  if ($("#lightgallery").length) {
    $("#lightgallery").lightGallery();
  }
  if ($("#lightgallery-without-thumb").length) {
    $("#lightgallery-without-thumb").lightGallery({
      thumbnail: true,
      animateThumb: false,
      showThumbByDefault: false
    });
  }
  if ($("#video-gallery").length) {
    $("#video-gallery").lightGallery();
  }
});
</script>
@endsection
