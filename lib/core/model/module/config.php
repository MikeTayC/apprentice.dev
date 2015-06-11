<?php
/*
 * retrieves and merges json configuration files
 */
class Core_Model_Module_Config
{
  //  public $config = 'etc/modules/config.json';
    public $filePath = 'lib/etc/modules/config.json';
    public $jsonArray;
//    private static $instance = null;

//    public static function getInstance()
//    {
//        if(self::$instance === null) {
//            self::$instance = new Core_Module_Module_Config();
//            return self::$instance;
//        }
//    }

//    private function __construct(){}
//
//    public static function getModuleConfig()
//    {
//        return self::$jsonArray;
//    }

    public function getConfig()
    {
        $this->jsonArray = json_decode(file_get_contents($this->filePath), true);
        return $this->jsonArray;
    }
}