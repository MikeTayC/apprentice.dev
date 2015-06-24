<?php

class Page_View_Error extends Page_View_Abstract
{
    public function __construct() {
        $this->setTitle('Error Title');
        $this->setHeader('Error Header');
        $this->setError('404 page does not exist!');
        $this->setFooter('Error footer');
    }
}