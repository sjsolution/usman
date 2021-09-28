@extends('appsp')
@section('content')
  <section class="content mt-4">
      <div class="card">

        <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">

            <h3 class="page-title"> Technician Details</h3> <br>

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
              <table class="table">
              <tr>
                <th>Name</th>
                <td><?php  echo wordwrap($user->full_name_en,30,"<br>\n", true); ?></td>
                <th>الاسم</th>
                <td><?php  echo wordwrap($user->full_name_ar,30,"<br>\n", true); ?></td>
              </tr>

              <tr>
                <th>Mobile Number</th>
                <td>{{ $user->country_code.' '.$user->mobile_number}}</td>

                <th>Email Address</th>
                  <td><?php  echo wordwrap($user->email,30,"<br>\n", true); ?></td>

              </tr>
              <tr>

                <th>Status</th>
                <td> @if($user->is_active ==1)
                  <label class="badge badge-gradient-success">Unblock</label>
                @elseif($user->is_active == 2)
                  <label class="badge badge-gradient-danger">Suspended</label>
                  @elseif($user->is_active == 0)
                        <label class="badge badge-gradient-info">Block</label>
                  @endif</td>
                  <th></th>
                  <td></td>
              </tr>
            </table><br>
            <table class="table ">
            <thead>
                <tr>
                    <th>Day Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Break From (Time)</th>
                    <th>Break To (Time)</th>
                    <th>Open / Closed</th>
                </tr>
            </thead>

            <tbody>
              @foreach ($user['technicaintimeslots'] as $technicatimes)
                <tr>
                  <td>
                    {{ $technicatimes['dayname']['name_en'] }}
                  </td>
                  <td>
                    {{ $technicatimes['start_time'] }}
                  </td>
                  <td>
                    {{ $technicatimes['end_time'] }}
                  </td>
                  <td>
                    {{ $technicatimes['break_from'] }}
                  </td>
                  <td>
                    {{ $technicatimes['break_to'] }}
                  </td>
                  <td>
                    @if($technicatimes['status'] ==1){{ "Open" }} @elseif ($technicatimes['status'] ==0){{ "Closed" }} @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
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
