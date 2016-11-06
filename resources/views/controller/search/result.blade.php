@extends('frontApp')

@section('meta-header')
    Search result
@endsection
@section('content')

    <script src="/apps/frontApp/adv/search/searchAdvController.js"></script>
    <div ng-controller="searchAdvController">
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

                <div class="row">
                    <div class="col-md-5">
                        Results per page
                    </div>
                    <div class="col-md-2">Map</div>

                    <div class="col-md-5">
                        Sort by
                        <select name="" id=""></select>   
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection