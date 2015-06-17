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
abstract class Core_Controller_Router_Abstract
{

    protected $_request;

    /*
     * the goal of this method:
     * 1) examine a request URL;
     * 2) determine which Modules might contain the appropriate Controller,
     * 3) determine which Controller in that module we should use
     * 4) deterine which action on the Controller we should call
     * 5) then tell the Controller to dispatch that action
     * ---
     * if a suitable module/Controller/action is not found, the method will return false
     * and the front Controller object moves to the next routers match method
     */
    abstract public function match($request);

    /*
     * dispatches the appropriate Controller and action,
     * implemented two ways
     */
    public function run()
    {
            $controller = $this->_request->getModule() . '_Controller_' . $this->_request->getController();
            $action = $this->_request->getAction();
            $params = !is_array($this->_request->getParams()) ? array($this->_request->getParams()) : $this->_request->getParams();
            call_user_func_array(array(new $controller, $action), $params);
    }

    /*
     * returns false to break of match method, in the event the Router needs to change
     */
    public function newRouter()
    {
        return false;
    }

    /*
     * @param is the request object
     * will stop dispatching and run the Controller
     * returns true to break out of match function
     */
    public function dispatch($request)
    {
        $request->stopDispatching();
        $this->run();
        return true;
    }
}