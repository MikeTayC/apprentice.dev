<?php
/*
 * autoloader class
 * static function autoload to be used in bootstrap.php to initialize the autoloader
 * TODO review differnces between require/include/include_once/require_once
 * TODO need to set an include path that includes app and lib
 */
class Autoloader
{
    static function autoload($className)
    {
        $directory = array('lib/', 'app/');
        foreach ($directory as $current_dir) {
            $fileName = $current_dir . str_replace('_', DIRECTORY_SEPARATOR, strtolower($className)) . '.php';

            if (is_readable($fileName)) {
                require $fileName;
            }
        }
    }
}