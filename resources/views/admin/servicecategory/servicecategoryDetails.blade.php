@extends('app')
@section('content')

  <section class="content">
      <div class="card">
        <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">
              <table class="table table-striped">
              <tr>
                <th>Name (EN)</th>
                <td>{{ $category->name_en}}</td>
                <th>Name (AR)</th>
                <td>{{ $category->name_ar}}</td>
              </tr>

            
            </table><br>
              <input type="button" class="btn btn-info" onclick="window.history.back()" value="Back">
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
