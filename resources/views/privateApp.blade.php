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
    <link href="/components/alertify.js/themes/alertify.bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/components/alertify.js/themes/alertify.core.css" rel="stylesheet" type="text/css">



    <script src="/components/angular/angular.min.js"></script>
    <script src="/components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>


    <script src="/components/angular-recaptcha/release/angular-recaptcha.min.js"></script>
    <script src="/components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/components/satellizer/dist/satellizer.min.js"></script>
    <script src="/components/moment/min/moment.min.js"></script>
    <script src="/components/angular-bootstrap/ui-bootstrap-tpls-0.12.1.min.js"></script>
    <script src="/components/angular-validation-match/dist/angular-validation-match.min.js"></script>
    <script src="/components/alertify.js/lib/alertify.min.js"></script>

    <script src="/apps/core/core.js"></script>
    <script src="/apps/core/cacheService.js"></script>

    <script src="/apps/core/user/userFactory.js"></script>
    <script src="/apps/core/user/userService.js"></script>

    <script src="/apps/core/news/newsFactory.js"></script>
    <script src="/apps/core/news/newsService.js"></script>


    <script src="/apps/core/adv/advFactory.js"></script>
    <script src="/apps/core/adv/advService.js"></script>

    <script src="/apps/privateApp/privateApp.js"></script>
    <script src="/apps/privateApp/login/loginController.js"></script>
    <script src="/apps/privateApp/dashboard/dashboardController.js"></script>
    <script src="/apps/privateApp/mainController.js"></script>
    <script src="/apps/privateApp/adv/myController.js"></script>

    <script src="/apps/privateApp/adv/myWatchListController.js"></script>
    <script src="/apps/privateApp/settings/settingsController.js"></script>
    <script src="/apps/privateApp/deleteAccount/deleteAccountController.js"></script>





</head>
<body ng-app="privateApp" ng-controller="mainController" >
    {!!$composer_header_menu!!}

    <div id="main-menu" >
        <div class="row">
            <div class="item col-md-4" ><a href="{{ route('adv.rent') }}" >{{ trans('main.rent')  }}</a></div>
            <div class="item col-md-4"><a href="{{ route('adv.buy') }}" >{{ trans('main.buy')  }}</a></div>
            <div class="item col-md-4"><a href="{{ route('adv.offer') }}" >{{ trans('main.offer')  }}</a></div>
        </div>
    </div>

    <div id="content" ui-view ></div>

    <div id="footer-menu">
        <ul>
            <li><a href="{{ route('main') }}">{{ trans('main.home')  }}</a></li>
            <li><a href="{{ route('contacts') }}">{{ trans('main.contacts')  }}</a></li>
            <li><a href="{{route('agb')}}">{{ trans('main.AGB')  }}</a></li>
            <li><a href="{{ route('disclaimer') }}">{{ trans('main.disclaimer')  }}</a></li>
        </ul>
    </div>


    <script src="/components/ngAutocomplete/src/ngAutocomplete.js"></script>


    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</body>
</html>
