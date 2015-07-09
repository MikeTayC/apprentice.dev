<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 11:49 AM
 */
class Core_Helpers_Hash
{
    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt );
    }

    public static function salt($length)
    {
        return mcrypt_create_iv($length);
    }

    public static function unique()
    {
        return self::make(uniqid());
    }
}