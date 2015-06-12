<?php
require_once 'const.php';
require_once 'autoloader.php';

final class Bootstrap
{
    public static function setIncludePath()
    {
        $paths[] = BP . DS . APP;

        $paths[] = BP . DS . LIB;

        $filePath = implode(PS , $paths);

        /*
         * Sets the include_path configuration
         */
        set_include_path($filePath . PS);
    }

    public static function registerAutoload()
    {
        spl_autoload_register('Autoloader::autoload');
    }

    public static function loadRunFrontController()
    {
        $frontController = new Core_Controller_Front();
        $frontController->dispatch();
    }

    public static function initJsonConfig()
    {
        Core_Model_Config_Json::setJsonConfig();
    }
}
