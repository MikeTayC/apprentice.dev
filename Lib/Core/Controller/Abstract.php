<?php

abstract class Core_Controller_Abstract
{
    protected $_crudModel;
    public function loadLayout($default = true)
    {
        $test = Bootstrap::getModel('page/design_json');
        $test->setJsonDesign();
        $layoutHandle = $this->getHandle();
        $block = $test->buildBlocks($layoutHandle, $default);

        $block->render();
    }

    private function getHandle()
    {
        $request = Core_Model_Request::getInstance();
        $layoutHandle = strtolower($request->getModule() . '_' . $request->getController() . '_' . substr($request->getAction(), 0, -6));
        return $layoutHandle;
    }


    public function redirect($module,$controller,$action)
    {
        $request = Core_Model_Request::getInstance();
        $request->setModule($module)
                ->setController($controller)
                ->setAction($action)
                ->continueDispatching();
    }

}