@extends('frontApp')

@section('meta-header')
    Create Offer
@endsection
@section('content')

    <script src="/apps/frontApp/adv/create/createAdvController.js"></script>

    <div>
        <ol class="breadcrumb">
            <li><a href="/" >{{ trans('main.home') }}</a></li>
            <li class="active" >{{ trans('main.create_adv') }}</li>
        </ol>

        @if(is_null($user))
            <div class="alert alert-danger" role="alert" >
                {!! trans('main.create_adv_need_register',['link'=>route('register.private')]) !!}
            </div>
        @else
            <div ng-controller="createAdvController">
                <form class="form-horizontal" name="adv_form">
                    <div class="row">
                        <h4 >{{ trans('main.create_adv_type') }}</h4>

                        <div class="col-md-3" >
                            {{ trans('main.create_adv_select') }}
                        </div>


                        <div class="col-md-4">

                            <div class="header"
                                 style="background-color: orange;padding:20px;margin:0px;text-align: center">
                                <strong >{{ trans('main.create_adv_rent') }}</strong></div>
                            <div class="type"
                                 style="background-color: lightyellow;padding:10px;margin:0px;height:120px">
                                @foreach($categories as $category)
                                    @if($category['ic_business']==false && $category['is_sale_only']==false )
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="flat"

                                                       ng-checked="model.type=='rent' && model.category=={{$category['id']}}"
                                                       ng-click="setPrivateType('rent',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                            <div style="background-color: darkseagreen;padding:10px;margin:0px;">
                                <div class="radio" ng-class="{'disabled':env.is_business_sale}">
                                    <label>
                                        <input type="radio" ng-model="env.is_business_rent"
                                               ng-disabled="env.is_business_sale"
                                               ng-click="isBusiness('rent')">
                                        {{ trans('main.create_adv_business') }}
                                    </label>
                                </div>
                            </div>

                            <div style="background-color: lightyellow;padding:10px;margin:0px;">
                                @foreach($categories as $category)
                                    @if($category['ic_business']==true )
                                        <div class="radio" ng-class="{'disabled' : env.is_business_rent==0}">
                                            <label>
                                                <input type="radio"
                                                       ng-disabled="env.is_business_rent==0"
                                                       ng-checked="model.type=='rent' && model.category=={{$category['id']}}"
                                                       ng-click="setBusinessType('rent',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background-color: lightcoral;margin:0px;padding:20px;text-align: center">
                                <strong >{{ trans('main.create_adv_sale') }}</strong></div>


                            <div class="type" style="background-color: oldlace;margin:0px;padding:10px;height:120px">
                                @foreach($categories as $category)
                                    @if($category['ic_business']==false  )
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="flat"
                                                       ng-checked="model.type=='sale' && model.category=={{$category['id']}}"
                                                       ng-click="setPrivateType('sale',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                            <div style="background-color: darkseagreen;margin:0px;padding:10px">

                                <div class="radio" ng-class="{'disabled':env.is_business_rent}">
                                    <label>
                                        <input type="radio" ng-model="env.is_business_sale"
                                               ng-disabled="env.is_business_rent"
                                               ng-click="isBusiness('sale')">
                                        {{ trans('main.create_adv_business') }}
                                    </label>
                                </div>
                            </div>

                            <div style="background-color: oldlace;margin:0px;padding:10px">

                                @foreach($categories as $category)
                                    @if($category['ic_business']==true  )
                                        <div class="radio" ng-class="{'disabled' : env.is_business_sale==0}">
                                            <label>
                                                <input type="radio" value="flat"
                                                       ng-disabled="env.is_business_sale==0"
                                                       ng-checked="model.type=='sale' && model.category=={{$category['id']}}"
                                                       ng-click="setBusinessType('sale',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>


                        </div>
                    </div>


                    <div ng-include="'apps/frontApp/adv/create/rentFlatForm.html'"  ng-if="model.category==1 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentHouseForm.html'" ng-if="model.category==2 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentGarageForm.html'" ng-if="model.category==3 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentOfficeForm.html'" ng-if="model.category==4 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentHotelForm.html'" ng-if="model.category==6 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentHallForm.html'" ng-if="model.category==7 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentRetailForm.html'" ng-if="model.category==8 && model.type=='rent'"></div>
                    <div ng-include="'apps/frontApp/adv/create/rentCommercialLandForm.html'" ng-if="model.category==9 && model.type=='rent'"></div>

                    <div ng-include="'apps/frontApp/adv/create/saleFlatForm.html'"  ng-if="model.category==1 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleHouseForm.html'" ng-if="model.category==2 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleGarageForm.html'" ng-if="model.category==3 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleOfficeForm.html'" ng-if="model.category==4 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleBuildingForm.html'" ng-if="model.category==5 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleHotelForm.html'" ng-if="model.category==6 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleHallForm.html'" ng-if="model.category==7 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleRetailForm.html'" ng-if="model.category==8 && model.type=='sale'"></div>
                    <div ng-include="'apps/frontApp/adv/create/saleCommercialLandForm.html'" ng-if="model.category==9 && model.type=='sale'"></div>


                    <div class="row form-horizontal" ng-show="model.category">
                        <h4 >
                            {{ trans('main.create_adv_finish') }}
                        </h4>

                        <div class="form-group" ng-class="{ 'has-error': adv_form.agb.$invalid && env.submit==true }">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">

                                <textarea class="form-control" style="height: 200px"
                                          disabled>{{ config('app.agb') }}</textarea> <br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="agb" ng-model="model.agb"
                                           required> {{ trans('main.register_agb_accept') }}
                                </label>

                            </div>
                        </div>

                        <div class="alert alert-danger" role="alert" ng-show="env.error">This is error</div>

                        <div class="form-group">

                            <div class="col-sm-10">
                                <div class="alert alert-warning" role="alert"
                                     ng-show="adv_form.$invalid && env.submit==true" >{{trans('main.create_adv_not_fill_fields')}}
                                </div>

                                <button class="btn btn-primary" type="button" ng-click="save(model)"
                                        ng-disabled="env.send==true" >
                                    {{ trans('main.create_adv_send') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        @endif


    </div>

    </div>
    <br style="clear: both">
@endsection