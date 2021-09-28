@extends('app')
@section('content')



        <div class="page-header">
          <h3 class="page-title"><a href="{{route('admin.home')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Postion  </h3>
          {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Tables</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data table</li>
            </ol>
          </nav>  --}}
         </div>
        @if (session()->has('success'))
            <h1>{{ session('success') }}</h1>
        @endif
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12 pull-right">
                <a href="{!! route('tr.createdToprated') !!} " class="btn btn-gradient-danger btn-md btn-fill pull-right" >Add New +</a>
                </div>
            </div>
          <br>
            <div class="row">

              <div class="col-12">
                <div class="row">
                  <div class="col-md-12">
                    <div class="bg-secondary p-4">
                      <div id="profile-list-left" class="py-2">
                        @csrf
                        @foreach ($toprated as $key => $toprate)
                          <div class="card rounded mb-2" id="{{ $key+1 }}" data-id="{{ $toprate->id }} ">
                            <div class="card-body p-3">
                              <div class="media" id="{{ $toprate->id }} ">
                                <div class="media-body">
                                  <p class="mb-0 text-muted">{{ $toprate->name_en }} </p>
                                  <p class="mb-0 text-muted">{{ $toprate->name_ar }} </p>
                                </div>
                                <a href="{{route('admin.home')}}" class='btn btn-sm btn-gradient-primary' title='Edit'><i class='fa fa-pencil'></i></a>
                                  <a href="{{route('admin.home')}}" class='btn btn-sm btn-gradient-danger deletecategory' title='Delete'><i class='fa fa-trash'></i></a>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

@endsection
@section('scripts')
<script>
(function($) {
  var elems = [];
  dragula([$("#profile-list-left").get(0)])
  .on("dragend", function(el, target, src) {
      elems = []; // reset elems
      $(".elem").each(function(idx, elem) {
      elems.push($(elem).data('id'));
  });
  console.log(elems);
  //ordering(elems,type);
  // validate elems are in correct order
  });
//   'use strict';
//   var iconTochange;
//   var elems = [];
//   dragula([document.getElementById("dragula-left"), document.getElementById("dragula-right")]);
//   dragula([document.getElementById("profile-list-left"), document.getElementById("profile-list-right")]);
//   dragula([document.getElementById("dragula-event-left"), document.getElementById("dragula-event-right")])
//     .on('dragend', function(el,target, src) {
//
//     })
 })(jQuery);
</script>
@endsection
