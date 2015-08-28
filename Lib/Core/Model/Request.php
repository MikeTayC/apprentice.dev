<?php
/**
 * Request object will parse and store URI info with in the $pathArray array
 *
 * Request is integral to dispatching urls and keeping track of these requests
 **/
class Core_Model_Request extends Core_Model_Object
{
    protected $dispatched = false;

    private static $instance = null;

    public static $pathUri;

    /**
     * Singelton
     **/
    private function __construct(){}
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


    /**
     * Retyrbs the path uri
     * @return string
     **/
    public function requestUri()
    {
        self::$pathUri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),'/');

        return self::$pathUri;
    }

    /**
     * Returns state of $dispatched. used to determine if the url has been
     * dispatched by the proper Router
     **/
    public function isDispatched()
    {
        return $this->dispatched;
    }

    /**
     * Changes the stat of dispatched to true, this will stop dispatch/Router loop
     **/
    public function stopDispatching()
    {
        $this->dispatched = true;

        return $this;
    }

    /**
     * Forces the dispatch/router loop to continue, useful for redirects
     * @return $this
     **/
    public function continueDispatching(){
        $this->dispatched = false;
        return $this;
    }

    /**
     * Returns post variable if it is set
     *
     * @param $key
     * @return bool
     **/
    public function getPost($key){
        return (array_key_exists($key, $_POST) ? $_POST[$key] : false);
    }

    /**
     * Check for a specific post variable
     * @param $key
     * @return bool
     **/
    public function hasPost($key){
        return (bool) self::getPost($key);
    }

    /**
     * Verifies if any post has been set
     * @return bool
     **/
    public function isPost(){
        return !empty($_POST);
    }

}