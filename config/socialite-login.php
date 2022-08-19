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
         */
        'create' => [
            /**
             * @options: [true|false|className]
             *
             * @option className
             * will ignore all the process of auto create
             * this class, must have one public method `handle`
             * the app will pass 2 parameters, and you must return the `User`
             * or Model created
             *
             * @param \Laravel\Socialite\Contracts\User $socialUser
             * @param string $socialLogin
             * @return \App\Models\User
             */
            'auto' => true,

            /**
             * @oncreate or onupdate process
             * if you want it saveQuietly
             */
            'saveQuietly' => true,
        ],

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
             *
             * @string (className)
             * will call your class with a method name `handle`
             * pass one parameter, the `model` passed on the created
             * before auto or by class
             * @param \App\Models\User
             * @return [redirect|null]
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
        'fallback' => env('APP_URL'),
    ]
];