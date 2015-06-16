<?php

class Admin_Controller_Index
{
    public function indexAction()
    {
        Bootstrap::getView('admin/login'); //([admin == module name in config]/[php file])
    }
}