<?php

class Admin_Controller_Index
{
    public function indexAction()
    {
        Bootstrap::getView('admin/login'); //([admin == module name in config]/[php file])
        $request = Core_Model_Request::getInstance();
        $request->setModule('Admin')
            ->setController('Admin_Controller_Login')
            ->setAction('indexAction')
            ->continueDispatching();
        $TEST = 'TEST';

    }
}