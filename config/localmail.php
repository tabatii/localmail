<?php

return [

    'enabled' => env('LOCALMAIL_ENABLED', true),

    'home_url' => '/',

    'routes' => [
        'prefix' => 'localmail',
        'middleware' => ['web'],
    ],

];
