<?php

class Core_Controller_Router_Default extends Core_Controller_Router_Abstract
{

    const DEFAULT_MODULE    = 'Core';
    const DEFAULT_CONTROLLER = 'Error';
    const DEFAULT_ACTION     = 'errorAction';

    public function match($request)
    {
        $this->_request = $request;
        $this->_request->setModule(self::DEFAULT_MODULE);
        $this->_request->setController(self::DEFAULT_CONTROLLER);
        $this->_request->setAction(self::DEFAULT_ACTION);


        return $this->dispatch($this->_request);
    }
}