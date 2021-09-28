@extends('app')
@section('content')

<div class="page-header"></div>
  <section class="content">
      <div class="card">
        <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">
              <table class="table">
              <tr class="">
                <th>Name</th>
                <th>اسم</th>
                <th>Type</th>
                <th>Image</th>
                <th>Fixed Price</th>
                <th>Commission</th>
                <th>Field Name</th>
              </tr><tr>
                <td>{{ $category->name_en}}</td>
                <td>{{ $category->name_ar}}</td>
                <td>
                  @if($category->type==1)
                    Normal
                  @elseif($category->type==2)
                    Insurance
                  @elseif($category->type==3)
                    Emergency
                  @endif
                  </td> 
                <td><img class='tableimage' src='{{config('app.AWS_URL')}}{{$category->image}}'/></td>
                <td>{{ $category->fixed_price}}</td>
                <td>{{ $category->commission_percent}}</td>
                <td>{{ $category->user_applicable_fee_name}}</td>
              </tr>
            </table>
            <h4 style="margin:10px;">Sub category details</h4>
            @if($subcategory->count()==0)
              <p class="alert alert-info">No subcategory Found...</p>
            @else
              <table class="table table-condensed table-hover table-bordered">
                <tr class="">
                <th>Name</th>
                <th>اسم</th>
              </tr>
                <?php if ($category->parent_id ==0) {


                  foreach($subcategory as $sub)
                  {
                    echo "<tr>
                      <td>".$sub->name_en."</td>
                      <td>".$sub->name_ar."</td>
                    </tr>";


                  }
                } ?>

              </table>
            @endif
            <br>

<a href="javascript: history.go(-1)"><input type="button" style="margin-left: 10px;"  class="btn btn-gradient-danger onclick="window.history.back()" value="Back"></a>
              




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
