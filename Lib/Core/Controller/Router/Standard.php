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
    protected $_defaultModule     = 'Core';
    protected $_defaultController = 'Core_Controller_Index';
    protected $_defaultAction     = 'indexAction';

    protected $_request;

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
        $path = $request->requestUri();

        $path = explode('/', $path, 4);

        if(!($this->_request->getModule() && $this->_request->getController() && $this->_request->getAction())) {

            $module = !empty($path[0]) ? $path[0] : null;
            $controller = !empty($path[1]) ? $path[1] : null;
            $method = !empty($path[2]) ? $path[2] : null;
            $paramsArray = !empty($path[3]) ? $path[3] : null;


            if (empty($module)) {

                $this->module = $this->_defaultModule;
                $this->controller = $this->_defaultController;
                $this->action = $this->_defaultAction;

                $this->_request->setModule($this->module);
                $this->_request->setController($this->controller);
                $this->_request->setAction($this->action);

                return $this->dispatch($request);
            }

            if (!$this->setModule($module)) {
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
        } else {

            $this->module = $request->getModule();
            $this->controller = $request->getController();
            $this->action = $request->getAction();

        }

        return $this->dispatch($request);
    }


    /*
     * todo change module check method
     * checks if the module exists, by checking if the module has an index Controller.
     * sets module and returns true if it does, so match function can continue
     * returns false if does not exist, so match we will reroute to next Router
     *
     * Need to start makign singltons
     * instantiating Core_Model_Module_Config to test if this module exists
     */
    private function setModule($module)
    {
        $module = ucfirst($module);
        $modulesConfig = Core_Model_Config_Json::getModulesConfig();
        if(!empty($modulesConfig)) {
            foreach($modulesConfig as $moduleName => $codePool) {
                if($module == ucfirst($moduleName)) {
                    $this->module = $module;
                    $this->_request->setModule($this->module);
                    return true;
                }
            }
            return false;
        }
    }

    /*
     * This function will check if a Controller exists by checking if the class exists
     *
     * catch function can continue
     * returns false if does not exist, so match we will reroute to next Router
     */
    private function setController( $controller)
    {
        $controller = ucfirst($controller);
        $className = $this->module . '_' . 'Controller' . '_' . $controller;
        if (class_exists($className)) {
            $this->controller = $className;
            $this->_request->setController($this->controller);
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
    private function setAction($method)
    {
        $method .= self::ACTION_METHOD_IDENTIFIER;
        if (method_exists($this->controller, $method)) {
            $this->action = $method;
            $this->_request->setAction($this->action);
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
