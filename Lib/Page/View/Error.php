<?php

class Page_View_Error extends Page_View_Abstract
{
    public function __construct() {
        $this->setError('404 page does not exist!');
    }
}