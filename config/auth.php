<?php

return [

    'defaults' => [
        'guard' => 'owner',
        'passwords' => 'owners',
    ],

    'guards' => [
        'owner' => [
            'driver' => 'session',
            'provider' => 'owners',
        ],

        'super_admin' => [
            'driver' => 'session',
            'provider' => 'super_admins',
        ],

        'user' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Owner::class,
        ],

        'super_admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class,
        ],

        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'owners' => [
            'provider' => 'owners',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'super_admins' => [
            'provider' => 'super_admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];