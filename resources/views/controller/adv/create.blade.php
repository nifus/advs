@extends('frontApp')

@section('meta-header')
    Create Offer
@endsection
@section('content')
    <div>
        @if(is_null($user))
            <div class="alert alert-danger" role="alert">
                {!! trans('main.create_adv_need_register',['link'=>route('register.private')]) !!}
            </div>
        @else
            @verbatim
            <div class="row" ng-controller="createAdvController">
                <h1>Create Advert</h1>
                <div id="advert-form">
                    <div class="progress" ng-if="env.loading == true">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%">
                            <span class="sr-only">100% Complete</span>
                        </div>
                    </div>


                    <div ng-if="env.loading==false" class="animation">
                        <div class="alert alert-info" style="width:40%;margin-left: auto;margin-right:auto;text-align:center" role="alert"
                             translate ng-if="env.restore_flag==true">
                            You did not complete the previous announcement. We have restored it for you
                            <div class="row">
                                <div class="col-md-6 text-right">
                                    <button class="btn-default btn" translate ng-click="env.restore_flag = false">Continue this advert</button>
                                </div>
                                <div class="col-md-6 text-left">
                                    <button class="btn-default btn" translate ng-click="newAdvert()">Start new
                                        advert
                                    </button>
                                </div>
                            </div>
                        </div>
                        <adv-form ng-if="env.action=='form'" class="animation"  user="user" model="model" on-save="save"></adv-form>

                        <div ng-if="env.action=='payment'" class="animation">
                            <div class="alert alert-info" role="alert">
                                Your advert is created but not a public on the site. To complete publication you need select a duration and pay
                                it.
                            </div>

                            <h4 translate>Select service</h4>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" translate>Please select the wanted duration of your advert:</label>
                                <div class="col-sm-9">
                                    <ul class="select">
                                        <li ng-repeat="tariff in env.tariffs" ng-click="setPrivateTariff(tariff)"
                                            ng-class="{'active': env.tariff.id==tariff.id}">
                                            {{tariff.duration}} -
                                            {{ (model.type=='rent' ? tariff.rent_price : tariff.sale_price)|currency:"€" }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <payment-form
                                    ng-if="model.id"
                                    price="{{env.tariff.price}}" tariff="env.tariff" advert="model" type="advert" slots="1"
                                    user="$parent.user">

                            </payment-form>
                        </div>
                    </div>
                </div>
            </div>
            @endverbatim
        @endif
    </div>
    <br style="clear: both">
@endsection