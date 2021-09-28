@extends('appsp')
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
                <th>Full Name</th>
                <td><?php  echo wordwrap($user->full_name_en,30,"<br>\n", true); ?></td>
                <!-- <th></th> -->
                <td><?php  echo wordwrap($user->full_name_ar,30,"<br>\n", true); ?></td>
                <th class="rtl_text">الاسم الكامل </th>

              </tr>
              <tr>
                <th>Email Address</th>
                <td><?php  echo wordwrap($user->email,30,"<br>\n", true); ?></td>
                <th>Mobile Number</th>
                <td>{{ $user->country_code.' '.$user->mobile_number}}</td>
              </tr>
              <tr>
                <th>Active Status</th>
                <td> @if($user->is_active ==1)
                  <label class="badge badge-gradient-success">Unblock</label>
                @elseif($user->is_active == 2)
                  <label class="badge badge-gradient-danger">Suspended</label>
                  @elseif($user->is_active == 0)
                        <label class="badge badge-gradient-info">Block</label>
                  @endif</td>
                <th>Mobile verified</th>
                <td> @if($user->is_verified_mobile ==1)
                  <label class="badge badge-gradient-success">verified</label>
                @elseif($user->is_verified_mobile == 2)
                  <label class="badge badge-gradient-danger">Suspended</label>
                  @elseif($user->is_verified_mobile == 0)
                        <label class="badge badge-gradient-info">Pending</label>
                  @endif</td>
              </tr>
              <tr>
                <th>Profile Pic</th>
                <td>  @if($user->profile_pic)
                      <img src="{{config('app.AWS_URL').$user->profile_pic}}" alt="">
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
