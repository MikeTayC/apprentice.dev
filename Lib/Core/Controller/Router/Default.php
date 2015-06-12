<?php

class Core_Controller_Router_Default extends Core_Controller_Router_Abstract
{

    protected $defaultModule     = 'Core';
    protected $defaultController = 'Core_Controller_Error';
    protected $defaultAction     = 'errorAction';

    public function match($request)
    {
        $this->module     = $this->defaultModule;
        $this->controller = $this->defaultController;
        $this->action     = $this->defaultAction;

        return $this->dispatch($request);
    }
}