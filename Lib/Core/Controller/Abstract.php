<?php

abstract class Core_Controller_Abstract
{
    public $block;

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


    public function redirect($module,$controller,$action)
    {
        $request = Core_Model_Request::getInstance();
        $request->setModule($module)
                ->setController($controller)
                ->setAction($action)
                ->continueDispatching();
    }


}