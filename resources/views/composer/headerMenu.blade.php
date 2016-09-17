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