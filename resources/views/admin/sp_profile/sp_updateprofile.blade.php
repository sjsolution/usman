@extends('appsp')
@section('content')
<style>
   .remove {
   display: block;
   /* background: #444; */
   /* border: 1px solid black; */
   color: white;
   text-align: center;
   cursor: pointer;
   width:100px !important;
   margin-top: -5px;
   margin-left: 7px;
   margin-bottom: 5px;
   }
   .footer
   {
     display:none;
   }
   .removeImages{display: none;}
</style>
<!-- Content Header (Page header) -->
<div class="page-header ">
   <nav aria-label="breadcrumb">
      {{-- 
      <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="#">Forms</a></li>
         <li class="breadcrumb-item active" aria-current="page">Form elements</li>
      </ol>
      --}}
   </nav>
</div>

<div class="row">
   <div class="col-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <h3 class="page-title">Update Profile</h3>
            {{-- 
            <h4 class="card-title">Create</h4>
            --}}
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
               <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $message }}</strong>
            </div>
            @endif
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-block">
               <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $error }}</strong>
            </div>
            @endforeach
            @endif
            <form class="forms-sample" id="profileform" action="{{route('sp.profileUpdated')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label >Name</label>
                        <input type="text" class="form-control" maxlength="50" name="full_name_en" value="{{$user->full_name_en}}" placeholder="Name" required>
                     </div>
                     @if ($errors->has('full_name_en'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('full_name_en') }}</strong>
                     </span>
                     @endif
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="arabic_label" style="float:right;">الاسم</label>
                        <input type="text" style="text-align:right;" maxlength="50" class="form-control full_name_ar"
                           name="full_name_ar" placeholder="الاسم" value="{{$user->full_name_ar}}" required=" " >
                     </div>
                     @if ($errors->has('full_name_ar'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('full_name_ar') }}</strong>
                     </span>
                     @endif
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{$user->email}}"placeholder="Email address" required>
                     </div>
                     @if ($errors->has('email'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('email') }}</strong>
                     </span>
                     @endif
                  </div>
                  {{-- <div class="col-md-2">
                       
                     <div class="form-group">
                        <label class="arabic_label">Country Code</label>
                        <select class="browser-default custom-select form-control" name="countrycode" value="{{$user->country_code}}" >
                           <option selected>Select Country Code</option>
                           @foreach($kuwait as $k)
                           <option value="{{ $k->phonecode }}"@if($k->phonecode == $user->country_code){{ "selected"}}@endif> +{{$k->phonecode}} </option>
                           @endforeach
                        </select>
                     </div>
                     @if ($errors->has('countrycode'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('countrycode') }}</strong>
                     </span>
                     @endif
                  </div> --}}
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="arabic_label" style="float:left;">Phone.no</label>
                        <div class="input-group">
                           <div class="input-group-prepend">
                              <span class="input-group-text" style="height: 39px;">+965</span>
                           </div>
                           <input type="text" style="text-align:left;" maxlength="12" minlength="8" class="form-control mobile_no"
                           name="mobile_number" placeholder="Phone.no"  value="{{$user->mobile_number}}" required=" ">
                        </div>
                       
                     </div>
                     @if ($errors->has('mobile_number'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('mobile_number') }}</strong>
                     </span>
                     @endif
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label> Profile Image </label>
                        <input type="file" name="profile_pic" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg">
                        <div class="input-group col-xs-12">
                           <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                           <span class="input-group-append">
                           <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                           </span>
                        </div>
                        <i style="color:lightgrey">*recommended dimension 500 x 500</i>
                     </div>
                     
                     @if ($errors->has('profile_pic'))
                     <span class="invalid-feedback" role="alert">
                     <strong>{{ $errors->first('profile_pic') }}</strong>
                     </span>
                     @endif
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Cover image  </label>
                        <input type="file" name="cover_profile_pic[]" class="file-upload-default" accept="image/x-png,image/gif,image/jpeg" multiple="multiple"/>
                        <div class="input-group col-xs-12">
                           <input type="text" class="form-control file-upload-info" disabled="" readonly placeholder="Upload Image">
                           <span class="input-group-append">
                           <button class="file-upload-browse btn btn-gradient-danger" type="button">Upload</button>
                           </span>
                        </div>
                        <i style="color:lightgrey">*recommended dimension 1000 x 500</i>
                     </div>
                     
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>As Technician</label>
                        <select name="is_technician" class="form-control">
                        <option value="0" @if($user->is_technician ==0){{ 'selected' }} @endif>No</option>
                        <option value="1" @if($user->is_technician ==1){{ 'selected' }} @endif>Yes</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class ="col-md-6">
                     <div class="form-group">
                        <label>About us</label>
                        <textarea type="text" class="form-control about" name="about"  placeholder=" About Us">@if($user->about){{ $user->about }}@endif</textarea>
                     </div>
                  </div>
                  <div class="col-md-6">
                        <div class="form-group">
                           <label style="float:right">معلومات عنا</label>
                           <textarea type="text" style="direction: rtl;" class="form-control about" name="about_ar"  placeholder="معلومات عنا">@if($user->about){{ $user->about_ar }}@endif</textarea>
                        </div>
                  </div>
               </div>
               <input type="button" value="Submit" class="btn btn-gradient-danger mr-2">
               <br/>
               <br/>
               @if(!empty($user['profile_pic']))
                  <h2 class="alert alert-success">Profile Images</h2>
                  <div class="row">
                     <img src="{{config('app.AWS_URL')}}{{$user['profile_pic']}}" id="{{ $user['id'] }}" style='height:100px !important; width:100px !important;margin:8px'>
                  </div>
               @endif
               
               @if(!empty($user['coverimages']) && count($user['coverimages']) >0)
               <h2 class="alert alert-success">Cover Images</h2>
               <div class="row">
                  {{-- 
                  <div id="lightgallery" class="row lightGallery">
                     --}}
                     @foreach ($user['coverimages'] as $covers)
                     <div class="col-md-2">
                        {{-- 
                        <div id="{{ $covers['id'] }}"> --}}
                           {{-- <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-id="{{ $covers['id'] }}" style="height:26px !important; width:30px !important;">X</a> --}}
                           <span class="btn btn-sm btn-danger remove {{ $covers['id'] }}" data-id="{{ $covers['id'] }}" data-attr="{{ $covers['id'] }}" 
                              >-</span>
                           <img src="{{config('app.AWS_URL')}}{{$covers['cover_image']}}" id="{{ $covers['id'] }}" style='height:100px !important; width:100px !important;margin:8px'>
                           {{-- 
                        </div>
                        --}}
                     </div>
                     @endforeach
                     {{-- 
                  </div>
                  --}}
               </div>
               @endif
               <br>
               @if($user['providertimeslots']->count())
               <h2 class="alert alert-success">My Time Slots</h2>
               <table class="table">
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
                     @foreach ($user['providertimeslots'] as $providertimeslot)
                     <tr>
                        <td>
                           {{ $providertimeslot['dayname']['name_en'] }}
                        </td>
                        <td>
                           {{ $providertimeslot['start_time'] }}
                        </td>
                        <td>
                           {{ $providertimeslot['end_time'] }}
                        </td>
                        <td>
                           {{ $providertimeslot['break_from'] }}
                        </td>
                        <td>
                           {{ $providertimeslot['break_to'] }}
                        </td>
                        <td>
                           @if($providertimeslot['status'] ==1){{ "Open" }} @elseif ($providertimeslot['status'] ==0){{ "Closed" }} @endif
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
               @endif
               <br>
         </div>
      </div>
   </div>
   </form>
