@extends('frontApp')

@section('meta-header')
    Search rent
@endsection
@section('content')

    <script src="/apps/frontApp/adv/create/searchAdvController.js"></script>
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
                            Flat
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body">
                                    Business realty
                                </div>
                                <div class="panel-footer">

                                    Office / Praxis
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
            <div class="col-md-7">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Detailed search</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 form-horizontal">
                            <div class="col-md-4">
                                <label>Cold rent in €</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">From</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">To</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-horizontal">
                            <div class="col-md-4">
                                <label>Living area in m2</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">From</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">To</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 form-horizontal">
                            <div class="col-md-4">
                                <label>Rooms</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">From</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">To</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 form-horizontal">
                            <div class="col-md-4">
                                <label>Floor</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">From</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">To</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12 form-horizontal">
                            <div class="col-md-4">
                                <label>Parking place available</label>
                            </div>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Any
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> No
                                </label>
                            </div>

                        </div>

                        <div>
                            <h4>Building type</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Souterrain
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Loft
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Equipment</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Souterrain
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Loft
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Heating system</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Souterrain
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Loft
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4>Pets</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Souterrain
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Loft
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row text-right">
            <button type="button" class="btn btn-primary">Search</button>
        </div>
    </div>
    <br style="clear: both">
@endsection