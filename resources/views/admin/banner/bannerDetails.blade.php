@extends('app')
@section('content')
  <div class="page-header">

    <nav aria-label="breadcrumb">
      {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form elements</li>
      </ol> --}}
    </nav>
  </div>
  <section class="content">
      <div class="card">
        <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">

            <h3 class="page-title">Banner Details</h3>


            {{--
                          <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-2">
                              @if($user->profile_pic !='')
                              <a href="{{env('AWS_URL').$user->profile_pic}}" class="image-tile"><img src="{{env('AWS_URL').$user->profile_pic}}" alt="Profile image"></a>
                              @else
                                    <a href="{{ env('DEFAULT_IMAGE') }}" class="image-tile ">
                                    <img src="{{ env('DEFAULT_IMAGE') }}" alt="Profile image" class="img-rounded">
                                  </a>
                              @endif
                              </div>
                            <div class="col-md-5"></div>
                          </div>
            --}}
              <table class="table table-striped">
              <tr>
                <th>Title</th>
                <td><?php  echo wordwrap($banner->title_en,30,"<br>\n", true); ?></td>
                  <td style="direction: rtl"><?php  echo wordwrap($banner->title_ar,30,"<br>\n", true); ?></td>
                <th style="direction: rtl">عنوان</th>

              </tr>

              <tr>
                <th>Description</th>
                <td><?php  echo wordwrap($banner->description_en,30,"<br>\n", true); ?></td>
                  <td style="direction: rtl"><?php  echo wordwrap($banner->description_ar,30,"<br>\n", true); ?></td>
                <th style="direction: rtl">وصف</th>


              </tr>
              <tr>

                <th>Type</th>
                <td> @if($banner->type ==1)
                  <label class="badge badge-gradient-success">Splash</label>
                @elseif($banner->type == 2)
                  <label class="badge badge-gradient-danger">Carousel</label>
                  @endif</td>
                  
              </tr>
              <tr>
                <th>Banner Image (English View)</th>
                  <td><img src="{{config('app.AWS_URL').$banner->banner_image}}"/></td>
                  <th>Banner Image (Arabic View)</th>
                  <td><img src="{{config('app.AWS_URL').$banner->banner_image_ar}}"/></td>
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
