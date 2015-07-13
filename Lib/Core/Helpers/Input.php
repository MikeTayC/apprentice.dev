<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 1:59 PM
 */
class Core_Helpers_Input
{
    public static function exists($type = 'post')
    {
        switch($type){
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;

        }
    }

    public static function get($item)
    {
        if(isset($_POST[$item])){
            return $_POST[$item];
        } elseif (isset($_GET[$item])){
            return $_GET[$item];
        }
        return '';
    }
}