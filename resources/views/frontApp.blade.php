<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('meta-header')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/app.css" rel="stylesheet" type="text/css">
    <link href="/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="/components/angular/angular.min.js"></script>


    <script src="/components/angular-recaptcha/release/angular-recaptcha.min.js"></script>
    <script src="/components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/components/satellizer/dist/satellizer.min.js"></script>

    <script src="/apps/frontApp/frontApp.js"></script>
    <script src="/apps/core/core.js"></script>
    <script src="/apps/core/user/userFactory.js"></script>

    <script src="/apps/frontApp/login/loginController.js"></script>

</head>
<body ng-app="frontApp" >
@if(is_null($user))
    <div ng-controller="loginController">
        <nav id="login" class="navbar navbar-default navbar-fixed-top hide"  ng-class="{'show':env.display_form}">
            <div class="container">
            <span class="glyphicon glyphicon-remove link close" aria-hidden="true"  ng-click="hideLoginForm()"></span>
                <div class="login-form">
                    <div class="alert alert-danger" role="alert" ng-show="env.error" ng-bind="env.error"></div>

                    <form class="form-inline" name="login_orm">
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" ng-model="form.email"  placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" ng-model="form.password"  placeholder="Password" required>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" ng-model="form.remember"> Remember me
                            </label>
                        </div>
                        <button type="button" class="btn btn-primary" ng-click="loginSubmit()" ng-disabled="login_orm.$invalid || env.submit">Login</button>

                    </form>

                </div>
            </div>
        </nav>

        <div id="head">
            <div class="right" >
                <ul>
                    <li><a  class="link" ng-click="displayLoginForm()">{{ trans('main.login')  }}</a></li>
                    <li><a href="{{route('register.private')}}">{{ trans('main.register')  }}</a></li>
                </ul>
            </div>

            <h1>ImmoStern</h1>
        </div>
    </div>
    @else
    <div id="head" ng-controller="loginController">
        <div class="right" >
            <ul>
                <li><a ng-click="logout()" class="link">{{ trans('main.logout')  }}</a></li>
            </ul>
        </div>

        <h1>ImmoStern</h1>
    </div>
@endif

<div id="main-menu" >
    <div class="row">
        <div class="item col-md-4" ><a href="{{ route('adv.rent') }}" >{{ trans('main.rent')  }}</a></div>
        <div class="item col-md-4"><a href="{{ route('adv.buy') }}" >{{ trans('main.buy')  }}</a></div>
        <div class="item col-md-4"><a href="{{ route('adv.offer') }}" >{{ trans('main.offer')  }}</a></div>
    </div>
</div>

<div id="content" >
    @yield('content')
</div>

<div id="footer-menu">
    <ul>
        <li><a href="{{ route('contacts') }}">{{ trans('main.contacts')  }}</a></li>
        <li><a href="{{route('agb')}}">{{ trans('main.AGB')  }}</a></li>
        <li><a href="{{ route('disclaimer') }}">{{ trans('main.disclaimer')  }}</a></li>
    </ul>
</div>



</body>
</html>
