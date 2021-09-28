@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    @if($error)
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @endif
                    @if($success)
                        <div class="alert alert-success" role="alert">
                            {{ $success }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
