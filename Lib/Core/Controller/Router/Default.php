<?php

class Core_Controller_Router_Default extends Core_Controller_Router_Abstract
{

    const DEFAULT_MODULE    = 'Core';
    const DEFAULT_CONTROLLER = 'Error';
    const DEFAULT_ACTION     = 'errorAction';

    public function match($request)
    {
        $this->_request->getModule(self::DEFAULT_MODULE);
        $this->_request->getController(self::DEFAULT_CONTROLLER);
        $this->_request->getAction(self::DEFAULT_ACTION);


        return $this->dispatch($request);
    }
}