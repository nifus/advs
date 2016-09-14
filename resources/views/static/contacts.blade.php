@extends('frontApp')

@section('meta-header')
    {{ trans('main.contacts')  }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><h3>{{ trans('main.contacts')  }}</h3></div>
        <div class="panel-body">
            <div class="col-md-12">
                <h4>{{ trans('main.agb-head')  }}</h4>

            </div>

        </div>
    </div>
@endsection