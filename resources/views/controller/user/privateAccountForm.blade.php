@extends('frontApp')

@section('meta-header')
    REGISTER
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading"><h3>REGISTER</h3></div>
        <div class="panel-body">
            <div class="alert alert-info" role="alert">If you are a real estate agent / agency please create a <a
                        href="{{route('register.business')}}">business account</a></div>


            <div class="col-md-9">
                <h4>Account type: PRIVATE</h4>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                <input type="radio" name="sex"  value="male"> Mister
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="sex"  value="female"> Miss
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Forename</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" placeholder="Alexander">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="surname" placeholder="Pushkin">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" placeholder="pushkin@gmail.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Repeat email address</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="re_email" placeholder="pushkin@gmail.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" placeholder="******">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Repeat password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="re_password" placeholder="******">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="sex"  value="male"> <a href="AGB"></a> accept?
                            </label>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Captcha validate</label>
                        <div class="col-sm-10">
                            {!! app('captcha')->display() !!}

                        </div>
                    </div>

                    <div style="text-align: center">
                        <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                    </div>

                </form>
            </div>

            <div class="col-md-3">
                ADV
            </div>
        </div>

@endsection