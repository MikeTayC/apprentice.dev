<?php
require_once 'const.php';
require_once 'autoloader.php';

class Bootstrap
{
    /**
     * $paths is an array that will keep track of different file paths
     * @var array
     */
    private $paths = array();

    /*
     * $filePath var will be used to initialize the $paths array to become the new include_path configuration
     */
    private $filePath;

    public function __construct()
    {
        $this->setIncludePath();

        spl_autoload_register('Autoloader::autoload');

        $this->initJsonConfig();

        $this->loadRunFrontController();
    }
    /*
     * function will set the include path to be used by autoloader
     */
    private function setIncludePath()
    {
        $paths[] = BP . DS . APP;

        $paths[] = BP . DS . LIB;

        $filePath = implode(PS , $paths);

        /*
         * Sets the include_path configuration
         */
        set_include_path($filePath . PS);
    }

    private function loadRunFrontController()
    {
        $frontcontroller = new Core_Controller_Front();
        $frontcontroller->dispatch();
    }

    private function initJsonConfig()
    {
        Core_Model_Config_Json::setJsonConfig();
    }
}
