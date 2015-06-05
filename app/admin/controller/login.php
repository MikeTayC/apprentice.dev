<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function __construct()
    {
        echo "this is a admin controller login test<br>";
    }

    public function test($param,$test)
    {
        echo 'this is a test method from the admin controller login';
        echo $param;
        echo $test;
    }
}