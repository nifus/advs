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


    </div>
@endsection