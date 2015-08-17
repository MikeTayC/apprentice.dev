<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 10:53 AM
 */
class Core_Model_Session extends Core_Model_Object
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
        if(self::exists($name)) {
            return $_SESSION[$name];
        }
        return null;
    }

    public static function delete($name)
    {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function deleteAll()
    {
        unset($_SESSION);
    }
    public static function dangerFlash($name, $string = '')
    {
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }
        elseif(!empty($string)) {
            self::set($name, '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>' . $string . '</p></div>');
        }
    }
    public static function successFlash($name, $string = '')
    {
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        }
        elseif(!empty($string)) {
            self::set($name, '<div class="uk-alert uk-alert-success" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>' . $string . '</p></div>');
        }
    }
}