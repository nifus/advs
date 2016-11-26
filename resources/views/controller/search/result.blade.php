@extends('frontApp')

@section('meta-header')
    Search results
@endsection
@section('content')

    <script src="/apps/frontApp/adv/searchResult/searchResultController.js"></script>
    <script src="/apps/frontApp/directives/photoBlock/photoBlock.js"></script>



    <div ng-controller="searchResultController">

        <div

                ng-include="'/apps/frontApp/adv/searchResult/listing/index.html'"
                ng-if="env.adv_id==undefined">
        </div>

        <div

                ng-include="'/apps/frontApp/adv/searchResult/view/index.html'"
                ng-if="env.adv_id!=undefined">
        </div>
    </div>
@endsection