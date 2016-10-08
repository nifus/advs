@extends('frontApp')

@section('meta-header')
    {{ $adv->title }}
@endsection

@section('content')
    <script src="/apps/frontApp/directives/photoBlock/photoBlock.js"></script>
    <div>
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">{{$adv->title}}</li>
        </ol>

        <div style="clear:both;background-color: #4472c4;color:white;height:40px;">
            <div style="width:50%;float:left">ID: {{$adv->id}}</div>
            <button class="btn btn-primary" style="float:right">Back</button>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">B i l d e r</div>
                <div class="col-md-6">K a r t e</div>
            </div>

            <div class="row" id="photo-block" photo-block>
                <div class="col-md-12 main">
                    <img src="{{$adv->MainPhoto}}" alt="{{$adv->title}}">
                    <div class="back navigate hide"></div>
                    <div class="next navigate hide"></div>
                </div>

                <div class="preview col-md-12">
                    @foreach($adv->LastPhotos as $photo)
                    <img class="active" src="{{$photo}}" alt="{{$adv->title}}" >
                    @endforeach
                </div>

            </div>

            <div class="row">
                <div class="col-md-5">
                    <h4>Vendor</h4>
                    <table class="table">
                        <tr><td>Contact person:</td><td></td></tr>
                        <tr><td>Phone:</td><td></td></tr>
                        <tr><td>Mobile 1:</td><td></td></tr>
                        <tr><td>Email:</td><td></td></tr>
                    </table>
                </div>
                <div class="col-md-7">
                    <h4>Contact</h4>


                    <form class="form-horizontal">
                        <div class="form-group" ng-class="{ 'has-error': register.sex.$invalid && submit==true }">
                            <label class="col-sm-2 control-label">{{ trans('main.register_title') }}</label>
                            <div class="col-sm-10">
                                <label class="radio-inline">
                                    <input type="radio" name="sex" ng-model="form.sex" value="male"
                                           required> {{ trans('main.register_man') }}
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sex" ng-model="form.sex" value="female"
                                           required> {{ trans('main.register_woman') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your name</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your phone</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your email</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Send message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4>{{$adv->title}}</h4>

            <div class="row">
                <div class="col-md-6">
                    <table style="width: 100%">
                        <tr>
                            <td>City:</td><td></td>
                        </tr>
                        <tr>
                            <td>ZIP:</td><td></td>
                        </tr>
                        <tr>
                            <td>Street / House number:</td><td></td>
                        </tr>
                        <tr>
                            <td>Building type:</td><td></td>
                        </tr>
                        <tr>
                            <td>Build year:</td><td></td>
                        </tr>
                        <tr>
                            <td>Floor:</td><td></td>
                        </tr>
                        <tr>
                            <td>Rooms:</td><td></td>
                        </tr>
                        <tr>
                            <td>Living area:</td><td></td>
                        </tr>
                        <tr>
                            <td>Method of heating:</td><td></td>
                        </tr>
                        <tr>
                            <td>Number of Garage / p:</td><td></td>
                        </tr>
                        <tr>
                            <td>Garage / parking place:</td><td></td>
                        </tr>
                        <tr>
                            <td>Pets:</td><td></td>
                        </tr>
                        <tr>
                            <td>Move in ready:</td><td></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table">
                        <tr class="info">
                            <th colspan="2">Finance</th>
                        </tr>
                        <tr class="info"><td>Cold rent:</td><td></td></tr>
                        <tr class="info"><td>Ancillary cost:</td><td></td></tr>
                        <tr class="info"><td>Heating cost:</td><td></td></tr>
                        <tr class="info"><td>Total rent:</td><td></td></tr>
                        <tr class="info"><td>Caution money:</td><td></td></tr>
                    </table>

                    <table class="table">
                        <tr class="warning">
                            <th colspan="2">Location</th>
                        </tr>
                        <tr class="warning"><td>Walkway to public transport ca.:.</td><td></td></tr>
                        <tr class="warning"><td>Driving time to the next central station ca.:</td><td></td></tr>
                        <tr class="warning"><td>Driving time to the next Autobahn ca.:</td><td></td></tr>
                        <tr class="warning"><td>Driving time to the next Airport ca.:</td><td></td></tr>
                    </table>

                    <table class="table">
                        <tr class="success">
                            <th colspan="2">Energy</th>
                        </tr>
                        <tr class="success"><td>Energy pass</td><td></td></tr>
                        <tr class="success"><td>Energy pass type</td><td></td></tr>
                        <tr class="success"><td>Energy consumption value</td><td></td></tr>
                    </table>
                </div>
            </div>

            <h4>Equipment</h4>
            <div class="row">
                <div class="col-md-1">Balcony / Terrace</div>
                <div class="col-md-1">New building</div>
                <div class="col-md-1">Built-in kitchen</div>
                <div class="col-md-1">Central heating</div>
                <div class="col-md-1">Garden (-shared use)</div>
                <div class="col-md-1">Elevator</div>
                <div class="col-md-1">Garage / parking space</div>
                <div class="col-md-1">Self-contained heating</div>
                <div class="col-md-1">Stepless access</div>
                <div class="col-md-1">Guest toilet</div>
                <div class="col-md-1">Cellar</div>
            </div>


            <h4>Description</h4>
            {!! $adv->desc !!}



            <div>ADV</div>
        </div>
    </div>
    <br style="clear: both">
@endsection