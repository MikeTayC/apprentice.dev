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
            goto dispatch;
        }

        if(!$this->setModule($module)) {
            goto reroute;
        }
        if (!$this->setController($controller)) {
            goto reroute;
        }

        if (!$this->setAction($method)) {
            goto reroute;
        }

        if (isset($paramsArray)) {
            $this->setParams(explode('/', $paramsArray));
            goto dispatch;
        }

        dispatch: {
            $request->stopDispatching();
            $this->run();
            return true;
        }

        reroute:
        return false;
    }


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
     * Function accepts three params: module/controller
     * This function will set the given controller class
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
}
