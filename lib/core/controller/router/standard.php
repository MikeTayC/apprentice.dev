<?php
/*
 * Router will use the request object's pathArray array to set the route specified by the URI.
 * it will verify that the corresponding module, controller, method and params exists, before being
 * dispatched
 *
 * 3 main responsibilities:
 * 1) provide a match method which examines the request object and returns true if the router wishes to claim
 * a request and stop other router objects from acting
 * 2) mark the request object as dispatched, or throu inaction fail to mark it as dispatchd
 * 3) set the body/contents of the request object,
 */
class Core_Controller_Router_Standard extends Core_Controller_Router_Abstract
{
    /*
     * Default parameters in the case of empty uri
     */
    protected $defaultModule     = 'admin';
    protected $defaultController = 'Admin_Controller_Index';
    protected $defaultAction     = 'indexAction';

    /*
     * function match will match the URI to the corresponding pathfile,
     * will dispatch to the correct controller handler if it exists
     *
     * if path is empty: sends to default module/controller/action
     * if any module || controller || action doesn't exist, will be rerouted (eventually to default router)
     *
     */
    public function match($request)
    {
        $path = $request->requestUri();

        $path = explode('/', $path, 4);
        $module         = $path[0];
        $controller     = $path[1];
        $method         = $path[2];
        $paramsArray    = $path[3];

        if(empty($module)) {
            $this->module     = $this->defaultModule;
            $this->controller = $this->defaultController;
            $this->action     = $this->defaultAction;
            return $this->dispatch($request);
        }

        if(!$this->setModule($module)) {
            return $this->reroute();
        }

        if (!$this->setController($controller)) {
            return $this->reroute();
        }

        if (!$this->setAction($method)) {
            return $this->reroute();
        }

        if (isset($paramsArray)) {
            $this->setParams(explode('/', $paramsArray));
        }

        return $this->dispatch($request);
    }


    /*
     * todo change module check method
     * checks if the module exists, by checking if the module has an index controller.
     * sets module and returns true if it does, so match function can continue
     * returns false if does not exist, so match we will reroute to next router
     */
    public function setModule($module)
    {
        $className = ucfirst($module) . '_Controller_Index';
        if(class_exists($className)) {
            $this->module = $module;
            return true;
        }
        else {
            return false;
        }
    }

    /*

     * This function will check if a controller exists by checking if the class exists
     *
     * catch function can continue
     * returns false if does not exist, so match we will reroute to next router
     */
    public function setController( $controller)
    {
        $className = $this->module . '_' . 'Controller' . '_' . $controller;
        if (class_exists($className)) {
            $this->controller = $className;
            return true;
        }
        else {
            return false;
        }
    }

    /*
     * will check if method exists before setting action
     *
     */
    public function setAction($method)
    {
        if(method_exists($this->controller, $method)) {
            $this->action = $method;
            return true;
        }
        else {
            return false;
        }
    }

    /*
     * sets the params var with the array specified
     */
    public function setParams(array $paramsArray)
    {
        $this->params = $paramsArray;
    }
}
