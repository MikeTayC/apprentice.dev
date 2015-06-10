<?php
/*
 * Request object will parse and store URI info with in the $pathArray array
 */
class Core_Controller_Router_Request
{
    protected $dispatched = false;

    public function requestUri()
    {
        $_SERVER['REQUEST_URI'];
        $pathUri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),'/');

        return $pathUri;
    }

    public function isDispatched()
    {
        return $this->dispatched;
    }

    public function stopDispatching()
    {
        $this->dispatched = true;

        return $this;
    }
}