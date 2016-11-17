@extends('frontApp')

@section('meta-header')
    Search result
@endsection
@section('content')

    <script src="/apps/frontApp/adv/searchResult/searchResultController.js"></script>
    <script src="/apps/frontApp/adv/searchResult/listing/searchResultListingController.js"></script>
    <script src="/apps/frontApp/adv/searchResult/view/searchResultViewController.js"></script>



    <div ng-controller="searchResultController">

        <div class="progress" ng-show="env.loading===true">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
            </div>
        </div>

        <div ng-include="'/apps/frontApp/adv/searchResult/listing/index.html'" ng-if="env.loading===false && env.adv_id==undefined"></div>
    </div>
@endsection