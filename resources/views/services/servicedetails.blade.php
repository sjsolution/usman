@extends('appsp')
@section('content')
<div class="page-header">
   <nav aria-label="breadcrumb">
      {{--
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="#">Forms</a></li>
         <li class="breadcrumb-item active" aria-current="page">Form elements</li>
      </ol>
      --}}
   </nav>
</div>

<style>
  .alert-info
  {
    margin-left:0px!important;
  }

  element.style {
      width: 100%;
  }
  h2.alert.alert-info {
      display: none;
  }
  </style>
<section class="content">
   <div class="card">
      <div class="card-body">
      <h3 class="page-title"><a href="javascript:history.go(-1)"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> View Service  </h3>

         <div class="row">
            <div class="col-lg-12">
               <div class="box-body">
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
                        <td>
                           <?php  echo wordwrap($details->name_en,60,"<br>\n", true); ?>
                        </td>
                        <td>
                           <?php  echo wordwrap($details->name_ar,60,"<br>\n", true); ?>
                        </td>
                        <th>العنوان</th>
                     </tr>
                     <tr>
                        <th>Service type</th>
                        <td>@if($details->type==1){{"Normal"}}@elseif($details->type==2){{"Insurance"}}@elseif($details->type==3){{"Emergency"}}@endif</td>
                          @if($details->type ==2)
                            <th>Party Type</th>
                            <td>
                               @if($details->type==2)
                                  @if($details->service_type==1)
                                     {{"Full Party"}}
                                  @elseif($details->service_type==2)
                                     {{"Third Party"}}
                                  @elseif($details->service_type==3)
                                     {{"Both - (Full + Third) Party"}}
                                  @endif
                               @endif
                            </td>
                          @endif
                     </tr>
                     <tr>
                        <th>Category</th>
                        <td>@if($details['category']['name_en'] != ''){{$details['category']['name_en']}}@endif</td>
                        <td>@if($details['category']['name_ar'] != ''){{$details['category']['name_ar']}}@endif</td>
                        <th>الفئة</th>
                     </tr>
                     <tr>
                        <th>Sub-category</th>
                        <td>@if($details['subcategory']['name_en'] != ''){{$details['subcategory']['name_en']}}@else {{ '--' }}@endif</td>
                        <td>@if($details['subcategory']['name_ar'] != ''){{$details['subcategory']['name_ar']}}@else {{ '--' }}@endif</td>
                        <th>فئة فرعية</th>
                     </tr>
                     <tr>
                        <th>Vehicle Type</th>
                        <td>{{ implode(",",$vehicleNames) }}</td>
                        <th></th>
                        <td></td>
                     </tr>
                     <tr>

                        @if($details->type==2)
                           <th>Premium (%)</th>
                           <td>{{$details->insurance_percentage ." "."%"}}</td>
                           <th>Fixed Price</th>
                           <td>{{$details->fixed_price ." "."KWD"}}</td>

                        @else
                           <th>Amount</th>
                           <td>{{$details->amount ." "."KWD"}}</td>
                        @endif
                     </tr>

                     <tr>
                        @if($details->type!=2)
                        <th>Service time duration</th>
                        <td>{{$details->time_duration."  ".$details->time_type}}</td>
                        @endif
                     </tr>


                  </table>
                  <br><br>
                  @if(!empty($details['servicedescription']->toArray()) && $details['servicedescription'] !=null)
                  <table class="table table-striped">
                     <h2 class="alert alert-info" style="width:100%;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Service description</font></font></h2>
                     @foreach($details['servicedescription'] as $desc)
                     <tr>
                        <th>Service Description</th>
                        <td>
                           <?php  echo wordwrap($desc->description_en,60,"<br>\n", true); ?>
                           {{-- {{$desc->description_en}} --}}
                        </td>
                        <td>
                           <?php  echo wordwrap($desc->description_ar,60,"<br>\n", true); ?>
                           {{-- {{$desc->description_ar}} --}}
                        </td>
                        <th>الوصف</th>

                     </tr>
                     @endforeach
                  </table>
                  @endif
                  <br><br>
                  @if(!empty($details['serviceaddonsdata']->toArray()) && $details['serviceaddonsdata'] !=null)
                  <table class="table table-striped">
                     <h2 class="alert alert-info" ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Service Addons</font></font></h2>
                     @foreach($details['serviceaddonsdata'] as $key=>$addons)

                     <tr>
                        <th>Addons </th>
                        <td>{{ $addons['name_en'] }}</td>
                         <td style=" direction: unset !important;text-align: right;">{{ $addons['name_ar'] }}</td>
                        <th style=" direction: unset !important;text-align: right;">الإضافات</th>

                     </tr>
                     {{-- <tr>
                        <th>Description </th>
                        <td>{{ $addons['description_ar'] }}</td>
                        <th>الوصف</th>
                        <td>{{ $addons['description_ar'] }}</td>
                     </tr> --}}
                     <tr>
                        <th>Amount </th>
                        <td>{{ $addons['amount'] }}</td>
                     </tr>
                     @endforeach
                  </table>
                  <br>
                  @endif
                  <input style="margin-left:0px;" type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">
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
