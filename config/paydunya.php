<?php

return [
    'master_key' => env('PAYDUNYA_MASTER_KEY'),
    'public_key' => env('PAYDUNYA_PUBLIC_KEY'),
    'private_key' => env('PAYDUNYA_PRIVATE_KEY'),
    'token' => env('PAYDUNYA_TOKEN'),
    'mode' => env('PAYDUNYA_MODE', 'test') // 'test' ou 'live'
];
