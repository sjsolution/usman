@extends('app')
@section('content')
  <link rel="stylesheet" href="{{ asset('css/timepicker.css') }}">
  <script type="text/javascript" src="{{ asset('js/timepicker.js')}}"></script>
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
<div class="content-wrapper">
    <div class="page-header">

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
                    <h4 class="card-title">Timeslot Management</h4>
                    <table id="time-slot" class="table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Slot Duration</th>
                        </tr>
                    </thead>
                    <form class="cmxform" id="time_slot_form" method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    <tbody>
                        <tr>
                            <td>
                                <span >Sunday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="sun_start_time" id="sun_start_time" value="{{ old('sun_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="sun_end_time" id="sun_end_time" value="{{ old('sun_end_time') }}" required>
                            </td>
                            <td>
                                <select name="sun_slot_lenght" id="sun_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Monday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="mon_start_time" name="mon_start_time" value="{{ old('mon_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="mon_end_time" name="mon_end_time" value="{{ old('mon_end_time') }}" required>
                            </td>
                            <td>
                                <select name="mon_slot_lenght" id="mon_slot_lenght" class="form-control input-sm medium monday ">
                                  @foreach ($slotDuration as $key => $slots)
                                    <option value="{{ $key }}">{{$slots}}</option>
                                  @endforeach
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Tuesday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="tues_start_time" name="tues_start_time" value="{{ old('tues_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="tues_end_time" name="tues_end_time" value="{{ old('tues_end_time') }}" required>
                            </td>
                            <td>
                                <select name="tues_slot_lenght" id="tues_slot_lenght" class="form-control input-sm medium monday ">
                                    <option value="10">10 minutes</option>
                                        <option value="15">15 minutes</option>
                                        <option value="20">20 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option selected="selected" value="60">1 hour</option>
                                        <option value="120">2 hours</option>
                                        <option value="180">3 hours</option>
                                        <option value="240">4 hours</option>
                                        <option value="300">5 hours</option>
                                        <option value="360">6 hours</option>
                                        <option value="420">7 hours</option>
                                        <option value="480">8 hours</option>
                                        <option value="540">9 hours</option>
                                        <option value="600">10 hours</option>
                                        <option value="660">11 hours</option>
                                        <option value="720">12 hours</option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Wednesday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="wed_start_time" name="wed_start_time" value="{{ old('wed_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="wed_end_time" name="wed_end_time" value="{{ old('wed_end_time') }}" required>
                            </td>
                            <td>
                                <select name="wed_slot_lenght" id="wed_slot_lenght" class="form-control input-sm medium">
                                    <option value="10">10 minutes</option>
                                    <option value="15">15 minutes</option>
                                    <option value="20">20 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option selected="selected" value="60">1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="300">5 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="420">7 hours</option>
                                    <option value="480">8 hours</option>
                                    <option value="540">9 hours</option>
                                    <option value="600">10 hours</option>
                                    <option value="660">11 hours</option>
                                    <option value="720">12 hours</option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Thursday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="thurs_start_time" name="thurs_start_time" value="{{ old('thurs_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" id="thurs_end_time" name="thurs_end_time" value="{{ old('thurs_end_time') }}" required>
                            </td>
                            <td>
                                <select name="thurs_slot_lenght" id="thurs_slot_lenght" class="form-control input-sm medium monday ">
                                    <option value="10">10 minutes</option>
                                    <option value="15">15 minutes</option>
                                    <option value="20">20 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option selected="selected" value="60">1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="300">5 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="420">7 hours</option>
                                    <option value="480">8 hours</option>
                                    <option value="540">9 hours</option>
                                    <option value="600">10 hours</option>
                                    <option value="660">11 hours</option>
                                    <option value="720">12 hours</option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Friday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="fri_start_time" id="fri_start_time" value="{{ old('fri_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="fri_end_time" id="fri_end_time" value="{{ old('fri_end_time') }}" required>
                            </td>
                            <td>
                                <select name="fri_slot_lenght" id="fri_slot_lenght" class="form-control input-sm medium monday ">
                                    <option value="10">10 minutes</option>
                                    <option value="15">15 minutes</option>
                                    <option value="20">20 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option selected="selected" value="60">1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="300">5 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="420">7 hours</option>
                                    <option value="480">8 hours</option>
                                    <option value="540">9 hours</option>
                                    <option value="600">10 hours</option>
                                    <option value="660">11 hours</option>
                                    <option value="720">12 hours</option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span class="margin8">Saturday</span>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="sat_start_time" id="sat_start_time" value="{{ old('sat_start_time') }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control timepicker" name="sat_end_time" id="sat_end_time" value="{{ old('sat_end_time') }}" required>
                            </td>
                            <td>
                                <select name="sat_slot_lenght" id="sat_slot_lenght" class="form-control input-sm medium monday ">
                                    <option value="10">10 minutes</option>
                                    <option value="15">15 minutes</option>
                                    <option value="20">20 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option selected="selected" value="60">1 hour</option>
                                    <option value="120">2 hours</option>
                                    <option value="180">3 hours</option>
                                    <option value="240">4 hours</option>
                                    <option value="300">5 hours</option>
                                    <option value="360">6 hours</option>
                                    <option value="420">7 hours</option>
                                    <option value="480">8 hours</option>
                                    <option value="540">9 hours</option>
                                    <option value="600">10 hours</option>
                                    <option value="660">11 hours</option>
                                    <option value="720">12 hours</option>
                                </select>
                            </td>

                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <button id="submit" class="btn btn-primary" autocomplete="off" value="Submit" name="submit" tabindex="9" type="submit"><i class="fa fa-fw fa-save"></i>&nbsp;&nbsp;Save</button>
                            </td>
                        </tr>
                    </tfoot>
                    </form>
    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

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
