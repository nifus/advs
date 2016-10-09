@extends('frontApp')

@section('meta-header')
    Create Offer
@endsection

@section('content')
    <script src="/apps/frontApp/adv/createAdvController.js"></script>
    <div>
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Create advertisement</li>
        </ol>

        @if(is_null($user))
            <div class="alert alert-danger" role="alert">You need <a href="{{route('register.private')}}">create an
                    account</a> or <a href="">login</a> to an
                existing account
            </div>
        @endif

        <h4>Advert type</h4>
        <div ng-controller="createAdvController">
            <div class="row" id="step_1">
                <div class="col-md-3">
                    Please select your advertisement type
                </div>


                <div class="col-md-4">

                    <div class="header" style="background-color: orange;padding:20px;margin:0px;text-align: center">
                        <strong>Rent</strong></div>
                    <div class="type" style="background-color: lightyellow;padding:10px;margin:0px;height:120px">
                        <div class="radio">
                            <label>
                                <input type="radio" value="flat" ng-model="model.rent.category"
                                       ng-click="setType('rent')">
                                Flat
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" value="house" ng-model="model.rent.category"
                                       ng-click="setType('rent')">
                                House
                            </label>
                        </div>
                        <div class="radio ">
                            <label>
                                <input type="radio" value="garage" ng-model="model.rent.category"
                                       ng-click="setType('rent')">
                                Garage / car space
                            </label>
                        </div>
                    </div>
                    <div style="background-color: darkseagreen;padding:10px;margin:0px;">

                        <div class="checkbox" ng-class="{'disabled':model.type!='rent'}">
                            <label>
                                <input type="checkbox" ng-model="model.rent.is_business">
                                Business realty
                            </label>
                        </div>
                    </div>

                    <div style="background-color: lightyellow;padding:10px;margin:0px;">
                        <div class="radio" ng-class="{'disabled':!model.rent.is_business}">
                            <label>
                                <input type="radio" value="office" ng-model="model.rent.category">
                                Office / Praxis
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.rent.is_business}">
                            <label>
                                <input type="radio" value="gastronomy" ng-model="model.rent.category">
                                Gastronomy / Hotel
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.rent.is_business}">
                            <label>
                                <input type="radio" value="hall" ng-model="model.rent.category">
                                Hall / Production / Warehouse
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.rent.is_business}">
                            <label>
                                <input type="radio" value="retail_trade" ng-model="model.rent.category">
                                Retail trade
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.rent.is_business}">
                            <label>
                                <input type="radio" value="commercial_land" ng-model="model.rent.category">
                                Commercial land
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background-color: lightcoral;margin:0px;padding:20px;text-align: center">
                        <strong>Sale</strong></div>


                    <div class="type" style="background-color: oldlace;margin:0px;padding:10px;height:120px">
                        <div class="radio">
                            <label>
                                <input type="radio" value="flat" ng-model="model.sell.category"
                                       ng-click="setType('sell')">
                                Flat
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" value="house" ng-model="model.sell.category"
                                       ng-click="setType('sell')">
                                House
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" value="garage" ng-model="model.sell.category"
                                       ng-click="setType('sell')">
                                Garage / car space
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" value="building_ground" ng-model="model.sell.category"
                                       ng-click="setType('sell')">
                                Building ground
                            </label>
                        </div>
                    </div>
                    <div style="background-color: darkseagreen;margin:0px;padding:10px">

                        <div class="checkbox" ng-class="{'disabled':model.type!='sell'}">
                            <label>
                                <input type="checkbox" ng-model="model.sell.is_business">
                                Business realty
                            </label>
                        </div>
                    </div>

                    <div style="background-color: oldlace;margin:0px;padding:10px">
                        <div class="radio" ng-class="{'disabled':!model.sell.is_business}">
                            <label>
                                <input type="radio" value="office" ng-model="model.sell.category">
                                Office / Praxis
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.sell.is_business}">
                            <label>
                                <input type="radio" value="gastronomy" ng-model="model.sell.category">
                                Gastronomy / Hotel
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.sell.is_business}">
                            <label>
                                <input type="radio" value="hall" ng-model="model.sell.category">
                                Hall / Production / Warehouse
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.sell.is_business}">
                            <label>
                                <input type="radio" value="retail_trade" ng-model="model.sell.category">
                                Retail trade
                            </label>
                        </div>
                        <div class="radio" ng-class="{'disabled':!model.sell.is_business}">
                            <label>
                                <input type="radio" value="commercial_land" ng-model="model.sell.category">
                                Commercial land
                            </label>
                        </div>
                    </div>
                    <div class="text-right" style="padding:10px">
                        <button class="btn-primary" ng-disabled="!model.sell.category && !model.rent.category">Next
                        </button>
                    </div>

                </div>
            </div>


            <div class="row" id="step_2">
                <h3>Location</h3>
                <div class="col-md-8">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Street, House number</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" style="width:70%" class="form-control" placeholder="Street">
                                <input type="text" class="form-control" placeholder="House number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Zip, City</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" class="form-control" placeholder="Zip">
                                <input type="text" style="width:70%" class="form-control" placeholder="City">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Don‘t show the street and house number</label>
                            <div class="col-sm-10 ">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Walkway to public transport ca.</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" class="form-control" placeholder="minutes">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Driving time to the next central station ca.</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" class="form-control" placeholder="minutes">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Driving time to the next Autobahn ca.</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" class="form-control" placeholder="minutes">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Driving time to the next Airport ca.</label>
                            <div class="col-sm-10 form-inline">
                                <input type="text" class="form-control" placeholder="minutes">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-md-4">
                    map
                </div>
            </div>


            <div class="row">
                <h4>Basic Information</h4>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Build year</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="In year">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Living area</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="m2">
                        </div>
                    </div>

                    <div class="form-group form-inline">
                        <label class="col-sm-2 control-label">Floor</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Your Floor">
                            of
                            <input type="text" class="form-control" placeholder="All Floors">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Number of rooms</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Room/Rooms">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Number of garage/parking space</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Type of garage/parking space</label>
                        <div class="col-sm-10">
                            <select class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Pets</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" id="inlineRadio1" value="option1"> Any
                            </label>
                            <label class="radio-inline">
                                <input type="radio" id="inlineRadio2" value="option2"> Allowed
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Forbidden
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> By agreement
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Available from now on</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <h4>Finance</h4>

                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Cold rent (in &euro;)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="&euro;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ancillary cost(in &euro;)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="&euro;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Heating costs included in ancillary cost?</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> No
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Please enter the heating costs</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total cost (in &euro;)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Caution money (in &euro;)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <h4>Energy</h4>

                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Energy pass</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Available
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Not available
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Not specified
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Energy pass type</label>
                        <div class="col-sm-10">
                            <select class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Energy consumption value</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="kWh / (m2*a)">
                            <span class="help-block">kWh / (m2*a)</span>

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Heating system</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Any
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Central Heating
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="option3"> Self-contained heating
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <h4>Equipment</h4>
                <form class="form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option1"> Balcony/Terrace
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option2"> New building
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Build-in kitchen
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Garden (shared-use)
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Elevator
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Garage/parking space
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Stepless access
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Guest toilet
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="option3"> Cellar
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">

                <h4>Advertisement pictures</h4>
            </div>


            <div class="row form-horizontal">
                <h4>Advertisement description</h4>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title of your advertisement</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control"></textarea>
                    </div>
                </div>
            </div>


            <div class="row form-horizontal">
                <h4>Contact details</h4>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" value="option3"> Mister
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="option3"> Miss
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Forename</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Surname</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label">Phone number</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Contact only via email?</label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="option3">
                        </label>
                        <span class="help-block">Your phone number will not being displayed in the advert</span>

                    </div>
                </div>
            </div>
            <div class="row form-horizontal">
                <h4>
                    Finish
                </h4>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Yes, I noticed and accepted the AGB's</label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline">
                            <input type="checkbox" value="option3">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <br style="clear: both">
@endsection