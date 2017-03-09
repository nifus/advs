@extends('frontApp')

@section('meta-header')
    {{ trans('main.register_business_header') }}
@endsection

@section('content')
    <script
            src="https://www.google.com/recaptcha/api.js?onload=vcRecaptchaApiLoaded&render=explicit"
            async defer
    ></script>
    <script src="/apps/frontApp/register/registerController.js"></script>

    <div class="panel panel-info" ng-controller="registerController">
        <div class="panel-heading"><h3>{{ trans('main.business_header') }}</h3></div>
        <div class="panel-body">


            <div class="col-md-9"  ng-show="step=='last'">
                <h4>{{trans('main.register_complete')}}</h4>
                {!! trans('main.register_business_complete_desc') !!}
            </div>

            <div class="col-md-9" ng-show="step=='first'">
                <div class="alert alert-info" role="alert">{!! trans('main.register_business_account_message',['link'=>route('register.private')]) !!}</div>

                <div class="alert alert-danger" role="alert" ng-show="error" ng-bind="error"></div>

                <form class="form-horizontal" name="register" >
                    <h4>{{ trans('main.register_company_data') }}</h4>

                    <div class="form-group" ng-class="{ 'has-error': register.company.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.company" name="company"   required minlength="2">
                            <p class="help-block">{{ trans('main.register_company_vat') }}</p>

                        </div>
                    </div>

                    <div class="form-group form-inline" ng-class="{ 'has-error': (register.country.$invalid || register.commercial_id.$invalid)  && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_register_id') }}</label>
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
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_website') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.website" name="website"  >
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': ( (register.autocomplete.$invalid )  && submit==true )  }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_address') }}</label>
                        <div class="col-sm-10">

                            <input type="text" class="form-control"  name="autocomplete" placeholder="{{ trans('main.register_company_address') }}"
                                   ng-autocomplete ng-model="autocomplete.value"  details="autocomplete.details"
                                   required >

                            <p class="help-block">{{ trans('main.register_company_address_related_vat') }}</p>
                        </div>
                    </div>

                    <div class="form-group"

                         ng-class="{ 'has-error': (register.zip.$invalid || register.city.$invalid || register.no.$invalid || register.street.$invalid) && submit==true }">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10 form-inline">
                            <input type="text" class="form-control" ng-model="form.address_street" name="street" placeholder="{{ trans('main.register_company_street') }}" required>
                            <input type="text" class="form-control" ng-model="form.address_number" name="no" placeholder="{{ trans('main.register_company_no') }}" required>
                            <input type="text" class="form-control" ng-model="form.address_zip" name="zip" placeholder="{{ trans('main.register_company_zip') }}" required>
                            <input type="text" class="form-control" ng-model="form.address_city" name="city" placeholder="{{ trans('main.register_company_city') }}" required>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_add_addr') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.address_additional" name="additional" >
                        </div>
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': register.country.$invalid  && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_country') }}</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="country" ng-model="form.commercial_country" required>
                                <option value="germany">Germany</option>
                                <option value="austria">Austria</option>
                                <option value="switzerland">Switzerland</option>
                            </select>
                        </div>
                    </div>

                    <h4>{{ trans('main.register_company_login') }}</h4>
                    <div class="alert alert-info" role="alert">{{ trans('main.register_company_email_details') }}</div>



                    <div class="form-group" ng-class="{ 'has-error': register.email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_email') }}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.email" name="email"  required >

                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_email.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_email_re') }}</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" ng-model="form.re_email" name="re_email"  required ng-match="email">
                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_password') }}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.password" name="password"  required >
                        </div>
                    </div>
                    <div class="form-group" ng-class="{ 'has-error': register.re_password.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_password_re') }}</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" ng-model="form.re_password" name="re_password" required ng-match="password">
                        </div>
                    </div>


                    <h4>{{ trans('main.register_company_contact') }}</h4>
                    <div class="alert alert-info" role="alert"> {!!  trans('main.register_company_manager')  !!}
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': register.sex.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_title') }}</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex"  value="male" required> {{ trans('main.register_man') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="sex" ng-model="form.sex" value="female" required> {{ trans('main.register_woman') }}
                            </label>
                            <p class="help-block"></p>
                        </div>
                    </div>


                    <div class="form-group" ng-class="{ 'has-error': register.name.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_forename') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.name" name="name" placeholder="Alexander" required minlength="2">

                        </div>
                    </div>

                    <div class="form-group" ng-class="{ 'has-error': register.surname.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_surname') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.surname" name="surname" placeholder="Pushkin" required  minlength="2">
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">{{ trans('main.register_email') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.contact_email"  >
                            <p class="help-block">{{ trans('main.register_company_personal_email') }}</p>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_phone') }}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="form.phone" name="phone" >
                        </div>
                    </div>


                    <h4>{{ trans('main.register_company_subsc') }}</h4>

                    <div class="form-group" ng-class="{ 'has-error': register.tariff.$invalid && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_select_subsc') }}</label>
                        <div class="col-sm-10">
                            <table class="table  table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ trans('main.register_company_package') }}</th>
                                    <th>{{ trans('main.register_company_slots_per_month') }}</th>
                                    <th>{{ trans('main.register_company_price_per_month') }}</th>
                                    <th>{{ trans('main.register_company_each_extra_slots') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tariffs as $tariff)
                                    <tr ng-click="form.tariff={{$tariff['id']}}" style="cursor: pointer">
                                        <td><input type="radio" value="{{$tariff['id']}}" name="tariff" ng-model="form.tariff" required></td>
                                        <td>{{$tariff['title']}}</td>
                                        <td>{{$tariff['number_of_slots']}}</td>
                                        <td>{{$tariff['price']}}</td>
                                        <td>{{$tariff['price_extra_slots']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>


                            </table>
                        </div>
                    </div>


                    <h4>{{ trans('main.register_company_payment') }}</h4>
                    <div class="form-group" ng-class="{ 'has-error': (form.payment_type==undefined || (form.payment_type=='paypal' && form.paypal_email==undefined )|| (form.payment_type=='giropay' && form.giro_account==undefined )) && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_company_select_payment') }}</label>
                        <div class="col-sm-10">

                            <div class="radio">
                                <label>
                                    <input type="radio" value="paypal" ng-model="form.payment_type">
                                    {{ trans('main.register_company_paypal') }} <br>
                                    {{ trans('main.register_company_paypal_desc') }}
                                </label>
                                <div class="form-inline">
                                    <label class="col-sm-2 ">{{ trans('main.register_company_paypal_email') }} </label>
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
                                    {{ trans('main.register_company_giropay') }} <br>
                                    {{ trans('main.register_company_giropay_desc') }}
                                </label>

                                <div class="form-inline">
                                    <label class="col-sm-2">{{ trans('main.register_company_giropay_acc') }}</label>
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
                                    {{ trans('main.register_company_prepayment') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <h4>{{ trans('main.register_company_finish') }}</h4>
                    <div class="form-group" ng-class="{ 'has-error': form.captcha==undefined && submit==true }">
                        <label class="col-sm-2 control-label">{{ trans('main.register_captcha') }}</label>
                        <div class="col-sm-10">
                            <div
                                    on-create="setWidgetId(widgetId)"
                                    on-expire="cbExpiration()"

                                    ng-model="form.captcha"
                                    vc-recaptcha
                                    key="'{{config('services.google.captcha.sitekey')}}'"
                            ></div>

                        </div>
                    </div>

                    <div class="form-group"  ng-class="{ 'has-error': register.agb.$invalid && submit==true }">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">

                            <textarea   class="form-control" style="height: 200px" disabled>{{ config('app.agb') }}</textarea> <br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="agb" ng-model="form.agb"  required> {{ trans('main.register_agb_accept') }}
                            </label>

                        </div>
                    </div>

                    <div class="alert alert-warning" role="alert">...</div>


                    <div style="text-align: center">
                        <button
                                ng-disabled="send==true"
                                type="button" ng-click="sendRegisterBusinessForm(form)" class="btn btn-primary btn-lg">{{ trans('main.register_create_account_button') }}</button>
                    </div>

                </form>


            </div>

            <div class="col-md-3">
                {!! trans('main.register_company_desc') !!}
            </div>
        </div>
        </div>

@endsection