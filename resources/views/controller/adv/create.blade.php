@extends('frontApp')

@section('meta-header')
    Create Offer
@endsection
@section('content')


    <div>
        <ol class="breadcrumb">
            <li><a href="/">{{ trans('main.home') }}</a></li>
            <li class="active">{{ trans('main.create_adv') }}</li>
        </ol>

        @if(is_null($user))
            <div class="alert alert-danger" role="alert">
                {!! trans('main.create_adv_need_register',['link'=>route('register.private')]) !!}
            </div>
        @else
            <div ng-controller="createAdvController">

                <div ng-if="env.loading==false">
                    <adv-form model="model" user="user" action="env.action"></adv-form>
                </div>
                <div class="progress" ng-if="env.loading == true">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"  style="width: 100%">
                        <span class="sr-only">100% Complete</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    </div>
    <br style="clear: both">
@endsection