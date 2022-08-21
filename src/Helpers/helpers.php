<?php

if (!function_exists('is_auto_login')) {
    /**
     * @return bool
     */
    function is_auto_login()
    {
        return (config('socialite-login.actions.login.auto') === true);
    }
}

if (!function_exists('is_login_a_class')) {
    /**
     * @return string|bool
     */
    function is_login_a_class()
    {
        $loginClass = config('socialite-login.actions.login.auto');
        if (
            is_string($loginClass)
            && class_exists($loginClass)
        ) {
            return $loginClass;
        }

        return false;
    }
}

if (!function_exists('is_login_stric')) {
    /**
     * @return bool
     */
    function is_login_strict()
    {
        return (config('socialite-login.actions.login.strict') === true);
    }
}

if (!function_exists('redirect_session_key')) {
    /**
     * @return string|null
     */
    function redirect_session_key()
    {
        return config('socialite-login.redirect.session_key');
    }
}

if (!function_exists('redirect_fallback')) {
    /**
     * @return string|null
     */
    function redirect_fallback()
    {
        return config('socialite-login.redirect.fallback');
    }
}

if (!function_exists('socialite_relationship_model')) {
    /**
     * @return string|null
     */
    function socialite_relationship_model()
    {
        return config('socialite-login.relationship.model');
    }
}

if (!function_exists('socialite_relationship')) {
    /**
     * Returns model and table
     * from relationship
     * @return array
     */
    function socialite_relationship()
    {
        $relModel = socialite_relationship_model();
        $relTable = (new $relModel)->getTable();
        return [$relModel, $relTable];
    }
}

if (!function_exists('socialite_relationship_table')) {
    /**
     * Returns model and table
     * from relationship
     * @return string
     */
    function socialite_relationship_table()
    {
        $relModel = socialite_relationship_model();
        return (new $relModel)->getTable();
    }
}

if (!function_exists('save_quietly_on_create')) {
    /**
     * @return bool
     */
    function save_quietly_on_create()
    {
        return (config('socialite-login.actions.create.saveQuietly') === true);
    }
}

if (!function_exists('can_user_auto_create')) {
    /**
     * @return bool
     */
    function can_user_auto_create()
    {
        return (config('socialite-login.actions.create.auto') === true);
    }
}

if (!function_exists('can_user_create_is_a_class')) {
    /**
     * @return string|bool
     */
    function can_user_create_is_a_class()
    {
        $createClass = config('socialite-login.actions.create.auto');
        if (
            is_string($createClass)
            && class_exists($createClass)
        ) {
            return $createClass;
        }

        return false;
    }
}

if (!function_exists('socialite_networks')) {
    /**
     * @return bool
     */
    function socialite_networks()
    {
        return config('socialite-login.networks');
    }
}