<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('meta-header')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans%3A400%2C400i%2C700%2C700i%7CPT+Sans+Narrow%3A400%2C700%7CPT+Serif" />
    <link href="/css/app.css" rel="stylesheet" type="text/css">

    <link href="/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/components/angular-bootstrap-datetimepicker/src/css/datetimepicker.css"/>
    <link rel="stylesheet" href="/components/angucomplete-alt/angucomplete-alt.css">
    <link href="/components/alertify.js/themes/alertify.core.css" rel="stylesheet" type="text/css">
    <link href="/components/alertify.js/themes/alertify.default.css" rel="stylesheet" type="text/css">


    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places,geometry&key=AIzaSyDhoywlfGZRVpt8hcYkJORK4ioyBeEIweU&language=de"></script>

    <script src="/components/jquery/dist/jquery.min.js"></script>
    <script src="/components/angular/angular.min.js"></script>
    <script src="/components/angular-animate/angular-animate.min.js"></script>

    <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>


    <script src="/components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="/components/angular-recaptcha/release/angular-recaptcha.min.js"></script>
    <script src="/components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/components/satellizer/dist/satellizer.min.js"></script>
    <script src="/components/checklist-model/checklist-model.js"></script>
    <script src="/components/moment/min/moment-with-locales.js"></script>

    <script type="text/javascript" src="/components/angular-bootstrap-datetimepicker/src/js/datetimepicker.js"></script>
    <script type="text/javascript" src="/components/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js"></script>
    <script src='/components/angular-gm/angular-gm.min.js'></script>
    <script src='/components/ng-file-upload/ng-file-upload-shim.min.js'></script>
    <script src='/components/ng-file-upload/ng-file-upload.min.js'></script>


    <script src='/components/angular-gettext/dist/angular-gettext.min.js'></script>
    <script src="/components/angucomplete-alt/dist/angucomplete-alt.min.js"></script>
    <script src="/components/angular-disable-all/dist/angular-disable-all.js"></script>
    <script src="/components/alertify.js/lib/alertify.min.js"></script>
    <script src="/components/angular-text-counter/src/textCounter.js"></script>
    <script src="/components/ngAutocomplete/src/ngAutocomplete.js"></script>
    <script src="/components/markerclustererplus/dist/markerclusterer.min.js"></script>
    <script src="/components/angular-bootstrap/ui-bootstrap.min.js"></script>
    <script src="/components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>


    <script src="/apps/core/core.js"></script>
    <script src="/apps/core/user/userFactory.js"></script>
    <script src="/apps/core/user/userService.js"></script>
    <script src="/apps/core/adv/advFactory.js"></script>
    <script src="/apps/core/adv/advService.js"></script>
    <script src="/apps/core/searchLog/searchLogFactory.js"></script>
    <script src="/apps/core/searchLog/searchLogService.js"></script>
    <script src="/apps/core/tariff/tariffFactory.js"></script>
    <script src="/apps/core/advPayment/advPaymentFactory.js"></script>
    <script src="/apps/core/tariff/businessTariffService.js"></script>
    <script src="/apps/core/advPayment/advPaymentService.js"></script>


    <script src="/apps/core/directives/advPreview/advPreview.js"></script>
    <script src="/apps/core/directives/advForm/advForm.js"></script>
    <script src="/apps/core/directives/paymentForm/paymentForm.js"></script>

    <script src="/apps/frontApp/frontApp.js"></script>
    <script src="/apps/frontApp/directives/citySelect/citySelect.js"></script>
    <script src="/apps/frontApp/directives/cityDetect/cityDetect.js"></script>
    <script src="/apps/frontApp/login/loginController.js"></script>
    <script src="/apps/frontApp/mainController.js"></script>
    <script src="/apps/frontApp/adv/create/createAdvController.js"></script>
    <script src="/apps/frontApp/adv/search/searchAdvController.js"></script>





</head>
<body ng-app="frontApp" ng-controller="mainController" >
    {!!$composer_header_menu!!}

    <!-- <div id="main-menu" >
        <div class="row">
            <div class="item col-md-4" ><a href="{{ route('adv.rent') }}" >{{ trans('main.rent')  }}</a></div>
            <div class="item col-md-4"><a href="{{ route('adv.sale') }}" >{{ trans('main.buy')  }}</a></div>
            <div class="item col-md-4"><a href="{{ route('adv.offer') }}" >{{ trans('main.offer')  }}</a></div>
        </div>
    </div>-->

    <div id="content" >

        @yield('content')
    </div>

    <div id="footer-menu">
        <h4>ImmoSterne.de</h4>
        <hr>
        <ul>
            <li><a href="{{ route('main') }}">{{ trans('main.home')  }}</a></li>
            <li><a href="{{ route('contacts') }}">{{ trans('main.contacts')  }}</a></li>
            <li><a href="{{route('agb')}}">{{ trans('main.AGB')  }}</a></li>
            <li><a href="{{ route('disclaimer') }}">{{ trans('main.disclaimer')  }}</a></li>
        </ul>
    </div>




</body>
</html>
