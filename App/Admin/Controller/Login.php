<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function indexAction()
    {
        $test = Bootstrap::getModel('core/design_json');
        $test->setJsonDesign();
        $test->getActionHandle();
        $block = $test->buildBlocks();
        $block->render();
    }

}
