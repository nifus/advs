@extends('frontApp')

@section('meta-header')
    {{trans('main.account_activation')}}
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>
                @if(!is_null($error))
                    {{trans('main.activation_invalid_message')}}
                @else
                    {{ trans('main.activation_complete_message') }}
                @endif

            </h3></div>
        <div class="panel-body">
            @if(!is_null($error))
                <div class="alert alert-danger" role="alert">{{$error}}</div>
                @else
                {{ trans('main.activation_complete_message_details') }}
            @endif
        </div>
    </div>
@endsection