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
        $actionHandles = $test->getActionHandle();
        $block = $test->buildBlocks('admin_login_index');
        $block->render();
    }

}
