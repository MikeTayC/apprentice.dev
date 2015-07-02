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
        $request = Core_Model_Request::getInstance();
        $request->setModule('Blog')
                ->setController('Post')
                ->setAction('readAction')
                ->continueDispatching();
    }

    public function updateAction($id)
    {
        if(empty($_POST)) {
            $readData = $this->read($id);
            $_POST['data'] = $readData;
            $request = Core_Model_Request::getInstance();
            $request->setModule('Blog')
                ->setController('Post')
                ->setAction('updateAction')
                ->continueDispatching();
        }
        else {
            $this->update($id);
        }
    }

    public function deleteAction($id)
    {
        if(empty($_POST)){
            $request = Core_Model_Request::getInstance();
            $request->setModule('Blog')
                ->setController('Post')
                ->setAction('deleteAction')
                ->continueDispatching();
        }
        else {
            $this->delete($id);
        }
    }
}