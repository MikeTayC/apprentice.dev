<?php
class Academy_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if(Helpers_Session::exists('home')) {
            echo '<p>' . Helpers_Session::flash('home') . '</p>';
        }
        $this->loadLayout();
    }
}