</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
   // form submit
    $("input[value=Submit]").click(function(event) {
      if($("#profileform").parsley().validate()){
         $(this).attr("disabled",true);
        $('#profileform').submit();
     }
    });
   
    (function($) {
   'use strict';
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
   })(jQuery);
</script>
<script>
   $(document).ready(function() {
         $(".remove").on('click', function () {
           var imgName = $(this).attr("data-attr");
           var coverid = $(this).attr("data-id");
           swal({
                 title: 'Are you sure?',
                 text: "Are you want to delete image?",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3f51b5',
                 cancelButtonColor: '#ff4081',
                 confirmButtonText: 'Great ',
                 buttons: {
                 cancel: {
                   text: "Cancel",
                   value: null,
                   visible: true,
                   className: "btn btn-danger",
                   closeModal: true,
                 },
                 confirm: {
                   text: "OK",
                   value: true,
                   visible: true,
                   className: "btn btn-primary",
                   closeModal: true,
                 }
               }
               }).then(function(isConfirm){
                 if(isConfirm){
                   $.ajax({
                      url: "{{route('coverimagedelete')}}",
                     method: 'POST',
                     data:{coverid:coverid},
                     type:"json",
                     success: function(response) {
                        if(response.status == '1'){
                          swal("Success!", response.message, "success");
                          $("#"+imgName).addClass('removeImages');
                          $("."+imgName).addClass('removeImages');
                        }
                     },
                     error:function(){
                     swal({
                       text: 'Something went wrong',
                       button: {
                         text: "OK",
                         value: true,
                         visible: true,
                         className: "btn btn-primary"
                       }
                     })
                   }
                 });
             }
           })
         });
       });
</script>
@endsection