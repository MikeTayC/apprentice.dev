<?php

/*
 * retrieves and merges json configuration files for routers
 */
class Core_Model_Config_Json
{
    private static $config = array();

    private static $jsonPathArray = array();

    private static $globalJsonArray = array();

    private static $instance = null;

    private static $registeredObservers = array();

    private static $baseUrl;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Core_Module_Config_Json();
        }
        return self::$instance;
    }
    private function __construct(){}

    public static function setObservers($observerEvents)
    {
        self::$registeredObservers = $observerEvents;
    }

    public static function getRegisteredObservers($eventName)
    {
        $observers = self::$registeredObservers;
        if(array_key_exists($eventName, $observers)) {
            $specificObserver = $observers[$eventName];
            return $specificObserver;
        }
        return null;

    }

    public static function setJsonConfig()
    {
        $jsonPaths = self::setJsonPath();

        foreach ($jsonPaths as $jsonPath) {

            self::$config = json_decode(file_get_contents($jsonPath), true);

            if(is_array(self::$config)) {
                self::$globalJsonArray = array_merge_recursive(self::$config, self::$globalJsonArray);
            }
        }
    }

    public static function setJsonPath()
    {
        $jsonLibAppModules = glob('*/*/Config.json');

        self::$jsonPathArray = $jsonLibAppModules;

        return self::$jsonPathArray;
    }

    public static function getJsonConfig()
    {
        return self::$globalJsonArray;
    }

    public static function getRouterConfig()
    {
        return self::$globalJsonArray['config']['routers'];
    }

    public static function getModulesConfig(){
        return self::$globalJsonArray['config']['modules'];
    }

    public static function getModulesDatabaseConfig()
    {

        return self::$globalJsonArray['config']['modules']['core']['database'];
    }

    public static function getModulesSessionConfig($field)
    {
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        return self::$globalJsonArray['config']['modules'][$module]['session'][$field];
    }

    public static function getModulesCookieConfig($field)
    {
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        return self::$globalJsonArray['config']['modules'][$module]['cookie'][$field];
    }

    public static function getValidationConfig(){
        $module = strtolower(Core_Model_Request::getInstance()->getModule());
        $controller = strtolower(Core_Model_Request::getInstance()->getController());

        return self::$globalJsonArray['config']['modules'][$module]['validation'][$controller];
    }
    public static function getAdminValidationConfig(){

        $module = strtolower(Core_Model_Request::getInstance()->getModule());
        $param = strtolower(Core_Model_Request::getInstance()->getParams()['0']);
        return self::$globalJsonArray['config']['modules'][$module]['validation']['admin'][$param];
    }

    public static function getEventConfig($event) {
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        $moduleConfig = self::getModulesConfig();

        $eventArray = array();
        foreach($moduleConfig as $nodeValue) {
            if($nodeValue['events']) {
                $eventArray[] = $nodeValue['events'][$event];
            }
        }
        return $eventArray;

    }

    public static function getBaseUrl()
    {
        return self::$baseUrl;
    }

    public static function setBaseUrl()
    {
        self::$baseUrl = self::$globalJsonArray['config']['modules']['core']['baseUrl']['url'];
    }
}