<div ng-controller="loginController">
    @if(is_null($user))
        <nav id="login" class="navbar navbar-default navbar-fixed-top hide" ng-class="{'show':display_form}">
            <div class="container">
                <span class="glyphicon glyphicon-remove link close" aria-hidden="true" ng-click="hideForm()"></span>
                <div class="login-form">
                    <div class="alert alert-danger" role="alert" ng-show="env.error" ng-bind="env.error"></div>
                    <div class="alert alert-success" role="alert" ng-show="env.message" ng-bind="env.message"></div>

                    <form class="form-inline" name="login_form" ng-show="form_type=='login'">
                        <div class="form-group" style="">
                            <input type="email" class="form-control" name="email" ng-model="form.email"
                                   placeholder="{{trans('main.email')}}" required>
                        </div>
                        <div class="form-group" style="height: 50px">
                            <input type="password" class="form-control" name="password" ng-model="form.password"
                                   placeholder="{{trans('main.password')}}" required>
                            <br>
                            <a class="link" ng-click="displayForgotForm()">{{trans('main.forgot_password')}}</a>
                        </div>
                        <input type="hidden" ng-model="form.remember" value="1">
                    <!--<div class="checkbox" >
                            <label>
                                <input type="checkbox" ng-model="form.remember"> {{trans('main.remember_me')}}
                            </label>
                        </div>-->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" ng-click="loginSubmit()"
                                    ng-disabled="login_form.$invalid || env.submit">{{trans('main.login')}}</button>
                        </div>
                    </form>
                    <form class="form-inline" name="forgot_form"
                          ng-show="form_type=='forgot' && display_reset_form==true">
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" ng-model="form.email"
                                   placeholder="{{trans('main.email')}}" required>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-primary" ng-click="forgotSubmit()"
                                    ng-disabled="forgot_form.$invalid || env.submit">{{trans('main.forgot_password')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </nav>
    @endif

    <div id="head">
        <div class="col-md-2 logo">
            @if( \Route::currentRouteName()!='main' )
                <img src="/images/logo.png" alt="">
            @endif
        </div>
        <div class="col-md-10 text-right">

            @if(is_null($user))
                <ul class="user-menu">
                    <li><a class="link" ng-click="displayLoginForm()"><span
                                    class="glyphicon glyphicon-user"></span> {{ trans('main.login')  }}</a></li>
                    <li><a href="{{route('register.private')}}">Sign In</a></li>
                </ul>
            @else
                <ul class="user-menu">
                    <li><a href="{{ route('user.dashboard') }}" class="link">{{ trans('main.profile')  }}</a></li>
                    <li><a ng-click="logout()" class="link">{{ trans('main.logout')  }}</a></li>
                </ul>
            @endif
            <ul class="main-menu">
                <li><a href="/">Home</a></li>
                <li><a href="{{ route('adv.rent') }}">{{ trans('main.rent')  }}</a></li>
                <li><a href="{{ route('adv.sale') }}">{{ trans('main.buy')  }}</a></li>
                <li><a href="{{ route('adv.offer') }}">{{ trans('main.offer')  }}</a></li>
            </ul>

            @if( !is_null($cityDetect) )
                <city-detect city="{{$cityDetect->city}}"></city-detect>
            @endif
        </div>
        <h1>ImmoStern</h1>
    </div>
</div>
