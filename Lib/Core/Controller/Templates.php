<?php

class Core_Controller_Templates extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout($default = false);
    }
}