<?php
/**
 * Front controller provides a centralized request handling mechanism so
 * that all request will be handled by a single handler.
 *
 * TODO set default controller
 */
class Core_Front_Controller
{
    /*
     * Default parameters in the case of empty uri
     */
    const DEFAULT_CONTROLLER = "Admin_Controller_Index";
    const DEFAULT_ACTION = "index";

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

    /*
     * function is responsible for parsing URI
     */
    public function parseUri()
    {
        /*
         * $path
         * function trim removes white space from front and end of str
         */
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),'/');

        /*
         * In case url is empty set path to default controller;
         */
        if(empty($path)) {
            $this->controller = self::DEFAULT_CONTROLLER;
            $this->action = self::DEFAULT_ACTION;
        }
        /*
         * if the base path is at the start of the url path, we will remove it
         */
        if(strpos($path, BP ) === 0) {
            $path = substr($path, strlen(BP));
        }

        /*
         * Will separate uri into array, where we can separate based on controller, method and params
         * the first three elements will allow us to confirm the correct class to the correct class,
         * while the 3rd and 4th will allow us to determine which action to take
         */
        $pathArray = explode('/', $path, 5);
        $module         = $pathArray[0];
        $controllerDir  = $pathArray[1];
        $controllerFile = $pathArray[2];
        $method         = $pathArray[3];
        $paramsArray    = $pathArray[4];

        /*
         * if the module/controller directory/ controller file are set, then we have the
         * necessary ingredients to set a controller with the setController function
         */
        if(isset($module) && isset($controllerDir) && isset($controllerFile)) {

            $this->setController($module, $controllerDir, $controllerFile);
        }

        /*
         * if action is set, checks controller class for this action, then sets with setAction function
         */
        if(isset($method)) {
            $this->setAction($method);
        }

        /*
         * If params are set, sets them with the setParam function with an array from explode
         */
        if(isset($paramsArray)) {
            $this->setParams(explode('/', $paramsArray));
        }
    }

    /*
     * Function accepts three params: module/controllerDir/controllerFile
     * This function will set the given controller class
     */
    public function setController($module, $controllerDir, $controllerFile)
    {
        $className = $module . '_' . $controllerDir . '_' . $controllerFile;
        if (class_exists($className)) {
            $this->controller = $className;
            return $this;
        }
        else {
            echo "Error: class does not exist";
        }
    }

    /*
     * will check if method exists before setting action
     */
    public function setAction($method)
    {
        if(method_exists($this->controller, $method)) {
            $this->action = $method;
            return $this;
        }
        else {
            echo 'Error: this action/method does not exists in the specified class';
        }
    }

    /*
     * sets teh params var with the array specified
     */
    public function setParams(array $paramsArray)
    {
        $this->params = $paramsArray;
        return $this;
    }
    /*
     * dispatches the appropriate controller and action,
     * implemented two ways
     */
    public function run()
    {
//        $test = new $this->controller;
//        $test->{($this->action)}();
        call_user_func_array(array(new $this->controller, $this->action), $this->params);
    }
}