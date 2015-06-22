<?php

class Core_Controller_Error extends Core_Controller_Abstract
{
    public function errorAction()
    {
        $this->loadLayout('core_error_error');
    }
}