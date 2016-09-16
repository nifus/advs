@extends('frontApp')

@section('meta-header')
    Account activation
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>
                @if(!is_null($error)) Invalid account activation @else Activate account is complete @endif

            </h3></div>
        <div class="panel-body">
            @if(!is_null($error))
                <div class="alert alert-danger" role="alert">{{$error}}</div>
                @else
                Now you can enjoy all the features of our website
            @endif


        </div>
    </div>

@endsection