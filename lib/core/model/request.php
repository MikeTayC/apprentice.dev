<?php
/*
 * Request object will parse and store URI info with in the $pathArray array
 */
class Core_Model_Request
{
    protected $dispatched = false;

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Core_Model_Request();
            return self::$instance;
        }
        else {
            return self::$instance;
        }
    }
    private function __construct(){}

    public function requestUri()
    {
        $pathUri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),'/');

        return $pathUri;
    }

    /*
     * returns state of $dispatched. used to determine if the url has been
     * dispatched by the proper router
     */
    public function isDispatched()
    {
        return $this->dispatched;
    }

    /*
     * changes the stat of dispatched to true, this will stop dispatch/router loop
     */
    public function stopDispatching()
    {
        $this->dispatched = true;

        return $this;
    }
}