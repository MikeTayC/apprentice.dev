<?php

class Page_View_View extends Page_View_Abstract
{
    public function __construct()
    {
        $this->setHeader('page view View set header');
        $this->setContent('page view View set Content');
        $this->setFooter('page view View set footer');
    }
}