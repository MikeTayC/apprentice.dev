<?php

/**
 * Class Core_Controller_Abstract
 */
abstract class Core_Controller_Abstract
{
    public $block;

    /*
     * loads view layout, creates a model  that can access some json data
     * this data can then be
     */
    public function loadLayout($default = true)
    {
        $model = Bootstrap::getModel('page/design_json');
        $model->setJsonDesign();
        $layoutHandle = $this->getHandle();
        $this->block = $model->buildBlocks($layoutHandle, $default);
        return $this->block;
    }

    public function render()
    {
        $this->block->render();
    }
    private function getHandle()
    {
        $request = Core_Model_Request::getInstance();
        $layoutHandle = strtolower($request->getModule() . '_' . $request->getController() . '_' . substr($request->getAction(), 0, -6));
        return $layoutHandle;
    }



    /**
     * @param $module : module/directory where controller is located controller
     * @param $controller : controller we are trying to gain access too
     * @param $action : the method within controller that we want to use
     */
    public function redirect($module,$controller,$action)
    {
        $request = Core_Model_Request::getInstance();
        $request->setModule($module)
                ->setController($controller)
                ->setAction($action)
                ->continueDispatching();
    }

    /*
     * redirects the actual url, necessary so access code does not show
     */
    public function headerRedirect($module, $controller, $action)
    {
        $headerURL = 'Location: http://apprentice.dev/' . $module . '/' . $controller . '/' . $action;
        header($headerURL);
    }
}