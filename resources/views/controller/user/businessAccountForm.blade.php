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
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>

    <div class="panel panel-info" ng-controller="registerController">
        <div class="panel-heading"><h3>BUSINESS</h3></div>
        <div class="panel-body">


            <div class="col-md-9"  ng-show="step=='last'">
                <h4>Thank you for registration.</h4>
                We have send an email to your email address.<br>
                Please click on the activation link inside this email.<br>
                Otherwise you are not able to login to your account.<br><br>

                <strong>After you click on this required
                activation
                can and start
                the are
                activation
                process for you.</strong>
                This link
                email we
                address
                password
                later your login
                This process takes one or two credentials<br><br>

                This activation link is valid for the next 24 hours.<br>
            </div>

            <div class="col-md-9" ng-show="step=='first'">
                <div class="alert alert-info" role="alert">If you are NOT a real estate agent / agency please create a <a
                            href="{{route('register.private')}}">private account</a></div>

                <div class="alert alert-danger" role="alert" ng-show="error" ng-bind="error"></div>

                <form class="form-horizontal" name="register" >
                    <h4>Company data</h4>

                    <div class="form-group" ng-class="{ 'has-error': register.company.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Company name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.company" name="company"   required minlength="2">
                            <p class="help-block">Please enter your legal company name according to
                                your VAT ID confirmation.</p>

                        </div>
                    </div>

                    <div class="form-group form-inline" ng-class="{ 'has-error': (register.country.$invalid || register.commercial_id.$invalid)  && submit==true }">
                        <label class="col-sm-2 control-label">Commercial register ID</label>
                        <div class="col-sm-10">



                            <select class="form-control" name="country" ng-model="form.commercial_country">
                                <option value="germany">DE</option>
                                <option value="austria">CHE</option>
                                <option value="switzerland">SW</option>
                            </select>
                            <input type="text" class="form-control" ng-model="form.commercial_id" name="commercial_id"  placeholder="DE000000000" required >

                            <select class="form-control" name="additional" ng-model="form.commercial_additional" ng-show="form.commercial.country=='switzerland'">
                                <option value="HR">HR</option>
                                <option value="MWST">MWST</option>
                                <option value="HR/MWST">HR/MWST</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Website</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.website" name="website"  >
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': ( (register.autocomplete.$invalid )  && submit==true )  }">
                        <label class="col-sm-2 control-label">Address</label>
                        <div class="col-sm-10">

                            <input type="text" class="form-control"  name="autocomplete" placeholder="Address"
                                   ng-autocomplete ng-model="autocomplete.value"  details="autocomplete.details"
                                   required >

                            <p class="help-block">Please enter the company data which is identical to
                                the address on your official VAT ID registration
                                document</p>


                        </div>
                    </div>

                    <div class="form-group"

                         ng-class="{ 'has-error': (register.zip.$invalid || register.city.$invalid || register.no.$invalid || register.street.$invalid) && submit==true }">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10 form-inline">
                            <input type="text" class="form-control" ng-model="form.address_street" name="street" placeholder="Street" required>
                            <input type="text" class="form-control" ng-model="form.address_number" name="no" placeholder="No." required>
                            <input type="text" class="form-control" ng-model="form.address_zip" name="zip" placeholder="Zip" required>
                            <input type="text" class="form-control" ng-model="form.address_city" name="city" placeholder="City" required>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">Additional address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.address_additional" name="additional" >
                        </div>
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': register.country.$invalid  && submit==true }">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="country" ng-model="form.commercial_country" required>
                                <option value="germany">Germany</option>
                                <option value="austria">Austria</option>
                                <option value="switzerland">Switzerland</option>
                            </select>
                        </div>
                    </div>

                    <h4>Login credentials</h4>
                    <div class="alert alert-info" role="alert"> This email address and password are later your login
                        credentials</div>



                    <div class="form-group" ng-class="{ 'has-error': register.email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">E-Mail address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.email" name="email"  required >

                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Repeat e-mail address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.re_email" name="re_email"  required ng-match="email">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.password" name="password"  required >
                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Repeat password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.re_password" name="re_password" required ng-match="password">
                        </div>
                    </div>


                    <h4>Contact person</h4>
                    <div class="alert alert-info" role="alert"> Please enter here the general manager of the
                        company. <br>
                        Later you can assign an other contact person which is
                         visible for all users in the account settings
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': register.sex.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex"  value="male" required> Mister
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex" value="female" required> Miss
                            </label>
                            <p class="help-block"></p>
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

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">Email address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.contact_email"  >
                            <p class="help-block">This is the personal email address of the contact
                                person (not the login email address)</p>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.phone" name="phone" >
                        </div>
                    </div>


                    <h4>Subscription</h4>

                    <div class="form-group" ng-class="{ 'has-error': register.tariff.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">Select you subscription</label>
                        <div class="col-sm-10">
                            <table class="table  table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Package</th>
                                    <th>Slots per month</th>
                                    <th>Price per month</th>
                                    <th>Each extra slot</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tariffs as $tariff)
                                <tr ng-click="form.tariff={{$tariff['id']}}" style="cursor: pointer">
                                    <td><input type="radio" value="{{$tariff['id']}}" name="tariff" ng-model="form.tariff" required></td>
                                    <td>{{$tariff['name']}}</td>
                                    <td>{{$tariff['slots']}}</td>
                                    <td>{{$tariff['price']}}</td>
                                    <td>{{$tariff['additional']}}</td>
                                </tr>
                                    @endforeach
                                </tbody>


                            </table>
                        </div>
                    </div>


                    <h4>Payment</h4>
                    <div class="form-group" ng-class="{ 'has-error': (form.payment_type==undefined || (form.payment_type=='paypal' && form.paypal_email==undefined )|| (form.payment_type=='giropay' && form.giro_account==undefined )) && submit==true }">
                        <label class="col-sm-2 control-label">Select your Payment</label>
                        <div class="col-sm-10">

                            <div class="radio">
                                <label>
                                    <input type="radio" value="paypal" ng-model="form.payment_type">
                                    PayPal <br>
                                    (you will be forwarded to PayPal by clicking on "Next")
                                </label>
                                <div class="form-inline">
                                    <label class="col-sm-2 ">PayPal email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" ng-model="form.paypal_email"  ng-disabled="form.payment_type!='paypal'"  ng-required="form.payment_type=='paypal'">
                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>


                            <div class="radio">
                                <label>
                                    <input type="radio"  value="giropay" ng-model="form.payment_type">
                                    GiroPay <br>
                                    (you will be forwarded to you Bank)
                                </label>

                                <div class="form-inline">
                                    <label class="col-sm-2">GiroPay account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" ng-model="form.giro_account"  ng-disabled="form.payment_type!='giropay'" ng-required="form.payment_type=='giropay'" >

                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="prepayment" ng-model="form.payment_type">
                                    Prepyment
                                </label>


                            </div>
                        </div>
                    </div>

                    <h4>Finish</h4>
                    <div class="form-group" ng-class="{ 'has-error': form.captcha==undefined && submit==true }">
                        <label class="col-sm-2 control-label">Captcha validate</label>
                        <div class="col-sm-10">
                            <div
                                    on-create="setWidgetId(widgetId)"
                                    on-expire="cbExpiration()"

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
                                type="button" ng-click="sendRegisterBusinessForm(form)" class="btn btn-primary btn-lg">Create Account</button>
                    </div>

                </form>


            </div>

            <div class="col-md-3">
                The business account provide you the following advantages:
                <ul>
                    <li>Creation of advertisements</li>
                    <li>Be able to subscripe a service</li>
                    <li>Save used car advertisements to your personal watchlist</li>
                </ul>


                <br>
                The following payment methods are available:
                <ul>
                    <li>Lastschrift</li>
                    <li>PayPal</li>
                    <li>Credit Card</li>
                </ul>
            </div>
        </div>
        </div>

@endsection