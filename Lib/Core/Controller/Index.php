<?php

/**
 * Class Core_Controller_Index, can be used as default controller
 **/
class Core_Controller_Index extends Core_Controller_Authorization
{
    public function indexAction()
    {
        $view = $this->loadLayout();
        $view->render();
    }
}