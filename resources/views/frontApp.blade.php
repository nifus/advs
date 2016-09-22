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
    <script src="/apps/core/cacheService.js"></script>

    <script src="/apps/core/user/userFactory.js"></script>
    <script src="/apps/core/user/userService.js"></script>

    <script src="/apps/frontApp/login/loginController.js"></script>

</head>
<body ng-app="frontApp" >
    {!!$composer_header_menu!!}

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
            <li><a href="{{ route('main') }}">{{ trans('main.home')  }}</a></li>
            <li><a href="{{ route('contacts') }}">{{ trans('main.contacts')  }}</a></li>
            <li><a href="{{route('agb')}}">{{ trans('main.AGB')  }}</a></li>
            <li><a href="{{ route('disclaimer') }}">{{ trans('main.disclaimer')  }}</a></li>
        </ul>
    </div>


    <script src="/components/ngAutocomplete/src/ngAutocomplete.js"></script>


</body>
</html>
