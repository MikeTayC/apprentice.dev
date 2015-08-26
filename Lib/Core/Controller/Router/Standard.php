<?php
/*
 * Router will use the request object's pathArray array to set the route specified by the URI.
 * it will verify that the corresponding module, Controller, method and params exists, before being
 * dispatched
 *
 * 3 main responsibilities:
 * 1) provide a match method which examines the request object and returns true if the Router wishes to claim
 * a request and stop other Router objects from acting
 * 2) mark the request object as dispatched, or throu inaction fail to mark it as dispatchd
 * 3) set the body/contents of the request object,
 */
class Core_Controller_Router_Standard extends Core_Controller_Router_Abstract
{
    const ACTION_METHOD_IDENTIFIER = 'Action';

    /*
     * Default parameters in the case of empty uri
     */
    const DEFAULT_MODULE    = 'Incubate';
    const DEFAULT_CONTROLLER = 'Login';
    const DEFAULT_ACTION    = 'indexAction';

    /*
     * function match will match the URI to the corresponding pathfile,
     * will dispatch to the correct Controller handler if it exists
     *
     * if path is empty: sends to default module/Controller/action
     * if any module || Controller || action doesn't exist, will be rerouted (eventually to default Router)
     *
     */
    public function match($request)
    {
        $this->_request = $request;
        $path = $this->_request->requestUri();

        $path = explode('/', $path, 4);

        //check if request is set, if it is not set the following will run
        if($this->checkRequestObjectIsSet()) {

            $module      = !empty($path[0]) ? ucfirst($path[0]) : null;
            $controller  = !empty($path[1]) ? ucfirst($path[1]) : null;
            $method      = !empty($path[2]) ? strtolower($path[2]) . self::ACTION_METHOD_IDENTIFIER : null;
            $paramsArray = !empty($path[3]) ? $path[3] : null;

            if (empty($module)) {
                $this->_request->setModule(self::DEFAULT_MODULE);
                $this->_request->setController(self::DEFAULT_CONTROLLER);
                $this->_request->setAction(self::DEFAULT_ACTION);
            }
            elseif (!($this->checkModuleControllerActionExists($module, $controller, $method))) {
                return $this->newRouter();
            }
            else {
                /** set module/controller/method to request object */
                $this->_request->setModule($module);
                $this->_request->setController($controller);
                $this->_request->setAction($method);
                !empty($paramsArray) ? $this->_request->setParams(explode('/', $paramsArray)): null;
            }
        }
        return $this->dispatch($this->_request);
    }


    private function checkModuleControllerActionExists($module, $controller, $method) {
        $className = $module . '_Controller_' . $controller;
        if(class_exists($className)) {
            if(method_exists($className, $method)) {
                return true;
            }
        }
        return false;
    }


    /*
     * evaluates request objects, checks if any of module/controller and action are set, if they are ALL
     * set function will return false, and skip to dispatching the request object
     */
    private function checkRequestObjectIsSet()
    {
        if(!($this->_request->getModule() && $this->_request->getController() && $this->_request->getAction())) {
            return true;
        }
        return false;
    }


}
