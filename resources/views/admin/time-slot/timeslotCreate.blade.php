@extends('app')
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
        position:relative!important;
        z-index:9!important;

    }
    .timepicker .cell-4
    {
        font-size:13px!important;
    }

    .timepicker .icon-up, .timepicker .icon-down
    {
        width:36px!important;
        height:29px!important;

    }

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
    <!-- Content Header (Page header) -->
    <div class="page-header">
      <h3 class="page-title">Global Time Slot</h3>
      <nav aria-label="breadcrumb">
        {{-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Forms</a></li>
          <li class="breadcrumb-item active" aria-current="page">Form elements</li>
        </ol> --}}
      </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <form class="cmxform" id="formdata" method="POST" action="#" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">

                                <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Sun" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <input type="text" class="form-control timepicker" id="sun_start_time" name="start_time[]" value="{{ old('sun_start_time') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <input type="text" class="form-control timepicker" id="sun_end_time" name="end_time[]" value="{{ old('sun_end_time') }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                              <select name="time_duration[]" id="sun_slot_lenght" class="form-control input-sm medium monday " required>
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                            </div>
                        </div>
                    </div>
              <!-- Moday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Mon" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="mon_start_time" name="start_time[]" value="{{ old('mon_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="mon_end_time" name="end_time[]" value="{{ old('mon_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>
              <!-- Tuesday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Tue" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="tues_start_time" name="start_time[]" value="{{ old('tues_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="tues_end_time" name="end_time[]" value="{{ old('tues_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>

              <!-- Wednesday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Wed" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="wed_start_time" name="start_time[]" value="{{ old('wed_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="wed_end_time" name="end_time[]" value="{{ old('wed_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>
              <!-- Thursday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Thur" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="thurs_start_time" name="start_time[]" value="{{ old('thurs_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" id="thurs_end_time" name="end_time[]" value="{{ old('thurs_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>
              <!-- Friday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Fri" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="start_time[]" id="fri_start_time" value="{{ old('fri_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="end_time[]" id="fri_end_time" value="{{ old('fri_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>
              <!-- Saturday-->
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">

                          <input type="text" maxlength="50" class="form-control" name="day_name[]" value="Sat" placeholder="Name" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="start_time[]" id="sat_start_time" value="{{ old('sat_start_time') }}" required>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <input type="text" class="form-control timepicker" name="end_time[]" id="sat_end_time" value="{{ old('sat_end_time') }}" required>
                      </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group">
                        <select name="time_duration[]" id="mon_slot_lenght" class="form-control input-sm medium monday " required>
                            @foreach ($slotDuration as $key => $slots)
                              <option value="{{ $key }}">{{$slots}}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          <input id="toggle-demo" type="checkbox" data-toggle="toggle">
                      </div>
                  </div>
              </div>
              <input type="button" value="Submit" class="btn btn-gradient-danger mr-2" >
              {{-- <input type="button " class="btn btn-light" value="Cancel" > --}}
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
     if($("#formdata").parsley().validate()){
        $(this).attr("disabled",true);
       $('#formdata').submit();
    }
   });
  </script>
  <script>
      $(document).ready(function(){
          $('#mon_start_time').timepicker();
          $('#tues_start_time').timepicker();
          $('#wed_start_time').timepicker();
          $('#thurs_start_time').timepicker();
          $('#fri_start_time').timepicker();
          $('#sat_start_time').timepicker();
          $('#sun_start_time').timepicker();

          $('#mon_end_time').timepicker();
          $('#tues_end_time').timepicker();
          $('#wed_end_time').timepicker();
          $('#thurs_end_time').timepicker();
          $('#fri_end_time').timepicker();
          $('#sat_end_time').timepicker();
          $('#sun_end_time').timepicker();

      })
      </script>
@endsection
