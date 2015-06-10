<?php

class Core_Controller_Router_Default extends Core_Controller_Router_Abstract
{

    protected $defaultModule = 'core';
    protected $defaultController = 'Core_Controller_Error';
    protected $defaultAction = 'errorAction';

    public function match($request)
    {
        $path = $request->requestUri();

        $path = explode('/', $path, 4);
        $module         = $path[0];
        $controller     = $path[1];
        $method         = $path[2];
        $paramsArray    = $path[3];

        $this->checkModule($module);

        $this->checkController($controller);

        $this->checkAction($method);

        $this->checkParams($paramsArray);

        $request->stopDispatching();

        $this->run();

        return true;
    }

    /*
     * checks if module directory exists
     * TODO change check
     */
    public function setModule($module)
    {
        $className = ucfirst($module) . '_Controller_Index';
        if(!class_exists($className)) {
            $this->module = $this->defaultModule;
            return $this;
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
        if (!class_exists($className)) {
            $this->controller = $this->defaultController;
            return $this;
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
        if(!method_exists($this->controller, $method)) {
            $this->action = $this->defaultAction;
            return $this;
        }
        else {
            return false;
        }
    }

    public function checkModule($module)
    {
        if(!empty($module)) {
            $this->setModule($module);
            return $this;
        }
        else {
            $this->module     = $this->defaultModule;
            $this->controller = $this->defaultController;
            $this->action     = $this->defaultAction;
        }
    }

    public function checkController($controller)
    {
        if (isset($controller)) {
            $this->setController($controller);
        }
        else {
            $this->controller = $this->defaultController;
        }
    }

    public function checkAction($method)
    {
        if (isset($method)) {
            $this->setAction($method);
        }
        else {
            $this->action = $this->defaultAction;
        }
    }

    public function checkParams($paramsArray = array())
    {
        if (isset($paramsArray)) {
            $this->setParams(explode('/', $paramsArray));
        }
    }

}