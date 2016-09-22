@extends('frontApp')

@section('meta-header')
    {{trans('main.password_reset')}}
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>
                @if(!is_null($error))
                    {{trans('main.password_reset_invalid_message')}}
                @else
                    {{trans('main.password_reset_complete_message')}}
                @endif

            </h3></div>
        <div class="panel-body">
            @if(!is_null($error))
                <div class="alert alert-danger" role="alert">{{$error}}</div>
                @else
                {{ trans('main.password_reset_sent_message') }}
            @endif


        </div>
    </div>

@endsection