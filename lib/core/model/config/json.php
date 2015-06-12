<?php

/*
 * retrieves and merges json configuration files for routers
 */
class Core_Model_Config_Json
{
    public static $config = array();

    public static $jsonPathArray = array();

    public static $globalJsonArray = array();

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Core_Module_Config_Json();
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
        $jsonLibAppModules = glob('*/*/config.json');

        self::$jsonPathArray = $jsonLibAppModules;

        $jsonModuleNames = 'lib/etc/modules/config.json';

        self::$jsonPathArray[] = $jsonModuleNames;

        return self::$jsonPathArray;
    }

    public static function getJsonConfig()
    {
        return self::$globalJsonArray;
    }
}