<?php

/**
 * Class Core_Controller_Abstract
 *
 * All controllers extend this class, has important functions needed
 * for proper functionality  of the framework
 **/
abstract class Core_Controller_Abstract
{
    /** @var  reference to current view block */
    public $block;

    /**
     * loads view layout, creates a model  that can access some json data
     * this data can then be
     *
     * @param $default = true: set to true, will automatically load default layout,
     * if false no default layout will be loadded
     * @return $this->block : has all necessary view information, will be an instance of
     * view object, capable of being rendered, and data can be set upon it
     **/
    public function loadLayout($default = true)
    {
        $model = Bootstrap::getModel('page/design_json');
        $model->setJsonDesign();
        $layoutHandle = $this->getHandle();
        $this->block = $model->buildBlocks($layoutHandle, $default);
        return $this->block;
    }

    /**
     * Will render the reference to the current block
     **/
    public function render()
    {
        $this->block->render();
    }

    /**
     * Returns the layout handle,
     * which will look like model_controller_action,
     *
     * Used for determining which set of design blocks to load
     * @return string
     */
    private function getHandle()
    {
        $request = Core_Model_Request::getInstance();
        $layoutHandle = strtolower($request->getModule() . '_' . $request->getController() . '_' . substr($request->getAction(), 0, -6));
        return $layoutHandle;
    }

    /**
     * Function enables internal redirects
     * @param $module : module/directory where controller is located controller
     * @param $controller : controller we are trying to gain access too
     * @param $action : the method within controller that we want to use
     * @param $param : optional argument to allow for arguments in the redirect
     */
    public function redirect($module,$controller,$action,$param = null)
    {
        $request = Core_Model_Request::getInstance();
        $request->setModule($module)
                ->setController($controller)
                ->setAction($action);
        if($param) {
            $request->setParams($param);
        }

        $request->continueDispatching();
    }

    /**
     * redirects the actual url, necessary so access code does not show
     * Used when you need to direct out of current model and to a specific page
     *
     * @param $module : module of desired redirect
     * @param $controller : controller of desired redirect
     * @param $action : action of desired redirect
     * @param null $param : optional paramaers to pass
     */
    public function headerRedirect($module, $controller, $action, $param = null)
    {
        $headerURL = 'Location: http://apprentice.dev/' . $module . '/' . $controller . '/' . $action;
        if($param) {
            $headerURL .= '/' . $param;
        }
        header($headerURL);
        exit;
    }

    /**
     * redirects the actual url, necessary so access code does not show
     * redirects to index of current model of specified controller
     *
     * @param $controller : contrller of desired redirect
     */
    protected function _thisModuleRedirect($controller)
    {
        $module = Core_Model_Request::getInstance()->getModule();
        $headerURL = 'Location: http://apprentice.dev/' . $module . '/' . $controller . '/' . 'index';
        header($headerURL);
        exit;
    }

    /**
     * Redirects current module with parameters
     *
     * @param $controller: desired controller of redirect
     * @param $action  :desired action of redirect
     * @param $param : desired params of redirect
     **/
    protected function _thisModuleRedirectParams($controller, $action, $param)
    {
        $module = Core_Model_Request::getInstance()->getModule();
        $headerURL = 'Location: http://apprentice.dev/' . $module . '/' . $controller . '/' . $action;
        if($param) {
            $headerURL .= '/' . $param;
        }
        header($headerURL);
        exit;
    }

    /**
     * Reloads the current page
     **/
    public function reload()
    {
        $request = Core_Model_Request::getInstance();
        $module = $request->getModule();
        $controller = $request->getController();
        $action = $request->getAction();

        $this->headerRedirect($module, $controller, $action);
    }




}