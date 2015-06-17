<?php

class Admin_Controller_Index
{
    public function indexAction()
    {
        $view = Bootstrap::getView('page/view'); //([admin == module name in config]/[php file])
        $view->setTemplate('Lib/Page/View/template/view.phtml');
        $view->setTitle('Mike\'s App');
        $view->setContent('<h1>Here is some content!</h1>');
        echo $view->render();
//        $request = Core_Model_Request::getInstance();
//        $request->setModule('Admin')
//            ->setController('Login')
//            ->setAction('testAction')
//            ->setParams(array('testparam1', 'whatever'))
//            ->continueDispatching();
    }
}