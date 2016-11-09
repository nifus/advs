@extends('frontApp')

@section('meta-header')
    Search result
@endsection
@section('content')

    <script src="/apps/frontApp/directives/adv/adv.js"></script>
    <script src="/apps/frontApp/adv/searchResult/searchResultAdvController.js"></script>
    <div ng-controller="searchResultAdvController">
        <div>
            <ol class="breadcrumb">
                <li><a href="/">{{ trans('main.home') }}</a></li>
                <li><a href="{{ route('adv.'.$search->query->type,['id'=>$search->id]) }}">Search real estate</a></li>
                <li class="active">48643 results</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-2">
                48643 Flats found
                <hr>

            </div>
            <div class="col-md-10">
                <div >
                    <div class="col-md-5 text-left">

                        <div class="btn-toolbar" role="toolbar" >
                            <div class="btn-group" role="group" >
                                <label>Results per page</label>

                            </div>

                            <div class="btn-group" role="group" >
                                <button type="button" class="btn btn-default" ng-class="{'btn-primary':env.advs_on_page==5}" ng-click="env.advs_on_page=5">5</button>
                                <button type="button" class="btn btn-default" ng-class="{'btn-primary':env.advs_on_page==10}" ng-click="env.advs_on_page=10">10</button>
                                <button type="button" class="btn btn-default" ng-class="{'btn-primary':env.advs_on_page==20}" ng-click="env.advs_on_page=20">20</button>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2"><a href="">Map</a></div>

                    <div class="col-md-5 text-right">
                        Sort by
                        <select name="" id=""></select>
                    </div>
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>


                <div class="row">
                    <div class="col-md-6" ng-repeat="row in env.rows">
                        <adv data="row"></adv>
                    </div>
                </div>
                <br style="clear: both">

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection