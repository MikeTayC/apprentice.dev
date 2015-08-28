<?php

/**
 * Class Core_Controller_Error
 *
 * Default error controller
 **/
class Core_Controller_Error extends Core_Controller_Abstract
{
    public function errorAction()
    {
        $this->loadLayout($default = false);
        $this->render();
    }
}