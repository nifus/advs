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
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Radius</label>
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    map
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
                    ng-disabled="env.submit==true"
                    ng-click="searchAdvs(search)">Search</button>        </div>
    </div>
    <br style="clear: both">
@endsection