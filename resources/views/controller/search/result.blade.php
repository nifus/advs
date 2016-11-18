@extends('frontApp')

@section('meta-header')
    Search result
@endsection
@section('content')

    <script src="/apps/frontApp/adv/searchResult/searchResultController.js"></script>
    <script src="/apps/frontApp/adv/searchResult/listing/searchResultListingController.js"></script>
    <script src="/apps/frontApp/adv/searchResult/view/searchResultViewController.js"></script>
    <script src="/apps/frontApp/directives/photoBlock/photoBlock.js"></script>



    <div ng-controller="searchResultController">

        <div
                ng-controller="searchResultListingController"
                ng-include="'/apps/frontApp/adv/searchResult/listing/index.html'"
                ng-if="root.adv_id==undefined">
        </div>

        <div
                ng-controller="searchResultViewController"
                ng-include="'/apps/frontApp/adv/searchResult/view/index.html'"
                ng-if="root.adv_id!=undefined">
        </div>
    </div>
@endsection