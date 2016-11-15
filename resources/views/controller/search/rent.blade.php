@extends('frontApp')

@section('meta-header')
    Search rent
@endsection
@section('content')

    <script src="/apps/frontApp/adv/search/searchAdvController.js"></script>

    <div ng-controller="searchAdvController">

        <div>
            <ol class="breadcrumb">
                <li><a href="/">{{ trans('main.home') }}</a></li>
                <li class="active">Search real estate</li>
            </ol>
        </div>
        <div class="progress" ng-show="env.loading==true">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">45% Complete</span>
            </div>
        </div>
        <form name="searchAdvForm"  ng-show="env.loading==false">
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Rent</h3>
                    </div>
                    <div class="panel-body">
                        <p>Please select the advertisement type, which you are looking for.
                            Based on your selection you will receive the required template.</p>
                        <div class="col-md-6">
                            @foreach($categories as $category)
                                @if ($category['is_sale_only']===false && $category['ic_business']===false)
                                    <div class="radio">
                                        <label>
                                            <input type="radio" ng-model="search.category" value="{{$category['id']}}">
                                            {{$category['title']}}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Business realty
                                </div>
                                <div class="panel-body">

                                    @foreach($categories as $category)
                                        @if ($category['is_sale_only']===false && $category['ic_business']===true)
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" ng-model="search.category" value="{{$category['id']}}">
                                                    {{$category['title']}}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <h4>Location</h4>
                <div class="col-md-12">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Zip, City</label>


                            <div class="alert alert-info alert-dismissible" role="alert" ng-show="!env.display_city_field">
                                <button ng-click="clearCityField()" type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                @if ($place)
                                {{$place->country.', '.$place->region.', '.$place->city.' ('.$place->zip.')'}}
                                    @endif
                            </div>
                            <city-select ng-model="env.address" radius="search.radius" cities="search.cities" >
                                <input type="text" class="form-control" ng-model="search.city">
                            </city-select>
                            <!--
                            <angucomplete-alt ng-show="env.display_city_field"
                                            id="au"
                                            minlength="1"
                                              placeholder="City"
                                              selected-object="env.address"
                                              remote-url="/api/search/cities/"
                                              remote-url-data-field="cities"
                                              title-field="city, zip"
                                              input-class="form-control"/>-->

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Radius</label>
                            <select class="form-control" ng-model="search.radius">
                                <option value="1">1 km</option>
                                <option value="5">5 km</option>
                                <option value="10">10 km</option>
                                <option value="15">15 km</option>
                                <option value="20">20 km</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div id="map" style="width:100%;height:500px"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-info alert-dismissible" role="alert"  ng-repeat="city in search.cities">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong ng-bind="city.name"></strong>
                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    banner
                </div>
            </div>
            <div ng-include="'apps/frontApp/adv/search/rentFlatSearchDetails.html'"  ng-if="search.category==1"></div>
            <div ng-include="'apps/frontApp/adv/search/rentHouseSearchDetails.html'"  ng-if="search.category==2"></div>
            <div ng-include="'apps/frontApp/adv/search/rentGarageSearchDetails.html'"  ng-if="search.category==3"></div>
            <div ng-include="'apps/frontApp/adv/search/rentOfficeSearchDetails.html'"  ng-if="search.category==4"></div>
            <div ng-include="'apps/frontApp/adv/search/rentHotelSearchDetails.html'"  ng-if="search.category==6"></div>
            <div ng-include="'apps/frontApp/adv/search/rentHallSearchDetails.html'"  ng-if="search.category==7"></div>
            <div ng-include="'apps/frontApp/adv/search/rentRetailSearchDetails.html'"  ng-if="search.category==8"></div>
            <div ng-include="'apps/frontApp/adv/search/rentCommercialLandSearchDetails.html'"  ng-if="search.category==9"></div>


        </div>

        <div class="row text-right">
            <button type="button" class="btn btn-primary"
                    ng-disabled="env.submit==true || !search.city_id"
                    ng-click="searchAdvs(search)">Search</button>        </div>
        </form>
    </div>
    <br style="clear: both">
@endsection