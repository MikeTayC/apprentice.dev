<?php

class Core_Controller_Error extends Core_Controller_Abstract
{
    public function errorAction()
    {
        $this->loadLayout($default = false);
        $this->render();
    }
}