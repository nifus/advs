@extends('frontApp')

@section('meta-header')
    {{ trans('main.agb-head')  }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><h3>{{ trans('main.AGB')  }}</h3></div>
        <div class="panel-body">
            <div class="col-md-12">
                <h4>{{ trans('main.agb-head')  }}</h4>
                <div style="white-space: pre">
                {{ config('app.agb') }}
                </div>
            </div>

        </div>
    </div>
@endsection