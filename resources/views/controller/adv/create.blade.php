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
                <div class="progress" ng-if="env.loading == true">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"  style="width: 100%">
                        <span class="sr-only">100% Complete</span>
                    </div>
                </div>




                <div ng-if="env.loading==false">



                    <adv-form ng-if="env.action=='form'" model="model"  on-save="save"></adv-form>



                    <div ng-if="env.action=='preview'">
                        <div class="panel panel-default">
                            <div class="panel-body text-right">
                                <button class="btn btn-default" type="button" ng-click="backToPayment()" translate>Back</button>

                            </div>
                        </div>
                        <adv-preview  adv="model"  user="user" hide-contact-form="true"></adv-preview>
                    </div>
                    <div  ng-if="env.action=='payment'" ng-include="'/apps/frontApp/adv/create/payment.html'"></div>

                </div>




            </div>
        @endif
    </div>
    <br style="clear: both">
@endsection