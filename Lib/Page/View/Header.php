<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 6/17/15
 * Time: 5:57 PM
 */
class Page_View_Header extends Page_View_Abstract{

    public function __construct() {
        $this->setTitle('Page View Header Set Title<br>');
        $this->setHeader('Page View Header Set Header<br>');
    }
}