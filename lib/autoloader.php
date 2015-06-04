<?php
/*
 * autoloader class
 * static function autoload to be used in bootstrap.php to initialize the autoloader
 */
require_once('config.php');

class Autoloader
{
    /**
     * $paths is an array that will keep track of different file paths
     * @var array
     */
    private $paths = array();

    /*
     * $filePath var will be used to initialize the $paths array to become the new include_path configuration
     */
    private $filePath;

    static function autoload($className)
    {
        $paths = array(
            BP . DS . APP ,
            BP . DS . LIB
        );

        $filePath = implode(PS , $paths);

        /*
         * Sets the include_path configuration
         */
        set_include_path($filePath . PS);

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
            echo 'Error loading:' . $fileName;
        }
    }

}