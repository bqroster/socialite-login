<?php

return [
    'relationship' => [
        /**
         * which is the Eloquent Model
         */
        'model' => 'App\Models\User',
        'migration' => [
            /**
             * socialite-login will add
             * 3 fields, we need to know
             * after which field
             * in the `users` table
             * will be included
             */
            'after' => 'email_verified_at'
        ],
    ],

    'actions' => [
        /**
         * auto create user
         * once social credentials
         * have being approved
         * @options: [true|false]
         */
        'create' => true,

        /**
         * auto logged user
         * into the system
         */
        'login' => [
            /**
             * @true
             * user is auto-logged
             * in the system,
             * after social network authorized
             * and email is present
             */
            'auto' => true,
            /**
             * @true,
             * user is not able to login
             * using a different network
             * from the one registered
             */
            'strict' => false,
        ],
    ],

    'table' => [
        'db' => 'user_socialite',
    ],

    'networks' => [
        'facebook',
        'twitter',
        'google',
    ],

    'redirect' => [
        'session_key' => 'socialite.redirect',
        'on_success' => null,
        'on_cancelled' => null,
        'fallback' => '/'
    ]
];