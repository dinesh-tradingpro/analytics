<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorized Emails
    |--------------------------------------------------------------------------
    |
    | This configuration defines which emails have special administrative
    | privileges and can manage the authorized emails list.
    |
    */

    'admin_emails' => [
        'developer@tradingpro.com',
        'dinesh@tradingpro.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration Restriction
    |--------------------------------------------------------------------------
    |
    | When enabled, only emails in the authorized_emails table can register.
    |
    */

    'restrict_registration' => true,
];
