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

        return Core_Model_Config_Json::getBaseUrl();
    }

    public static function setBaseUrl()
    {
        Core_Model_Config_Json::setBaseUrl();
    }

    public static function registerObservers()
    {
        if($moduleConfig = Core_Model_Config_Json::getModulesConfig()) {
            $mergedEvents = array();
            foreach($moduleConfig as $module) {
                if(array_key_exists('events', $module)) {
                    $observerEvents = $module['events'];
                    $mergedEvents = array_merge_recursive($mergedEvents, $observerEvents);
                }
            }
            Core_Model_Config_Json::setObservers($mergedEvents);
        }

    }
	public static function dispatchEvent($eventName, $params)
	{
		/*
		 * 1. get all obersever nodes that listen to @var $eventName
		 * 2. foreach $observers as $obserever, (expect associative array)
		 * 		$observerModel = Bootstrap::getModel($observer['*class*'])
		 * 		//check method
		 * 		call_user_func_array(array($observerModel, $obserberMethodFrom Congif), $params
		 *
		 *
		 */
        //get all obersver nodes that listen to the event
        //foreach observer (should be associative array, check for method, then call the appropriate method)
         if($observers = Core_Model_Config_Json::getRegisteredObservers($eventName)) {
            foreach($observers as $observer) {
                if(!$observer['class'] || !$observer['method']) {
                    continue;
                }
                $observerModel = Bootstrap::getModel($observer['class']);
                $observerMethod = $observer['method'];
                if(method_exists($observerModel, $observerMethod)) {
                    call_user_func_array(array($observerModel, $observerMethod), array($params));
                }

            }
        }
	}

    public static function registerGoogleFiles()
    {
        require_once 'google-api-php-client/src/Google/autoload.php';
        require_once 'google-api-php-client/src/Google/Client.php';
        require_once 'google-api-php-client/src/Google/Service/Calendar.php';
    }

    public static function getGoogleClientInfo()
    {
        return Core_Model_Config_Json::getGoogleClientInfo();
    }

    public static function getCalendarConfig()
    {
        return Core_Model_Config_Json::getCalendarConfig();
    }
}
