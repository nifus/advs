@extends('frontApp')

@section('meta-header')
    Password Reset
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>
                @if(!is_null($error)) Invalid password reset @else Password reset is complete @endif

            </h3></div>
        <div class="panel-body">
            @if(!is_null($error))
                <div class="alert alert-danger" role="alert">{{$error}}</div>
                @else
                We sent to you email new credentials.
            @endif


        </div>
    </div>

@endsection