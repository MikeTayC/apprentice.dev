<?php
/*
 * autoloader class
 * static function autoload to be used in bootstrap.php to initialize the autoloader
 */

class Autoloader
{
    static function autoload($className)
    {
        /*
         * Configures the class name to the appropriate file path
         */
        $fileName = str_replace('_', DS , strtolower($className)) . '.php';

        /*
         * stream_resolve_include_path()
         * resolves filename against the include path
         * Check if file actually exists before loading
         */
        if (stream_resolve_include_path($fileName)) {
            require_once($fileName);
        }
        else {
            return false;
        }
    }
}