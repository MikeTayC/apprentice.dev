<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 1:44 PM
 */
class Blog_Controller_Update extends Core_Controller_Abstract
{
    public function updateAction()
    {
        $this->updateModel();
        $this->loadLayout();
    }

    public function updateModel()
    {
        if(isset($_POST)) {
            $updateModel = new Core_Model_Database_Update();
            $updateModel->update();
        }
    }
}