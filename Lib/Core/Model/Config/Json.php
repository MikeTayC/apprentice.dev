<?php

/**
 * Retrieves, merges and stores json configuration files
 **/
class Core_Model_Config_Json
{
    /** @var array reference to a specific modules json config data */
    private static $config = array();

    /** @var array referenct to path to json */
    private static $jsonPathArray = array();

    /** @var array refernce to global json data */
    private static $globalJsonArray = array();

    /** @var null will hold reference to object for singleton use */
    private static $instance = null;

    /** @var array reference to observers for events */
    private static $registeredObservers = array();

    /** @var  base url of site */
    private static $baseUrl;

    /** Singleton */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Core_Module_Config_Json();
        }
        return self::$instance;
    }
    private function __construct(){}

    /**
     * Sets observers on class
     * @param $observerEvents
     **/
    public static function setObservers($observerEvents)
    {
        self::$registeredObservers = $observerEvents;
    }

    /**
     * Returns a specific observer
     *
     * @param $eventName
     * @return null
     **/
    public static function getRegisteredObservers($eventName)
    {
        $observers = self::$registeredObservers;
        if(array_key_exists($eventName, $observers)) {
            $specificObserver = $observers[$eventName];
            return $specificObserver;
        }
        return null;

    }

    /** Merges all configuration json from different modules **/
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

    /**
     * finds specific paths to all json data,
     * @return array
     **/
    public static function setJsonPath()
    {
        $jsonLibAppModules = glob('*/*/Config.json');

        self::$jsonPathArray = $jsonLibAppModules;

        return self::$jsonPathArray;
    }

    /**
     * @return array of all json config
     **/
    public static function getJsonConfig()
    {
        return self::$globalJsonArray;
    }

    /**
     * @return mixed all routers
     **/
    public static function getRouterConfig()
    {
        return self::$globalJsonArray['config']['routers'];
    }

    /**
     * @return mixed information on all modules
     **/
    public static function getModulesConfig(){
        return self::$globalJsonArray['config']['modules'];
    }

    /**
     * @return mixed returns specific information about the database need to be accessed
     **/
    public static function getModulesDatabaseConfig()
    {

        return self::$globalJsonArray['config']['modules']['core']['database'];
    }

    /** gets a specific modules sesssion data
     * @param $field
     * @return mixed
     **/
    public static function getModulesSessionConfig($field)
    {
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        return self::$globalJsonArray['config']['modules'][$module]['session'][$field];
    }

    /**
     * Cookie configuration
     * @param $field
     * @return mixed
     **/
    public static function getModulesCookieConfig($field)
    {
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        return self::$globalJsonArray['config']['modules'][$module]['cookie'][$field];
    }

    /**
     * validation config
     * @return mixed
     **/
    public static function getValidationConfig(){
        $module = strtolower(Core_Model_Request::getInstance()->getModule());
        $controller = strtolower(Core_Model_Request::getInstance()->getController());

        return self::$globalJsonArray['config']['modules'][$module]['validation'][$controller];
    }

    /**
     * admin alidation configuration
     * @return mixed
     **/
    public static function getAdminValidationConfig(){

        $module = strtolower(Core_Model_Request::getInstance()->getModule());
        $param = strtolower(Core_Model_Request::getInstance()->getParams()['0']);
        return self::$globalJsonArray['config']['modules'][$module]['validation']['admin'][$param];
    }

    /**
     * returns all event information from modules
     *
     * @param $event
     * @return array
     **/
    public static function getEventConfig($event) {
        $moduleConfig = self::getModulesConfig();

        $eventArray = array();
        foreach($moduleConfig as $nodeValue) {
            if($nodeValue['events']) {
                $eventArray[] = $nodeValue['events'][$event];
            }
        }
        return $eventArray;

    }

    /**
     * @return base url of the application
     **/
    public static function getBaseUrl()
    {
        return self::$baseUrl;
    }

    /**
     * Sets the current base url which should be in configuration
     **/
    public static function setBaseUrl()
    {
        self::$baseUrl = self::$globalJsonArray['config']['modules']['core']['baseUrl']['url'];
    }

    /**
     * returns google client information from configuration
     * @return mixed
     **/
    public static function getGoogleClientInfo()
    {
        return self::$globalJsonArray['config']['modules']['core']['client'];
    }

    /**
     * Returns google calendar informtion from configuration
     * @return mixed
     **/
    public static function getCalendarConfig()
    {
        return self::$globalJsonArray['config']['modules']['core']['calendar'];
    }
}