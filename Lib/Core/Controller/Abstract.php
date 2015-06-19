<?php

abstract class Core_Controller_Abstract
{
    public function loadLayout()
    {
        $actionHandle = Core_Model_Design_Json::getActionHandle();
        $defaultHandle = Core_Model_Design_Json::getDefaultHandle();
        $urlHandle = $this->getHandle();

        $defaultView = Bootstrap::getView($defaultHandle['type']);
        $defaultView->setTemplate($defaultHandle['template']);

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

    public function renderLayout()
    {

    }

    private function getHandle()
    {
        $layoutHandle = strtolower(str_replace('/', '_', Core_Model_Request::$pathUri));

        return $layoutHandle;
    }
}