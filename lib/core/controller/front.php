<?php
/**
 * Front controller provides a centralized request handling mechanism so
 * that all request will be handled by a single handler.
 *
 * Front controller will determine which router object will be used for a particular URL
 * these routing objects have a different set of rules for how that url should be routed to
 * a particular controller
 *
 */
class Core_Controller_Front
{
    /*
     * array will keep track of instantiated routers
     */
    private $routers = array();

    /*
     * $request holds the request object
     */
    private $request;

    /*
     * will hold router data from json files
     */
    private $routerConfig;

    /*
     * instantiates new request object
     * initializes routers
     */
    public function __construct()
    {
        $this->request = new Core_Model_Request();

        $this->routersInit();
    }

    /*
     * routing happens here,
     * dispatch() will loop through the routers array, one at a time, the front controller takes the objects
     * and calls their match method(passing in the request object) HOWEVER, just because there is a
     * router match does not mean the request has been dispatched.
     */
    public function dispatch()
    {
        $i = 0;
        while(!$this->request->isDispatched() && $i++<100) {
            foreach ($this->routers as $router) {
                if($router->match($this->request))
                    break;
            }
        }

        /*
         * if request isnt found by 100 attempt, something is wrong
         */
        if ($i>100) {
            throw new Exception('Request was not found after 100 attempts, something is wrong');
        }
    }

    /*
     * need to instantiate the available routers and add them to accessible array
     */
    private function routersInit()
    {
        /*
         * function calls something that will grab json, it will decode/merge the json into an array
         * this function will iterate over the array, add add to the router
         */
        $config = new Core_Model_Router_Config();
        $this->routerConfig = $config->getRouterConfig();

        foreach ($this->routerConfig['config']['routers'] as $router) {
            $this->addRouter(new $router);
        }

        $default = new Core_Controller_Router_Default();
        $this->addRouter($default);
    }

    /*
     * adds an instantiated router to the routers array, for use in dispatch
     */
    private function addRouter($router)
    {
        $this->routers[] = $router;
    }
}

