@extends('appsp')
@section('content')
<style>
.card-title{
    margin-bottom: 30px !important;
}
.error {
    color: red;
}

.table th, .jsgrid .jsgrid-table th, .table td, .jsgrid .jsgrid-table td
{
    vertical-align: top!important;
}
.timepicker
{

}


.clock
{width: 1.2857142857142858em;
    text-align: center;
    color: #555;

}
.input-group, .asColorPicker-wrap
{
    width: 70%!important;
}

input.form-control.timepicker
{
    padding: 0px;
}



.input-group-append .input-group-text, .input-group-prepend .input-group-text
{
    border-color: #ebedf2;
    padding: 10px 0.75rem;
    border-left:none!important;
    background-color: #f4f4f4!important;
    color: #c9c8c8;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 0px 4px 4px 0px!important;
    margin-top: 2px;
}
</style>

  <div class="page-header">
    <h3 class="page-title">Time slot</h3>
    <nav aria-label="breadcrumb">
      {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form elements</li>
      </ol> --}}
    </nav>
  </div>
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <b>{!! \Session::get('success') !!}</b>
    </div>
    @endif

    @if (\Session::has('error'))
    <div class="alert alert-danger">
        <b>{!! \Session::get('error') !!}</b>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Timeslot Management </h4>
                    <table id="time-slot" class="table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Slot Lenght</th>
                            <th>Buffer Time</th>
                            <th>Break From </th>
                            <th>Break To</th>
                            <th>On/Off</th>
                        </tr>
                    </thead>
                    <form class="cmxform" id="time_slot_form" method="POST" action="#" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <tbody>
                        <tr>
                            <td>
                                <span class="margin8">Sunday</span>
                            </td>
                            <td>
                              <input type="hidden" name="sun_time_slot" value="@if(!empty($adminTimeSlots[0]['id'])){{ $adminTimeSlots[0]['id'] }}@endif">
                              <select name="sun_start_time" id="sun_start_time" class="form-control">
                              <?php
                             if(!empty($adminTimeSlots[0]['status']) && $adminTimeSlots[0]['day_id'] == 1 &&  $adminTimeSlots[0]['status'] ==1){
                                $tNow = strtotime($adminTimeSlots[0]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[0]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('sun_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                  }
                              ?>
                              </select>

                                {{-- <input type="text" class="form-control timepicker" name="sun_start_time" id="sun_start_time" value="{{ old('sun_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="sun_end_time" id="sun_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[0]['status']) && $adminTimeSlots[0]['day_id'] == 1 &&  $adminTimeSlots[0]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[0]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[0]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('sun_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                  }
                              ?>
                              </select>

                                {{-- <input type="text" class="form-control timepicker" name="sun_end_time" id="sun_end_time" value="{{ old('sun_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="sun_slot_lenght" id="sun_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="sun_buffer_lenght" id="sun_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>

                            <td>
                              <select name="sun_from_time" id="sun_from_time" class="form-control input-sm medium monday ">
                                <?php
                               if(!empty($adminTimeSlots[0]['status']) && $adminTimeSlots[0]['day_id'] == 1 &&  $adminTimeSlots[0]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[0]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[0]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('sun_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                    }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="sun_to_time" id="sun_to_time" class="form-control input-sm medium monday ">
                                <?php
                               if(!empty($adminTimeSlots[0]['status']) && $adminTimeSlots[0]['day_id'] == 1 &&  $adminTimeSlots[0]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[0]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[0]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('sun_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                    }
                                ?>
                              </select>
                            </td>

                            <td>
                              <select name="sun_status" id="sun_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>



                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Monday</span>
                            </td>
                            <td>
                              <input type="hidden" name="mon_time_slot" value="@if(!empty($adminTimeSlots[1]['id'])){{ $adminTimeSlots[1]['id'] }}@endif">
                              <select name="mon_start_time" id="mon_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[1]['status']) && $adminTimeSlots[1]['status']==2 && $adminTimeSlots[1]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[1]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[1]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php
                                      // echo '<option value="'.$optionvalue
                                      //   .'" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>

                                {{-- <input type="text" class="form-control timepicker" id="mon_start_time" name="mon_start_time" value="{{ old('mon_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="mon_end_time" id="mon_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[1]['status']) && $adminTimeSlots[1]['status']==2 && $adminTimeSlots[1]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[1]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[1]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);?>
                                      <option value="{{ $optionvalue }}" {{ old('mon_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="mon_end_time" name="mon_end_time" value="{{ old('mon_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="mon_slot_lenght" id="mon_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="mon_buffer_lenght" id="mon_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>

                            <td>
                              <select name="mon_from_time" id="mon_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[1]['status']) && $adminTimeSlots[1]['status']==2 && $adminTimeSlots[1]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[1]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[1]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php
                                        // echo '<option value="'.$optionvalue
                                        //   .'" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="mon_to_time" id="mon_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[1]['status']) && $adminTimeSlots[1]['status']==2 && $adminTimeSlots[1]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[1]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[1]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php
                                        // echo '<option value="'.$optionvalue
                                        //   .'" {{ old('mon_start_time') == $optionvalue ? 'selected' : '' }}>'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="mon_status" id="mon_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Tuesday</span>
                            </td>
                            <td>
                              <input type="hidden" name="tues_time_slot" value="@if(!empty($adminTimeSlots[2]['id'])){{ $adminTimeSlots[2]['id'] }}@endif">
                              <select name="tues_start_time" id="tues_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[2]['status']) && $adminTimeSlots[2]['status']==3 && $adminTimeSlots[2]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[2]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[2]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('tues_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);

                                }
                              }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="tues_start_time" name="tues_start_time" value="{{ old('tues_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="tues_end_time" id="tues_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[2]['status']) && $adminTimeSlots[2]['status']==3 && $adminTimeSlots[2]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[2]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[2]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('tues_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="tues_end_time" name="tues_end_time" value="{{ old('tues_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="tues_slot_lenght" id="tues_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="tues_buffer_lenght" id="tues_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                              <select name="tues_from_time" id="tues_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[2]['status']) && $adminTimeSlots[2]['status']==3 && $adminTimeSlots[2]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[2]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[2]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('tues_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);

                                  }
                                }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="tues_to_time" id="tues_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[2]['status']) && $adminTimeSlots[2]['status']==3 && $adminTimeSlots[2]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[2]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[2]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('tues_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);

                                  }
                                }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="tues_status" id="tues_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Wednesday</span>
                            </td>
                            <td>
                              <input type="hidden" name="wed_time_slot" value="@if(!empty($adminTimeSlots[3]['id'])){{ $adminTimeSlots[3]['id'] }}@endif">
                              <select name="wed_start_time" id="wed_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[3]['status']) && $adminTimeSlots[3]['status']==4 && $adminTimeSlots[3]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[3]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[3]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);?>
                                      <option value="{{ $optionvalue }}" {{ old('wed_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                               }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="wed_start_time" name="wed_start_time" value="{{ old('wed_start_time') }}" required> --}}
                            </td>
                            <td>

                              <select name="wed_end_time" id="wed_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[3]['status']) && $adminTimeSlots[3]['status']==4 && $adminTimeSlots[3]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[3]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[3]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);
                                      ?>
                                      <option value="{{ $optionvalue }}" {{ old('wed_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php
                                      //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                               }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="wed_end_time" name="wed_end_time" value="{{ old('wed_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="wed_slot_lenght" id="wed_slot_lenght" class="form-control input-sm medium">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="wed_buffer_lenght" id="wed_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                              <select name="wed_from_time" id="wed_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[3]['status']) && $adminTimeSlots[3]['status']==4 && $adminTimeSlots[3]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[3]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[3]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('wed_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                 }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="wed_to_time" id="wed_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[3]['status']) && $adminTimeSlots[3]['status']==4 && $adminTimeSlots[3]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[3]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[3]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('wed_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                 }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="wed_status" id="wed_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Thursday</span>
                            </td>
                            <td>
                              <input type="hidden" name="thurs_time_slot" value="@if(!empty($adminTimeSlots[4]['id'])){{ $adminTimeSlots[4]['id'] }}@endif">
                              <select name="thurs_start_time" id="thurs_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[4]['status']) && $adminTimeSlots[4]['status']==5 && $adminTimeSlots[4]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[4]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[4]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);?>
                                      <option value="{{ $optionvalue }}" {{ old('thurs_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="thurs_start_time" name="thurs_start_time" value="{{ old('thurs_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="thurs_end_time" id="thurs_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[4]['status']) && $adminTimeSlots[4]['status']==5 && $adminTimeSlots[4]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[4]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[4]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);?>
                                      <option value="{{ $optionvalue }}" {{ old('thurs_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" id="thurs_end_time" name="thurs_end_time" value="{{ old('thurs_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="thurs_slot_lenght" id="thurs_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="thurs_buffer_lenght" id="thurs_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                              <select name="thurs_from_time" id="thurs_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[4]['status']) && $adminTimeSlots[4]['status']==5 && $adminTimeSlots[4]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[4]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[4]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('thurs_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="thurs_to_time" id="thurs_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[4]['status']) && $adminTimeSlots[4]['status']==5 && $adminTimeSlots[4]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[4]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[4]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('thurs_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="thurs_status" id="thurs_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Friday</span>
                            </td>
                            <td>
                              <input type="hidden" name="fri_time_slot" value="@if(!empty($adminTimeSlots[5]['id'])){{ $adminTimeSlots[5]['id'] }}@endif">
                              <select name="fri_start_time" id="fri_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[5]['status']) && $adminTimeSlots[5]['status']==6 && $adminTimeSlots[5]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[5]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[5]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow);?>
                                      <option value="{{ $optionvalue }}" {{ old('fri_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" name="fri_start_time" id="fri_start_time" value="{{ old('fri_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="fri_end_time" id="fri_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[5]['status']) && $adminTimeSlots[5]['status']==6 && $adminTimeSlots[5]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[5]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[5]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('fri_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                    <?php   //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" name="fri_end_time" id="fri_end_time" value="{{ old('fri_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="fri_slot_lenght" id="fri_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="fri_buffer_lenght" id="fri_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                              <select name="fri_from_time" id="fri_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[5]['status']) && $adminTimeSlots[5]['status']==6 && $adminTimeSlots[5]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[5]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[5]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('fri_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="fri_to_time" id="fri_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[5]['status']) && $adminTimeSlots[5]['status']==6 && $adminTimeSlots[5]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[5]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[5]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow);?>
                                        <option value="{{ $optionvalue }}" {{ old('fri_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="fri_status" id="fri_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Saturday</span>
                            </td>
                            <td>
                              <input type="hidden" name="sat_time_slot" value="@if(!empty($adminTimeSlots[6]['id'])){{ $adminTimeSlots[6]['id'] }}@endif">
                              <select name="sat_start_time" id="sat_start_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[6]['status']) && $adminTimeSlots[6]['status']==7 && $adminTimeSlots[6]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[6]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[6]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('sat_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>

                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" name="sat_start_time" id="sat_start_time" value="{{ old('sat_start_time') }}" required> --}}
                            </td>
                            <td>
                              <select name="sat_end_time" id="sat_end_time" class="form-control">
                              <?php
                                if(!empty($adminTimeSlots[6]['status']) && $adminTimeSlots[6]['status']==7 && $adminTimeSlots[6]['status'] ==1){
                                  $tNow = strtotime($adminTimeSlots[6]['start_time']);
                                  $end_time = strtotime($adminTimeSlots[6]['end_time']);
                                  while($tNow <= $end_time){
                                      $optionvalue = date("H:i",$tNow); ?>
                                      <option value="{{ $optionvalue }}" {{ old('sat_end_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>
                                      <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                      $tNow = strtotime('+5 minutes',$tNow);
                                  }
                                }
                              ?>
                              </select>
                                {{-- <input type="text" class="form-control timepicker" name="sat_end_time" id="sat_end_time" value="{{ old('sat_end_time') }}" required> --}}
                            </td>
                            <td>
                                <select name="sat_slot_lenght" id="sat_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="sat_buffer_lenght" id="sat_buffer_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($bufferDuration as $key1 => $buffer)
                                    <option value="{{ $key1 }}">{{$buffer}}</option>
                                  @endforeach
                                </select>
                            </td>
                            <td>
                              <select name="sat_from_time" id="sat_from_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[6]['status']) && $adminTimeSlots[6]['status']==7 && $adminTimeSlots[6]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[6]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[6]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('sat_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>

                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="sat_to_time" id="sat_to_time" class="form-control input-sm medium monday ">
                                <?php
                                  if(!empty($adminTimeSlots[6]['status']) && $adminTimeSlots[6]['status']==7 && $adminTimeSlots[6]['status'] ==1){
                                    $tNow = strtotime($adminTimeSlots[6]['start_time']);
                                    $end_time = strtotime($adminTimeSlots[6]['end_time']);
                                    while($tNow <= $end_time){
                                        $optionvalue = date("H:i",$tNow); ?>
                                        <option value="{{ $optionvalue }}" {{ old('sat_start_time') == $optionvalue ? 'selected' : '' }}>{{ $optionvalue }}</option>

                                        <?php //echo '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                        $tNow = strtotime('+5 minutes',$tNow);
                                    }
                                  }
                                ?>
                              </select>
                            </td>
                            <td>
                              <select name="sat_status" id="sat_status" class="form-control input-sm medium monday ">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                              </select>
                            </td>
                        </tr>
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
@endsection
