@extends('frontApp')

@section('meta-header')
    {{trans('main.account_confirmation')}}
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>
                @if(!is_null($error))
                    {{trans('main.confirmation_invalid_message')}}
                @else
                    {{ trans('main.confirmation_complete_message') }}
                @endif

            </h3></div>
        <div class="panel-body">
            @if(!is_null($error))
                <div class="alert alert-danger" role="alert">{{$error}}</div>
                @else
                {{ trans('main.confirmation_complete_message_details') }}
            @endif
        </div>
    </div>
@endsection