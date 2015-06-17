<?php
class Core_Model_Design_Json
{
    private static $_design = array();

    private static $_jsonPathArray = array();

    private static $_jsonDesignArray = array();

    private static $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Core_Module_Design_Json();
        }
        return self::$_instance;
    }
    private function __construct(){}

    public static function setJsonDesign()
    {
        $jsonPaths = self::setJsonPath();

        foreach ($jsonPaths as $jsonPath) {

            self::$_design = json_decode(file_get_contents($jsonPath), true);

            self::$_jsonDesignArray = array_merge_recursive(self::$_design, self::$_jsonDesignArray);
        }
    }

    private static function setJsonPath($module)
    {
        $jsonLibAppModules = glob('*/*/Design.json');

        self::$_jsonPathArray = $jsonLibAppModules;

        return self::$_jsonPathArray;
    }

    public static function getJsonConfig()
    {
        return self::$_jsonDesignArray;
    }

    public static function getDefaultType()
    {
        return self::$_jsonDesignArray['layout']['actions']['default']['type'];
    }

    public static function getDefaultTemplate(){
        return self::$_jsonDesignArray['layout']['actions']['default']['Template'];
    }

    public static function getDefaultFooterType(){
        return self::$_jsonDesignArray['layout']['actions']['default']['footer']['type'];
    }

    public static function getDefaultFooterTemplate(){
        return self::$_jsonDesignArray['layout']['actions']['default']['footer']['Template'];
    }

    public static function getDefaultHeaderType(){
        return self::$_jsonDesignArray['layout']['actions']['default']['header']['type'];
    }

    public static function getDefaultHeaderTemplate(){
        return self::$_jsonDesignArray['layout']['actions']['default']['header']['Template'];
    }

    public static function getCurrentClassDesignJson($className)
    {
        $className = strtolower($className);
        return self::$_jsonDesignArray['layout']['actions'][$className];
    }

}