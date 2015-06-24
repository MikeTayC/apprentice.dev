<?php

abstract class Core_Controller_Abstract
{
    public function loadLayout($default = true)
    {
        $test = Bootstrap::getModel('core/design_json');
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
}