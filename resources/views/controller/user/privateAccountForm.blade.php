@extends('frontApp')

@section('meta-header')
    {{ trans('main.register_header') }}
@endsection

@section('content')
    <script
            src="https://www.google.com/recaptcha/api.js?onload=vcRecaptchaApiLoaded&render=explicit"
            async defer
    ></script>
    <script src="/apps/frontApp/register/registerController.js"></script>

    <div class="panel panel-info" ng-controller="registerController">
        <div class="panel-heading"><h3>{{trans('main.register')}}</h3></div>
        <div class="panel-body">


            <div class="col-md-9" ng-show="step=='last'">

                <h4>{{trans('main.register_complete')}}</h4>
                {!! trans('main.register_complete_desc') !!}
            </div>

            <div class="col-md-9" ng-show="step=='first'">

                <div class="alert alert-info" role="alert">
                    {!! trans('main.register_private_account_message',['link'=>route('register.business')]) !!}
                </div>

                <h4>{{ trans('main.register_private_header') }}</h4>

                <div class="alert alert-danger" role="alert" ng-show="error" ng-bind="error"></div>

                <form class="form-horizontal" name="register">

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

                    <div class="form-group" ng-class="{ 'has-error': register.name.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_forename') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.name" name="name"
                                   placeholder="Alexander" required minlength="2">

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.surname.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_surname') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.surname" name="surname"
                                   placeholder="Pushkin" required minlength="2">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': (register.email.$invalid && submit==true )  }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_email') }}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.email" name="email"
                                   placeholder="pushkin@gmail.com" required>

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.re_email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_email_re') }}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.re_email" name="re_email"
                                   placeholder="pushkin@gmail.com" required compare-to="form.email">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_password') }}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.password" name="password"
                                   placeholder="******" required minlength="6">
                            <p class="help-block">{{ trans('main.register_password_length') }}</p>

                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_password_re') }}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.re_password" name="re_password"
                                   placeholder="******" required ng-match="password">
                        </div>
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': form.captcha==undefined && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_captcha') }}</label>
                        <div class="col-sm-10">
                            <div
                                    on-create="setWidgetId(widgetId)"
                                    on-expire="cbExpiration()"
                                    ng-model="form.captcha"
                                    vc-recaptcha
                                    key="'{{config('services.google_captcha.public')}}'"
                            ></div>

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.agb.$invalid && submit==true }">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">

                            <textarea class="form-control" style="height: 200px" disabled>
                                {{ config('app.agb') }}
                            </textarea> <br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="agb" ng-model="form.agb"
                                       required> {{ trans('main.register_agb_accept') }}
                            </label>

                        </div>
                    </div>

                    <div style="text-align: center">
                        <button
                                ng-disabled="send==true"
                                type="button" ng-click="sendRegisterForm(form)"
                                class="btn btn-primary btn-lg">{{ trans('main.register_create_account_button') }}</button>
                    </div>

                </form>


            </div>

            <div class="col-md-3">
                {!! trans('main.register_private_desc') !!}
            </div>
        </div>
    </div>
@endsection