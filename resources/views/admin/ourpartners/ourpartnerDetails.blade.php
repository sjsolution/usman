@extends('app')
@section('content')

<div class="page-header">
   <nav aria-label="breadcrumb"></nav>
</div>
<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box-body">
                        <table class="table">
                            
                                <h4>Our Partner's Deatails</h4>
                            
                            <tr >
                                <th>Name</th>
                                <th>اسم</th>
                                <th>Partner's image</th> 
                            </tr>
                            <tr>
                                <td>{{ $partner->partnername_en}}</td>
                            <td>{{$partner->partnername_ar}}</td>
                            <td><img src="{{config('app.AWS_URL').$partner->partner_image}}"/></td>
                            </tr>
                        </table>
                        <br>
                    <input type="button" class="btn btn-gradient-danger" onclick="window.history.back()" value="Back">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection