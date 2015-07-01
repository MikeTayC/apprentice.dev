<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 6/30/15
 * Time: 4:36 PM
 */
class Blog_Controller_Post extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout();
    }

    public function createAction()
    {
        $this->loadLayout();
    }
}