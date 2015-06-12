<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function __construct()
    {
        echo "this is a Admin Controller login test<br>";
    }

    public function test($param,$test)
    {
        echo 'this is a test method from the Admin Controller login';
        echo $param;
        echo $test;
    }

    public function indexAction()
    {
        echo "indexAction test";
    }
}
