<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/10/15
 * Time: 9:10 AM
 */
class Incubate_Controller_Tagit extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout($default = false);
        $this->render();
    }
}