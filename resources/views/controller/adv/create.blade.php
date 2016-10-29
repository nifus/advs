@extends('frontApp')

@section('meta-header')
    Create Offer
@endsection
@section('content')

    <script src="/apps/frontApp/adv/create/createAdvController.js"></script>

    <div>
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Create advertisement</li>
        </ol>

        @if(is_null($user))
            <div class="alert alert-danger" role="alert">You need <a href="{{route('register.private')}}">create an
                    account</a> or <a ng-click="displayLoginForm()" class="link">login</a> to an
                existing account
            </div>
        @else
            <div ng-controller="createAdvController">
                <form class="form-horizontal" name="adv_form">
                    <div class="row">
                        <h4>Advert type</h4>

                        <div class="col-md-3">
                            Please select your advertisement type
                        </div>


                        <div class="col-md-4">

                            <div class="header"
                                 style="background-color: orange;padding:20px;margin:0px;text-align: center">
                                <strong>Rent</strong></div>
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
                                <div class="radio" ng-class="{'disabled':env.is_business_sell}">
                                    <label>
                                        <input type="radio" ng-model="env.is_business_rent"
                                               ng-click="isBusiness('rent')">
                                        Business realty
                                    </label>
                                </div>
                            </div>

                            <div style="background-color: lightyellow;padding:10px;margin:0px;">
                                @foreach($categories as $category)
                                    @if($category['ic_business']==true )
                                        <div class="radio" ng-class="{'disabled' : env.is_business_rent==0}">
                                            <label>
                                                <input type="radio"
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
                                <strong>Sale</strong></div>


                            <div class="type" style="background-color: oldlace;margin:0px;padding:10px;height:120px">
                                @foreach($categories as $category)
                                    @if($category['ic_business']==false  )
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="flat"
                                                       ng-checked="model.type=='sell' && model.category=={{$category['id']}}"
                                                       ng-click="setPrivateType('sell',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                            <div style="background-color: darkseagreen;margin:0px;padding:10px">

                                <div class="radio" ng-class="{'disabled':env.is_business_rent}">
                                    <label>
                                        <input type="radio" ng-model="env.is_business_sell"
                                               ng-click="isBusiness('sell')">
                                        Business realty
                                    </label>
                                </div>
                            </div>

                            <div style="background-color: oldlace;margin:0px;padding:10px">

                                @foreach($categories as $category)
                                    @if($category['ic_business']==true  )
                                        <div class="radio" ng-class="{'disabled' : env.is_business_sell==0}">
                                            <label>
                                                <input type="radio" value="flat"
                                                       ng-checked="model.type=='sell' && model.category=={{$category['id']}}"
                                                       ng-click="setBusinessType('sell',{{$category['id']}})">
                                                {{$category['title']}}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>


                        </div>
                    </div>


                    <div ng-include="'apps/frontApp/adv/create/flatForm.html'" ng-show="model.category"></div>


                    <div class="row form-horizontal" ng-show="model.category">
                        <h4>
                            Finish
                        </h4>

                        <div class="form-group" ng-class="{ 'has-error': adv_form.agb.$invalid && submit==true }">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">

                                <textarea class="form-control" style="height: 200px"
                                          disabled>{{ config('app.agb') }}</textarea> <br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="agb" ng-model="form.agb"
                                           required> {{ trans('main.register_agb_accept') }}
                                </label>

                            </div>
                        </div>

                        <div class="alert alert-danger" role="alert" ng-show="env.error">This is error</div>

                        <div class="form-group">

                            <div class="col-sm-10">
                                <div class="alert alert-warning" role="alert" ng-show="adv_form.agb.$invalid && submit==true">You did not fill in all fields</div>

                                <button class="btn btn-primary" type="button" ng-click="save(model)"
                                        ng-disabled="env.send==true">
                                    Send
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