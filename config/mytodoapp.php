<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Send Champ Token
    |--------------------------------------------------------------------------
    |
    | Retrieve the Send Champ Public Token
    |
    */
    'sendchamp_token' => env('SENDCHAMP_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Ebulk SMS API Key
    |--------------------------------------------------------------------------
    |
    | Retrieve the EbulkSMS API Key
    |
    */
    'ebulksms_api_key' => env('EBULK_SMS_API_KEY', null),
    'ebulksms_username' => env('EBULK_USERNAME', null),

];
