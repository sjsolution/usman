@extends('appsp')
@section('content')
@section('style')
<link rel="stylesheet" href="{{ asset('css/timepicker.css')}}"/>
<link rel="stylesheet" href="{{asset('css/sp-slot.css')}}">
@endsection

<div class="page-header">
   <nav aria-label="breadcrumb"></nav>
</div>

<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class=" add_button">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12 ">
                     <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <h3 class="page-title"> Timeslot Management   </h3>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            {{-- 
            <h4 class="card-title">Timeslot Management</h4>
            --}}
            <form class="cmxform" id="time_slot_form" method="POST" action="#" autocomplete="off" enctype="multipart/form-data">
               @csrf

               @if($adminTimeSlots->exists())

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Emergency Services : </label>  
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label class="switch">
                        <input type="checkbox" class="switch-input" name="is_emergency" id="is_emergency" {{ \Session::has('emergency') ?  'checked' : $spEmergencySlots->count() > 0 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                        </label>
                     </div>
                  </div>
               </div>

               <div class="row  emeregencytime" >
                  
                  <div class="col-md-6" >
                     <div class="form-group">
                        <label>From Time</label>
                        <input type="text" style="margin-top: -5%;
                        margin-left: 100px; width:160px" class="form-control " name="em_from_time" id="em_from_time" value="{{ old('em_from_time',!empty($spEmergencySlots->first()) ? $spEmergencySlots->first()->start_time : '09:00') }}" required >
                     </div>
                  </div>
                  <div class="col-md-6" style="margin-top: -5%;
                  margin-left: 50%; width:160px">
                     <div class="form-group">
                        <label>To Time</label>
                        <input type="text" style="margin-left: 75%;  width:160px;
                        margin-top: -25%;" class="form-control" name="em_to_time" id="em_to_time" value="{{ old('em_to_time',!empty($spEmergencySlots->first()) ? $spEmergencySlots->first()->end_time : '13:00')  }}" required >
                     </div>
                  </div>
                 
                  @if (\Session::has('emergency')) 
                     <strong  style="color: red; font-size: 15px; margin-left: 18px;">{!! \Session::get('emergency') !!}</strong>
                  @endif
                 
               </div>

               <hr><br>

               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Buffer Time</label>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <select name="buffer_lenght" id="buffer_lenght" class="form-control input-sm medium monday ">
                        @foreach ($bufferDuration as $key1 => $buffer)
                        <option value="{{ $key1 }}" {{ old('buffer_lenght',isset($spTimeSlots[0]->buffer_length) ? $spTimeSlots[0]->buffer_length : 10) == $key1 ? 'selected' : '' }}>{{$buffer}}</option>
                        @endforeach
                        </select>
                     </div>
                  </div>
               </div>

               <strong><span>Timeslots</span></strong>
               <hr>
               <table id="time-slot" class="table" style=" position: relative;   z-index: 0;">
                  <thead>
                     <tr>
                        <th>Day</th>
                        <th>Open/Close</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Break On/Off</th>
                        <th>Break From </th>
                        <th>Break To</th>
                     </tr>
                  </thead>
                  <tbody>

                     @if($spTimeSlots->count())
                     
                        @foreach ($spTimeSlots as $spslotsvalue)
                        
                           <tr id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_row' }}" style="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_end_time',$spslotsvalue['status']) ? 'background:white' : 'background:lightgrey' }}">
                           <td>
                           <span class="margin8">{{$spslotsvalue['dayname']['name_en']}} </span>
                           </td>
                           <td>
                                 <label class="switch">
                                 <input type="checkbox" class="switch-input" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_status' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_status' }}" {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? 'checked' : '' }} >
                                 <span class="slider round"></span>
                                 </label>
                           </td>
                           <td>
                              <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_start_time' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_start_time' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_start_time',strtolower($spslotsvalue['start_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                           </td>
                           <td>
                              <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_end_time' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_end_time' }}"  value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_end_time',strtolower($spslotsvalue['end_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                           </td>
                           <td>
                              <label class="switch">
                              <input type="checkbox" class="switch-input" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_status' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_status' }}" {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_status',$spslotsvalue['break_time_status']) ? 'checked ' : '' }} {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=true' }}>
                              <span class="slider round"></span>
                              </label>
                           </td>
                        
                           <td  id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_from_row' }}"   style="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_to',$spslotsvalue['break_time_status']) ? 'background:white' : 'background:lightgrey' }}">
                              <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_from' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_from' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_from',strtolower($spslotsvalue['break_from'])) }}"  {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) && old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['break_time_status']) ? '' : 'disabled=disabled' }}>
                           </td>
                           <td  id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_to_row' }}" style="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_to',$spslotsvalue['break_time_status']) ? 'background:white' : 'background:lightgrey' }}">
                              <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_to' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_to' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_to',strtolower($spslotsvalue['break_to'])) }}" {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) && old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['break_time_status']) ? '' : 'disabled=disabled' }}>
                           </td>

                           @if (\Session::has(strtolower($spslotsvalue['dayname']['name_en']))) 
                           <td>
                              <i class="fa fa-times" style="color:red;font-size: 38px;"  title="Error" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! \Session::get(strtolower($spslotsvalue['dayname']['name_en'])) !!}"></i>
                              
                           </td>
                           @endif   
                        
                           </tr>
                           <input type="hidden" name="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" id="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" value="{{ !empty($spslotsvalue->adminSlots) ? $spslotsvalue->adminSlots->id : 0  }}" class="form-control input-sm medium monday ">
                        @endforeach

                     @else
                        @if($adminTimeSlots->exists())
                           @foreach ($adminTimeSlots->get() as $spslotsvalue)
                              <tr id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_row' }}" style="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_end_time',$spslotsvalue['status']) ? 'background:white' : 'background:lightgrey' }}">
                              <td>
                                    <span class="margin8">{{$spslotsvalue['dayname']['name_en']}}</span>
                              </td>
                              <td>
                                    <label class="switch">
                                    <input type="checkbox" class="switch-input" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_status' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_status' }}" {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                    </label>
                              </td>
                              <td>
                                 <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_start_time' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_start_time' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_start_time',strtolower($spslotsvalue['start_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                              </td>
                              <td>
                                 <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_end_time' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_end_time' }}"  value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_end_time',strtolower($spslotsvalue['end_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                              </td>
                              <td>
                                 <label class="switch">
                                 <input type="checkbox" class="switch-input" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_status' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_status' }}" {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_status',$spslotsvalue['break_time_status']) ? 'checked' : '' }}>
                                 <span class="slider round"></span>
                                 </label>
                              </td>
                              <td>
                                 <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_from' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_from' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_from',strtolower($spslotsvalue['start_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                              </td>
                              <td>
                                 <input type="text" class="form-control timepicker" name="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_to' }}" id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_break_to' }}" value="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_break_to',strtolower($spslotsvalue['end_time'])) }}" required {{ old(strtolower($spslotsvalue['dayname']['name_en']).'_status',$spslotsvalue['status']) ? '' : 'disabled=disabled' }}>
                              </td>
                              

                              @if (\Session::has(strtolower($spslotsvalue['dayname']['name_en']))) 
                              <td>
                                 <i class="fa fa-times" style="color:red;font-size: 38px;"   title="Error" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! \Session::get(strtolower($spslotsvalue['dayname']['name_en'])) !!}"></i>
                                 
                              </td>
                              @endif   
                           
                              </tr>

                              <input type="hidden" name="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" id="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" value="{{ $spslotsvalue['id'] }}" class="form-control input-sm medium monday ">
                           @endforeach
                       
                        @endif
                     @endif

                  </tbody>
                  <tfoot>
                     <tr>
                        <td colspan="6">
                           <button id="submit" class="btn btn-gradient-danger mr-2" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;Save</button>
                           <a href="{{ url('/home') }}" class="btn btn-gradient-primary">Back</a>

                        </td>
                     </tr>
                  </tfoot>
                  @else
                     <div class="alert alert-warning" role="alert">
                        Global time slot not set by adminstrator...please contact admin
                     </div>
                  @endif
               </table>
            </form>
         </div>
      </div>
   </div>
</div>


@endsection
@section('scripts')
<script>
$(document).ready(function(){

   $(".emeregencytime").removeClass("openedTimes");
   $(".emeregencytime").addClass("closedTimes");
   $('#em_from_time').timepicker();
   $('#em_to_time').timepicker();

   if($("#is_emergency").is(':checked')){
      
      $(".emeregencytime").addClass("openedTimes");
      $(".emeregencytime").removeClass("closedTimes");
   }

   $("#is_emergency").on('change', function() { 
      
      if ($(this).is(':checked')) { 
         $(".emeregencytime").addClass("openedTimes");
         $(".emeregencytime").removeClass("closedTimes");
         
      }else{
         $(".emeregencytime").removeClass("openedTimes");
         $(".emeregencytime").addClass("closedTimes");
         
      }
   });

   

});
</script>
<script>
      $(document).ready(function(){
          $('[data-toggle="popover"]').popover();   
      });
      </script>

<script type="text/javascript" src="{{ asset('js/timepicker.js')}}"></script>
<script src="{{asset('js/timeslot_management.js')}}" type="text/javascript"></script>

@endsection