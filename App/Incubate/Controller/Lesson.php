<?php

class Incubate_Controller_Lesson extends Core_Controller_Admin
{

    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $view = $this->loadLayout();
        $view->getDefault()->setUser($this->user);
        $view->render();
    }

}