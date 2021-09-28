@extends('app')
@section('content')
<div class="page-header"></div>
  <section class="content pad-20">
      <div class="card"> 
        <div class="card-body">
            <h3 class="page-title">
                Notification Detials
            </h3>
      <div class="row">
        <div class="col-lg-12">
            <div class="box-body">
            
              <table class="table table-striped">
              <tr>
                <th>Title</th>
                <td><?php  echo wordwrap($notification->title_en,30,"<br>\n", true); ?></td>
                <td><?php  echo wordwrap($notification->title_ar,30,"<br>\n", true); ?></td>
                <th class="rtl_text">عنوان</th>
       
              </tr>
              <tr>
                <th>Description</th>
                <td><?php  echo wordwrap($notification->description_en,30,"<br>\n", true); ?></td>
                <td><?php  echo wordwrap($notification->description_ar,30,"<br>\n", true); ?></td>
                <th class="rtl_text">وصف</th>
              </tr>
              <tr>
                <th>Send To</th>
                <td> @if($notification->send_to ==0)
                  <strong>All User</strong>
                  @elseif($notification->send_to == 1)
                      <ul>
                      @foreach ($notification->specificUser as $item)
                        <li>{{ $item->user->email }}</li>
                      @endforeach
                      </ul>
                  @endif
                </td> 
              </tr>
            

            </table><br>
              <input type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">
            </div>
            <!-- /.box-body -->
          </div>
          </div>
          </div>
                    <!-- /.box -->
</div>
</section>

@endsection
