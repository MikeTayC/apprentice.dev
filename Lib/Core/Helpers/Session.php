<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 10:53 AM
 */
class Core_Helpers_Session
{
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true: false;
    }

    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function delete($name)
    {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    public static function flash($name, $string = '')
    {
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }
        else {
            self::set($name, $string);
        }
    }
}