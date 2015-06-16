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
    const DEFAULT_MODULE    = 'Core';
    const DEFAULT_CONTROLLER = 'Index';
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
        if($this->checkRequestObject()) {

            $module      = !empty($path[0]) ? $path[0] : null;
            $controller  = !empty($path[1]) ? $path[1] : null;
            $method      = !empty($path[2]) ? $path[2] : null;
            $paramsArray = !empty($path[3]) ? $path[3] : null;


            if (empty($module)) {

                $this->_request->setModule(self::DEFAULT_MODULE);
                $this->_request->setController(self::DEFAULT_CONTROLLER);
                $this->_request->setAction(self::DEFAULT_ACTION);

                return $this->dispatch($this->_request);
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
        }

        return $this->dispatch($this->_request);
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
                    $this->_request->setModule($module);
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
        $controllerConfig = Core_Model_Config_Json::getControllerConfig($this->_request->getModule());
        if (!empty($controllerConfig)) {
            if(array_key_exists($controller, $controllerConfig)) {
                $this->_request->setController($controller);
                return true;
            }
        }
        return false;
    }

    /*
     * will check if method exists before setting action
     *
     */
    private function setAction($method)
    {
        $method .= self::ACTION_METHOD_IDENTIFIER;
        $controllerConfig = Core_Model_Config_Json::getControllerConfig($this->_request->getModule());
        if(!empty($controllerConfig)) {
            $controller = $controllerConfig[$this->_request->getController()];
            if (method_exists($controller, $method)) {
                $this->_request->setAction($method);
                return true;
            }
        }
        return false;
    }

    /*
     * sets the params var with the array specified
     */
    private function setParams(array $paramsArray)
    {
        $this->_request->setParams($paramsArray);
    }

    /*
     * evaulates request objects, checks if any of module/controller and action are set, if they are ALL
     * set function will return false, and skip to dispatching the request object
     */
    private function checkRequestObject(){
        if(!($this->_request->getModule() && $this->_request->getController() && $this->_request->getAction())) {
            return true;
        }
        return false;
    }

}
