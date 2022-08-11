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

if (!function_exists('socialite_table_name')) {
    /**
     * Returns model and table
     * from relationship
     * @return string
     */
    function socialite_table_name()
    {
        return config('socialite-login.table.db');
    }
}

if (!function_exists('can_user_auto_create')) {
    /**
     * @return bool
     */
    function can_user_auto_create()
    {
        return (config('socialite-login.actions.create') === true);
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