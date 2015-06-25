<?php

class Admin_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout($default = false);
//        $view = Bootstrap::getView('page/view'); //([admin == module name in config]/[php file])
//        $loginForm = Bootstrap::getView('page/view');
//        $loginForm->setTemplate('App/Admin/View/Template/Login.phtml');
//        $view->setTemplate('Lib/Page/View/Template/View.phtml');
//        $view->setTitle('Mike\'s App');
//        $loginForm->render();
//        echo $view->render();
//        $request = Core_Model_Request::getInstance();
//        $request->setModule('Admin')
//            ->setController('Login')
//            ->setAction('testAction')
//            ->setParams(array('testparam1', 'whatever'))
//            ->continueDispatching();
    }
}