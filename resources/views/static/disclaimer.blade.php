@extends('frontApp')

@section('meta-header')
    {{ trans('main.disclaimer')  }}
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading"><h3>{{ trans('main.disclaimer')  }}</h3></div>
        <div class="panel-body">
            <div class="col-md-6">
                <h4>{{ trans('main.address')  }}</h4>

                <strong>Company GmbH</strong> <br>
                Industriepark Süd 11-231 <br><br>
                D-74523 Schwäbisch Hall<br>

                E-Mail: <a href="mailto:kontakt@company.de">kontakt@company.de</a><br>
                Web: <a target="_blank" href="http://www.company.de">http://www.company.de</a>

                <br><br>
                Telefon 06403 - 90 50 40 (Ortstarif)<br>
                Geschäftsführer Elena Brunsch<br>
                Gerichtsstand Amtsgericht Schwäbisch Hall HRB 2110<br>
                USt.-IDNr. gem. §27a UStG DE 112 595 008<br>
                WEEE-Reg.-Nr. DE 48199970<br>
            </div>
            <div class="col-md-6">
                adv
            </div>
        </div>
    </div>
@endsection