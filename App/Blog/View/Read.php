<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 10:44 AM
 */
class Blog_View_Read extends Page_View_Abstract
{
    public function __construct(){
        if(isset($_POST['data'])) {
            $this->setBody($_POST['data']['body']);
            $this->setAuthor($_POST['data']['author']);
        }
    }
}