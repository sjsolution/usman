@extends('appsp')
@section('content')
@section('style')
<link rel="stylesheet" href="{{ asset('css/timepicker.css')}}"/>
<link rel="stylesheet" href="{{asset('css/technician-slot.css')}}">
@endsection
 
<div class="page-header">
  <nav aria-label="breadcrumb"></nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h3 class="page-title">Technician Timeslot Management</h3>      
        <form class="cmxform" id="time_slot_form" method="POST" action="#" autocomplete="off" enctype="multipart/form-data">
        @if($adminTimeSlots->exists())
        @csrf
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

              @if($technicianSlots->count())
                @foreach ($technicianSlots as $spslotsvalue)
             
                  <tr id="{{ strtolower($spslotsvalue['dayname']['name_en']).'_row' }}" style="{{ old(strtolower($spslotsvalue['dayname']['name_en']).'_end_time',$spslotsvalue['status']) ? 'background:white' : 'background:lightgrey' }}">
                  <td>
                  <span class="margin8">{{$spslotsvalue['dayname']['name_en']}} </span>
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
                 
                  <input type="hidden" name="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" id="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" value="{{ !empty($spslotsvalue->spTimeSlots) ? $spslotsvalue->spTimeSlots->id : 0  }}" class="form-control input-sm medium monday ">
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
                      <input type="hidden" name="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" id="{{strtolower($spslotsvalue['dayname']['name_en'])}}_time_slot" value="{{ $spslotsvalue['id'] }}" class="form-control input-sm medium monday ">
                  @endforeach
              
                @endif
              @endif

            </tbody>
     
            <tfoot>
              <tr>
                <td colspan="6">
                  <button id="submit" class="btn btn-gradient-danger mr-2" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;Save</button>
                </td>
              </tr>
            </tfoot>

          </table>
          @else
            <div class="alert alert-warning" role="alert">
              Please set your timeslot ...
            </div>
          @endif
        </form>
      </div>
    </div>
  </div>
</div>

  
@endsection
@section('scripts')
<script>
  $(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
  });
</script>
<script type="text/javascript" src="{{ asset('js/timepicker.js')}}"></script>
<script src="{{asset('js/timeslot_management.js')}}" type="text/javascript"></script>
@endsection
