<?php

class Core_View_Index extends Page_View_Abstract
{
    public function __construct() {
        $this->setTemplate('Lib/Page/View/Template/helloworld.phtml');

    }
}