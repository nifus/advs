@extends('frontApp')

@section('meta-header')
    Search buy
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
                        <h3 class="panel-title">Buy</h3>
                    </div>
                    <div class="panel-body">
                        <p>Please select the advertisement type, which you are looking for.
                            Based on your selection you will receive the required template.</p>
                        <div class="col-md-6">
                            @foreach($categories as $category)
                                @if ( $category['ic_business']===false)
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
                                        @if ( $category['ic_business']===true)
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
                    <city-select ng-model="search" ></city-select>
                </div>


                <div class="col-md-12">
                    banner
                </div>
            </div>
            <div ng-include="'apps/frontApp/adv/search/saleFlatSearchDetails.html'"  ng-if="search.category==1"></div>
            <div ng-include="'apps/frontApp/adv/search/saleHouseSearchDetails.html'"  ng-if="search.category==2"></div>
            <div ng-include="'apps/frontApp/adv/search/saleGarageSearchDetails.html'"  ng-if="search.category==3"></div>
            <div ng-include="'apps/frontApp/adv/search/saleOfficeSearchDetails.html'"  ng-if="search.category==4"></div>
            <div ng-include="'apps/frontApp/adv/search/saleBuildingSearchDetails.html'"  ng-if="search.category==5"></div>
            <div ng-include="'apps/frontApp/adv/search/saleHotelSearchDetails.html'"  ng-if="search.category==6"></div>
            <div ng-include="'apps/frontApp/adv/search/saleHallSearchDetails.html'"  ng-if="search.category==7"></div>
            <div ng-include="'apps/frontApp/adv/search/saleRetailSearchDetails.html'"  ng-if="search.category==8"></div>
            <div ng-include="'apps/frontApp/adv/search/saleCommercialLandSearchDetails.html'"  ng-if="search.category==9"></div>


        </div>

        <div class="row text-right">
            <button type="button" class="btn btn-primary"
                    ng-disabled="env.submit==true || !search.lat"
                    ng-click="searchAdvs(search)">Search</button>        </div>
        </form>
    </div>
    <br style="clear: both">
@endsection