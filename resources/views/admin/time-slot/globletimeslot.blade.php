@extends('app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/timepicker.css')}}"/>
<link rel="stylesheet" href="{{asset('css/admin-slot.css')}}">
@endsection
@section('content')

<div class="page-header" >
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
                  <h3 class="page-title"> Timeslot Management </h3>
                </div>
              </div>
            </div>
          </div>
        </div>
       
        <table id="time-slot" class="table" style=" position: relative; z-index: 0;">
          <thead>
            <tr>
              <th>Day</th>
              <th>Active/In-active</th>
              <th>Start Time</th>
              <th>End Time</th>
            </tr>
          </thead>
          <form class="cmxform" id="time_slot_form" method="POST" action="#" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <tbody>
                @if(!$adminSlots->exists())

                  @foreach ($days as $day)
                  <tr id="{{ strtolower($day->name_en).'_row' }}">
                    <td>
                        <span class="margin8">{{ $day->name_en }}</span>
                    </td>
                    <td>
                        <label class="switch">
                        <input type="checkbox" class="switch-input" name="{{ strtolower($day->name_en).'_status' }}" id="{{ strtolower($day->name_en).'_status' }}" checked >
                        <span class="slider round"></span>
                        </label>
                    </td>
                    <td >
                        <input type="text" class="form-control timepicker" name="{{ strtolower($day->name_en).'_start_time' }}" id="{{ strtolower($day->name_en).'_start_time' }}" value="{{ old(strtolower($day->name_en).'_start_time', '09:00') }}" required>

                    </td>
                    <td>
                        <input type="text" class="form-control timepicker" name="{{ strtolower($day->name_en).'_end_time' }}" id="{{ strtolower($day->name_en).'_end_time' }}" value="{{ old(strtolower($day->name_en).'_end_time', '09:00') }}"  required>

                    </td>
                    @if (\Session::has(strtolower($day->name_en))) 
                    <td>
                      <i class="fa fa-times" style="color:red;font-size: 38px;"  title="Error" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! \Session::get(strtolower($day->name_en)) !!}"></i>
                    </td>
                    @endif   
                    
                  </tr>
                  @endforeach

                @else

                  @foreach ($adminSlots->get() as $spslotsvalue)
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
                    @if (\Session::has(strtolower($spslotsvalue['dayname']['name_en']))) 
                    <td>
                      <i class="fa fa-times" style="color:red;font-size: 38px;"  title="Error" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! \Session::get(strtolower($spslotsvalue['dayname']['name_en'])) !!}"></i>
                    </td>
                    @endif   
                
                  </tr>
                  @endforeach

                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <button id="submit" class="btn btn-gradient-danger mr-2" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;Save</button>
                    </td>
                </tr>
            </tfoot>
          </form>
        </table>
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
<script src="{{asset('js/timeslot_management.js')}}"></script>
@endsection
