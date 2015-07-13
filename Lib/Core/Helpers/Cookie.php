<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/10/15
 * Time: 12:16 PM
 */
class Core_Helpers_Cookie
{
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    public static function set($name, $value, $expiry)
    {
        /*
         * to set a cookie we need a name and value
         * need the expiry time which is appended to the current time
         * and need the path, which is now set to current domain?
         */
        if(setcookie($name, $value, time() + $expiry, '/')){
            return true;
        }
        return false;
    }

    public static function delete($name)
    {
        setcookie($name, '', time()-(Core_Model_Config_Json::getModulesCookieConfig('cookie_expiry')), '/');
    }
}