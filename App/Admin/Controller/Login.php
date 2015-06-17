<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function testAction($param,$test)
    {
        echo 'this is a test method from the Admin Controller login';
        echo $param;
        echo $test;
    }

    public function indexAction()
    {
        $view = Bootstrap::getView('page/view'); //([admin == module name in config]/[php file])
        $view->setTemplate('Lib/Page/View/template/view.phtml');
        $view->setTitle('Please login Admin');
        $loginForm = Bootstrap::getView('page/view');
        $loginForm->setTemplate('App/Admin/View/template/login.phtml');
        $view->setContent($loginForm->render());
        echo $view->render();
    }
}
