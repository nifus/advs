<?php

return [


    'tariffs'=>[
        ['id'=>1, 'name'=>'Pack 1', 'slots'=>1, 'price'=>30, 'additional'=>30],
        ['id'=>2, 'name'=>'Pack 2', 'slots'=>2, 'price'=>55, 'additional'=>27.5],
        ['id'=>3, 'name'=>'Pack 5', 'slots'=>5, 'price'=>125, 'additional'=>25],
        ['id'=>4, 'name'=>'Pack 10', 'slots'=>10, 'price'=>225, 'additional'=>22.5],
        ['id'=>5, 'name'=>'Pack 20', 'slots'=>20, 'price'=>400, 'additional'=>20],
        ['id'=>6, 'name'=>'Pack 30', 'slots'=>30, 'price'=>525, 'additional'=>17.5],
        ['id'=>7, 'name'=>'Pack 40', 'slots'=>40, 'price'=>600, 'additional'=>15],
    ],

    'agb'=>'Stand: September 2015
                1. Allgemeines / Vertragsabschluss
                1.1 Diese Allgemeinen Geschäftsbedingungen (nachfolgend „AGB“) der/des ALTERNATE GmbH (nachfolgend „Verkäufer“) gelten für alle Verträge, die ein Verbraucher oder Unternehmer (nachfolgend „Kunde“)
                mit dem Verkäufer hinsichtlich der vom Verkäufer in dem vorliegenden Online-Shop dargestellten Waren und/oder Leistungen abschließt. Hiermit wird der Einbeziehung von eigenen Bedingungen des Kunden
                widersprochen, es sei denn, es ist etwas anderes vereinbart worden.
                1.2 Für den Erwerb von Gutscheinen gelten diese AGB entsprechend, sofern insoweit nicht ausdrücklich etwas Abweichendes geregelt ist.
                1.3 Verbraucher im Sinne dieser AGB ist jede natürliche Person, die ein Rechtsgeschäft zu Zwecken abschließt, die überwiegend weder ihrer gewerblichen noch ihrer selbständigen beruflichen Tätigkeit
                zugerechnet werden können. Unternehmer im Sinne dieser AGB ist jede natürliche oder juristische Person oder eine rechtsfä- hige Personengesellschaft, die bei Abschluss eines Rechtsgeschäfts in Ausübung
                ihrer selbstständigen beruflichen oder gewerblichen Tätigkeit handelt
                2. Preise und Zahlungsbedingungen
                2.1 Für die Lieferung gelten die Listenpreise zum Zeitpunkt der Bestellung. Sofern sich aus dem Angebot des Verkäufers nichts anderes ergibt, handelt es sich bei den angegebenen Preisen um Endpreise, die die
                gesetzliche Umsatzsteuer oder Versicherungssteuer enthalten und verstehen sich zzgl. Versandkosten, gegebenenfalls Nachnahmegebühren, Transaktionskosten, Installations- und Schulungskosten etc. Je nach
                Versandart errechnen sich die Versandkosten in Abhängigkeit von Größe, Gewicht und Anzahl der Pakete.
                2.2 Soweit Lieferungen in Länder außerhalb der Europäischen Union in diesem Online- Shop möglich sind, können im Einzelfall weitere Kosten anfallen, die der Verkäufer nicht zu vertreten hat und die vom
                Kunden zu tragen sind. Hierzu zählen beispielsweise Kosten für die Geldübermittlung durch Kreditinstitute (z.B. Überweisungsgebühren, Wechselkursgebühren) oder einfuhrrechtliche Abgaben bzw. Steuern (z.B.
                Zölle).
                2.3 Dem Kunden stehen verschiedene Zahlungsmöglichkeiten zur Verfügung, auf die er in diesem Online- Shop hingewiesen wird.
                3. Lieferung, Versand, Gefahrübergang
                3.1 Die Lieferung von Waren erfolgt auf dem Versandweg an die vom Kunden angegebene Lieferanschrift, sofern nichts anderes vereinbart ist. Bei der Abwicklung der Transaktion ist die in der Bestellabwicklung
                des Verkäufers angegebene Lieferanschrift maßgeblich. Ausgenommen hiervon ist die ausgewählte Zahlungsart PayPal. Hier ist die vom Kunden zum Zeitpunkt der Bezahlung bei PayPal hinterlegte Lieferanschrift
                maßgeblich.
                3.2 Sendet das Transportunternehmen die versandte Ware an den Verkäufer zurück, da eine Zustellung beim Kunden nicht möglich war, trägt der Kunde die Kosten für den erfolglosen Versand. Dies gilt nicht,
                wenn der Kunde den Umstand, der zur Unmöglichkeit der Zustellung geführt hat, nicht zu vertreten hat oder wenn er vorübergehend an der Annahme der angebotenen Leistung verhindert war, es sei denn, dass
                der Verkäufer dem Kunden die Leistung eine angemessene Zeit vorher angekündigt hatte.
                3.3 Grundsätzlich geht die Gefahr des zufälligen Untergangs und der zufälligen Verschlechterung der verkauften Ware mit der Übergabe an den Kunden oder eine empfangsberechtigte Person über. Handelt der
                Kunde als Unternehmer, geht die Gefahr des zufälligen Untergangs und der zufälligen Verschlechterung beim Versendungskauf mit der Auslieferung der Ware an eine geeignete Transportperson am Geschäftssitz
                    des Verkäufers über',
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => 'Adv service',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        //

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
        Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,
        //Barryvdh\Cors\ServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'JWTAuth'   => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
        'Image' => Intervention\Image\Facades\Image::class

    ],

];
