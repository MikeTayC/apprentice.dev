<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 9:17 AM
 */
class Blog_Controller_Create extends Core_Controller_Abstract
{
    public function createAction()
    {
        $this->createModel();
        $this->loadLayout();
    }

    public function createModel()
    {
        if(isset($_POST)) {
            $createModel = new Core_Model_Database_Create();
            $createModel->create();
        }
    }
}