<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 10:53 AM
 *
 *
 * Handles session data
 **/
class Core_Model_Session extends Core_Model_Object
{
    /**
     *
     * Checks if a current session variable is set
     * @param $name
     * @return bool
     **/
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true: false;
    }

    /**
     *
     * Sets a value in the session
     * @param $name
     * @param $value
     * @return mixed
     **/
    public static function set($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    /**
     *
     * Gets a value in the session if it exists
     * @param $name
     * @return null
     **/
    public static function get($name)
    {
        if(self::exists($name)) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     *
     * Deletes a session variable
     * @param $name
     **/
    public static function delete($name)
    {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Deletes all session variables
     **/
    public static function deleteAll()
    {
        unset($_SESSION);
    }

    /**
     *
     * used for flashing an error message
     * @param $name
     * @param string $string
     * @return null
     **/
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

    /**
     *
     * Used for flashing a success message
     *
     * @param $name
     * @param string $string
     * @return null
     **/
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