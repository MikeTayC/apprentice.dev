<?php

class Core_Controller_Error
{
    public function __construct()
    {
        echo "this is a core controller error test<br>";
    }
    public function errorAction()
    {
        echo 'mad errors';
    }
}