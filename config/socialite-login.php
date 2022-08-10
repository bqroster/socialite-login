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
         * @options: [true|false]
         */
        'login' => true
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
        'sessionKey' => 'socialite.redirect',
        'onSuccess' => null,
        'onCancelled' => null,
        'fallback' => '/'
    ]
];