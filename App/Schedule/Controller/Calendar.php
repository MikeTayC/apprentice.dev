<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Schedule_Controller_Calendar extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_flashCheck();
        $this->render();
    }
}
