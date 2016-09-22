@if(is_null($user))
    <div ng-controller="loginController">
        <nav id="login" class="navbar navbar-default navbar-fixed-top hide"  ng-class="{'show':env.display_form}">
            <div class="container">
                <span class="glyphicon glyphicon-remove link close" aria-hidden="true"  ng-click="hideForm()"></span>
                <div class="login-form">
                    <div class="alert alert-danger" role="alert" ng-show="env.error" ng-bind="env.error"></div>
                    <div class="alert alert-success" role="alert" ng-show="env.message" ng-bind="env.message"></div>

                    <form class="form-inline" name="login_form" ng-show="env.form=='login'">
                        <div class="form-group" style="">
                            <input type="email" class="form-control" name="email" ng-model="form.email"  placeholder="{{trans('main.email')}}" required>
                        </div>
                        <div class="form-group" style="height: 50px">
                            <input type="password" class="form-control" name="password" ng-model="form.password"  placeholder="{{trans('main.password')}}" required>
                            <br>
                            <a class="link" ng-click="displayForgotForm()">{{trans('main.forgot_password')}}</a>
                        </div>
                        <div class="checkbox" >
                            <label>
                                <input type="checkbox" ng-model="form.remember"> {{trans('main.remember_me')}}
                            </label>
                        </div>
                        <div class="form-group" >
                            <button type="button" class="btn btn-primary" ng-click="loginSubmit()" ng-disabled="login_form.$invalid || env.submit">{{trans('main.login')}}</button>
                        </div>
                    </form>
                    <form class="form-inline" name="forgot_form" ng-show="env.form=='forgot' && env.display_reset_form==true">
                        <div class="form-group" >
                            <input type="email" class="form-control" name="email" ng-model="form.email"  placeholder="{{trans('main.email')}}" required>
                        </div>

                        <div class="form-group" >
                            <button type="button" class="btn btn-primary" ng-click="forgotSubmit()" ng-disabled="forgot_form.$invalid || env.submit">{{trans('main.forgot_password')}}</button>
                        </div>
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