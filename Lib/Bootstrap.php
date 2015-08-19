<?php
require_once 'Const.php';
require_once 'Autoloader.php';

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

    public static function getView($viewIdentifier = false){
        $viewClassName = self::buildClassName($viewIdentifier, 'View');
        return new $viewClassName();
    }

    public static function getModel($viewIdentifier = false){
        $viewClassName = self::buildClassName($viewIdentifier, 'Model');
        return new $viewClassName();
    }

    public static function getModuleName($moduleName = false){
        if($moduleName){
            $modulesConfig = Core_Model_Config_Json::getModulesConfig();
            if(array_key_exists($moduleName, $modulesConfig)){
                return $modulesConfig[$moduleName]['dir'];
            }
        }
    }

    public static function buildClassName($identifier = false, $type = false){
        $className = false;
        if($identifier && $type && strpos('/', $identifier) >= 0){
            //Check if module exists
            list($module, $viewFile) = explode('/', $identifier);
            $viewFile = str_replace('_', ' ', $viewFile);
            $viewFile = str_replace(' ', '_', ucwords($viewFile));
            $className = self::getModuleName($module) . '_' . $type . '_' . $viewFile;
        }
        return $className;
    }

    public static function getBaseUrl()
    {
        return "http://apprentice.dev/incubate/";
    }
}
