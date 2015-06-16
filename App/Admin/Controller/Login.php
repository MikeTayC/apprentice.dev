<?php
/*
 * Test class
 */
class Admin_Controller_Login
{
    public function testAction($param,$test)
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
