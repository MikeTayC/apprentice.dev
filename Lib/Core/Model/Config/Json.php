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

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Core_Module_Config_Json();
        }
        return self::$instance;
    }
    private function __construct(){}

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
        $module = strtolower(Core_Model_Request::getInstance()->getModule());

        return self::$globalJsonArray['config']['modules'][$module]['database'];
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
}