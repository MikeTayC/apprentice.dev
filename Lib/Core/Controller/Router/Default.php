<?php

class Core_Controller_Router_Default extends Core_Controller_Router_Abstract
{

    protected $_defaultModule     = 'Core';
    protected $_defaultController = 'Core_Controller_Error';
    protected $_defaultAction     = 'errorAction';

    public function match($request)
    {
        $this->module     = $this->_defaultModule;
        $this->controller = $this->_defaultController;
        $this->action     = $this->_defaultAction;

        return $this->dispatch($request);
    }
}