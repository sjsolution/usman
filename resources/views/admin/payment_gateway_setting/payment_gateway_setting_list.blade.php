@extends('app')
@section('content')
<div class="page-header">
  <nav aria-label="breadcrumb"></nav>
</div>

@if (session()->has('success'))
  <h1>{{ session('success') }}</h1>
@endif
<style>
    .dataTables_wrapper.dt-bootstrap4.no-footer > :first-child {
        position: fixed;
        width: 76%;
        z-index: 99;
        height: 66px;
        padding-top: 60px;
        padding-bottom: 43px;
        background: #fff;
        margin-bottom: 58px !important;
    }
    .banner_row {
        background: white;
        height:25px;
        width: 76%;
        z-index: 999;
        position: fixed;
        padding-bottom: 10px;
        padding-top: 20px;
    }
    .card .card-body {
        padding: 0px 20px!important;
    }
    table#users-table {
        margin-top: 120px !important;
    }
</style>
<div class="card">
  <div class="card-body">

    <form id="search" onSubmit="return false">
        <div class="banner_row">
      <div class="row">
        <div class="col-md-3">
            <h3 class="page-title"> Payment Gateway Setting  </h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-3 pull-right">
          <a id="add_banner" href="{{route('paymentgateway.setting')}}" class="btn btn-gradient-danger btn-md btn-fill pull-right">Manage Payment +</a>
        </div>
          <div class="col-md-2"></div>
      </div>
        </div>
    </form>
    <br>
    <table class="table table-hover "  id="users-table">
      <thead>
          <tr>
            <th>Name</th>
            <th>اسم</th>
            <th>Satus</th>
          </tr>
      </thead>
      <tbody id="category-list">
          <tr>
            <td colspan="6" style="text-align:center;">
                <i class="fa fa-refresh fa-spin fa fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </td>
          <tr>
      </tbody>
    </table>
  </div>
</div>

@endsection
@section('scripts')

<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".banner_row").css('margin-top', '-4px');
        } else {
            $(".banner_row").css('margin-top', '0px');
        }
    });
$(function() {

 $('#users-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route('paymentgateway.getlist') !!}',
    "columns": [
       {data: 'name_en', name: 'name_en'},
       {data: 'name_ar', name: 'name_ar'},
       {data: 'status', name: 'status'},
    ],
    "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
    "iDisplayLength": 50,
    // "bStateSave": true,
    // "fnStateSave": function (oSettings, oData) {
    //     localStorage.setItem('offersDataTables', JSON.stringify(oData));
    // },
    // "fnStateLoad": function (oSettings) {
    //     return JSON.parse(localStorage.getItem('offersDataTables'));
    // }
    initComplete: function () {

    }

 });

});

 </script>
@endsection
