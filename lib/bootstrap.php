<?php
require_once 'config.php';
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

        $this->loadFrontController();

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

    /*
     * Loads front crontroller by:
     * Instantiating the front controller
     * Parsing the url with the parseUri function
     * Running the appropriate controller set in the uri
     */
    private function loadFrontController()
    {
        $frontController = new Core_Front_Controller();
        $frontController->parseUri();
        $frontController->run();
    }
}
