<?php
/*
 * Test class
 */
class Admin_Controller_Uikit extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->render();
    }
}
