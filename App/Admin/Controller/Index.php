<?php

class Admin_Controller_Index
{
    public function indexAction()
    {
        Bootstrap::getView('admin/login'); //([admin == module name in config]/[php file])
        $request = Core_Model_Request::getInstance();
        $request->setModule('Admin')
            ->setController('Login')
            ->setAction('testAction')
            ->setParams(array('testparam1', 'whatever'))
            ->continueDispatching();
    }
}