<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Varnish Server Configuration
    |--------------------------------------------------------------------------
    |
    |   Connection information for the Varnish server you want to connect to.
    |
    */

    'server' => array(
        "address" => "varnish-test.local:80",
    ),

    /*
    |--------------------------------------------------------------------------
    | Force Bad Response Exceptions
    |--------------------------------------------------------------------------
    |
    |   By default Acetone will allow exceptions to bubble through if debug mode is on (for local development).
    |   However in production, they will be caught and handled. This will stop things like a purge request returning a
    |   404 from halting the execution of your script.
    |
    |   'auto' => will show or hide depending on environment
    |   false => will always hide exceptions
    |   true => will always show exceptions
    |
    |   Supported: "auto", true, false
    */

    'force_exceptions' => "auto",
);