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
            return self::$instance;
        }
        else {
            return self::$instance;
        }
    }
    private function __construct(){}

    public static function setJsonConfig()
    {
        $jsonPaths = self::setJsonPath();

        foreach ($jsonPaths as $jsonPath) {

            self::$config = json_decode(file_get_contents($jsonPath), true);

            self::$globalJsonArray = array_merge_recursive(self::$config, self::$globalJsonArray);
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

    public static function getModulesConfig(){
        return self::$globalJsonArray['config']['modules'];
    }
}