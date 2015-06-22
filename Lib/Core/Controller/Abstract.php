<?php

abstract class Core_Controller_Abstract
{
    public function loadLayout()
    {
        $test = Bootstrap::getModel('core/design_json');
        $test->setJsonDesign();
        $layoutHandle = $this->getHandle();
        $block = $test->buildBlocks($layoutHandle);
        $block->render();
    }

    private function getHandle()
    {
        $layoutHandle = strtolower(str_replace('/', '_', Core_Model_Request::$pathUri));

        return $layoutHandle;
    }
}