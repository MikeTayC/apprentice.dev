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
abstract class Core_Controller_Router_Abstract
{
    /*
     * Default parameters in the case of empty uri
     */
    protected $defaultModule;
    protected $defaultController;
    protected $defaultAction;
    protected $modulePageDefault;
    /*
     * $module will store the module
     */
    protected $module;

    /*
     * $controller will store the controller class to be loaded
     */
    protected $controller;

    /*
     * $action will store method/action to be called, if specified
     */
    protected $action;

    /*
     * $params stores any params for methods, if specified
     */
    protected $params = array();


    abstract public function setModule($module);
    /*
     * Function accepts three params: module/controller
     * This function will set the given controller class
     */
    abstract public function setController($controller);

    /*
     * will check if method exists before setting action
     */
    abstract public function setAction($method);

    /*
     * sets the params var with the array specified
     */
    public function setParams(array $paramsArray)
    {
        $this->params = $paramsArray;
        return $this;
    }
    /*
     * the goal of this method:
     * 1) examine a request URL;
     * 2) determine which modules might contain the appropriate controller,
     * 3) determine which controller in that module we should use
     * 4) deterine which action on the controller we should call
     * 5) then tell the controller to dispatch that action
     * ---
     * if a suitable module/controller/action is not found, the method will return false
     * and the front controller object moves to the next routers match method
     */
    abstract public function match($request);

    /*
     * dispatches the appropriate controller and action,
     * implemented two ways
     */
    public function run()
    {
        call_user_func_array(array(new $this->controller, $this->action), $this->params);
    }
}