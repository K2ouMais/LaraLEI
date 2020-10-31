<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LOU Identifier (LOU = Local Operating Unit)
    |--------------------------------------------------------------------------
    |
    | You prabably only will have a LOU Prefix if you are an accredited
    | LEI Issuer.
    |
    | Default: 1234
    |
    */

    'lou_ident' => env('LARALEI_LOU_IDENTIFIER', '1234'),

    /*
    |--------------------------------------------------------------------------
    | Reserved characters
    |--------------------------------------------------------------------------
    |
    | This is are reserved characters reserved by GLEIF.
    |
    | Default: 00
    |
    */

    'lei_reserved' => env('LARALEI_RESERVED', '00'),

];
