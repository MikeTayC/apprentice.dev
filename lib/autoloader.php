<?php
/*
 * autoloader class
 * static function autoload to be used in bootstrap.php to initialize the autoloader
 *  review differnces between require/include/include_once/require_once
 */

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(dirname(__FILE__)));


class Autoloader
{
    private $paths = array();

    private $filePath;

    static function autoload($className)
    {
        $paths = array(
            BP . DS . 'app' ,
            BP . DS . 'lib'
        );

        $filePath = implode(PS , $paths);

        set_include_path($filePath . PS);

        $fileName = str_replace('_', DS , strtolower($className)) . '.php';

        include $fileName;
    }

}