<?php


return [
    /*
    |--------------------------------------------------------------------------
    | User should send emails when queue failed
    |--------------------------------------------------------------------------
    |
    | support string and array config
    | set null will not send emails
    */

    'to' => env('MAIL_TO_ADDRESS', '603353475@qq.com'),
    /*
    |--------------------------------------------------------------------------
    | The queue use for handle send email
    |--------------------------------------------------------------------------
    |
    | If set null will not use queue
    |
   */
    'queue' => null,
];