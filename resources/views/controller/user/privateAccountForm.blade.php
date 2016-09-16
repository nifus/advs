@extends('frontApp')

@section('meta-header')
    REGISTER
@endsection

@section('content')
    <script
            src="https://www.google.com/recaptcha/api.js?onload=vcRecaptchaApiLoaded&render=explicit"
            async defer
    ></script>
    <script src="/apps/frontApp/register/registerController.js"></script>

    <div class="panel panel-info" ng-controller="registerController">
        <div class="panel-heading"><h3>REGISTER</h3></div>
        <div class="panel-body">
            <div class="alert alert-info" role="alert">If you are a real estate agent / agency please create a <a
                        href="{{route('register.business')}}">business account</a></div>

            <div class="col-md-9"  ng-show="step=='last'">
                <h4>Thank you for registration.</h4>
                We have send an email to your email address.<br>
                Please click on the activation link inside this email.<br>
                Otherwise you are not able to login to your account.<br>
                This activation link is valid for the next 24 hours.<br>
            </div>
            <div class="col-md-9" ng-show="step=='first'">
                <h4>Account type: PRIVATE</h4>


                <form class="form-horizontal" name="register" >

                    <div class="form-group" ng-class="{ 'has-error': register.sex.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex"  value="male" required> Mister
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex" value="female" required> Miss
                            </label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.name.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Forename</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.name" name="name" placeholder="Alexander" required minlength="2">

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.surname.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.surname" name="surname" placeholder="Pushkin" required  minlength="2">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': (register.email.$invalid && submit==true ) || errors.email!=undefined }">
                        <label class="col-sm-2 control-label">Email address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.email" name="email" placeholder="pushkin@gmail.com" required>
                            <p class="help-block" ng-show="errors.email!=undefined">errors.email</p>

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.re_email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Repeat email address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.re_email" name="re_email" placeholder="pushkin@gmail.com" required  compare-to="form.email">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.password" name="password" placeholder="******" required minlength="6">
                            <p class="help-block">6 length minimum</p>

                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Repeat password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.re_password" name="re_password" placeholder="******" required ng-match="password">
                        </div>
                    </div>




                    <div class="form-group" ng-class="{ 'has-error': form.captcha==undefined && submit==true }">
                        <label class="col-sm-2 control-label">Captcha validate</label>
                        <div class="col-sm-10">
                            <div
                                    on-create="setWidgetId(widgetId)"
                                    ng-model="form.captcha"
                                    vc-recaptcha
                                    key="'6LfvECoTAAAAAPIObIeKOUQhEi6v2IB1ezIgac9j'"
                            ></div>

                        </div>
                    </div>

                    <div class="form-group"  ng-class="{ 'has-error': register.agb.$invalid && submit==true }">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">

                            <textarea   class="form-control" style="height: 200px" disabled>
                                1.1 Diese Allgemeinen Geschäftsbedingungen (nachfolgend „AGB“) der/des ALTERNATE GmbH (nachfolgend „Verkäufer“) gelten für alle Verträge, die ein Verbraucher oder Unternehmer (nachfolgend „Kunde“)
mit dem Verkäufer hinsichtlich der vom Verkäufer in dem vorliegenden Online-Shop dargestellten Waren und/oder Leistungen abschließt. Hiermit wird der Einbeziehung von eigenen Bedingungen des Kunden
widersprochen, es sei denn, es ist etwas anderes vereinbart worden.
1.2 Für den Erwerb von Gutscheinen gelten diese AGB entsprechend, sofern insoweit nicht ausdrücklich etwas Abweichendes geregelt ist.
1.3 Verbraucher im Sinne dieser AGB ist jede natürliche Person, die ein Rechtsgeschäft zu Zwecken abschließt, die überwiegend weder ihrer gewerblichen noch ihrer selbständigen beruflichen Tätigkeit
zugerechnet werden können. Unternehmer im Sinne dieser AGB ist jede
                            </textarea> <br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="agb" ng-model="form.agb"  required> AGB accept?
                            </label>

                        </div>
                    </div>

                    <div style="text-align: center">
                        <button
                                ng-disabled="send==true"
                                type="button" ng-click="sendRegisterForm(form)" class="btn btn-primary btn-lg">Create Account</button>
                    </div>

                </form>


            </div>

            <div class="col-md-3">
                A private account provide you the following advantages:
                <ul>
                    <li>No hidden costs, the useage is free!</li>
                    <li>Creation advertisements</li>
                    <li>Save used car advertisements to your personal watchlist</li>
                </ul>
            </div>
        </div>
        </div>

@endsection