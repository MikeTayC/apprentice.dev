<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 9:17 AM
 */
class Blog_Controller_Crud extends Core_Controller_Abstract
{
    public function createAction()
    {
        $this->create();
    }

    public function readAction($id)
    {
        $readData = $this->read($id);
        $_POST['data'] = $readData;
        $this->redirect('Blog','Post','readAction');
    }

    public function updateAction($id)
    {
        if(empty($_POST)) {
            $readData = $this->read($id);
            $_POST['data'] = $readData;
            $this->redirect('Blog','Post','updateAction');
        }
        else {
            $this->update($id);
        }
    }

    public function deleteAction($id)
    {
        if(empty($_POST)){
            $this->redirect('Blog','Post','deleteAction');
        }
        else {
            $this->delete($id);
        }
    }
}