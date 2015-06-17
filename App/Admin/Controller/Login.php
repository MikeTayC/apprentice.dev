<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function indexAction()
    {
        $jsonDesign = Core_Model_Design_Json::getCurrentClassDesignJson(__CLASS__);

        $header = Bootstrap::getView($jsonDesign['header']['type']);
        $header->setTemplate($jsonDesign['header']['template']);

        $viewContent = Bootstrap::getView($jsonDesign['content']['type']); //([admin == module name in config]/[php file])
        $viewContent->setTemplate($jsonDesign['content']['Template']);

        $loginForm = Bootstrap::getView($jsonDesign['type']);
        $loginForm->setTemplate($jsonDesign['template']);

        $footer = Bootstrap::getView($jsonDesign['footer']['type']);
        $footer->setTemplate($jsonDesign['footer']['template']);

        $header->render();
        $viewContent->setContent($loginForm->render());
        $footer->render();
    }

}
