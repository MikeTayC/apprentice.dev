<?php

require_once "lib/bootstrap.php";

$app = new Bootstrap();

$test = new Admin_Controller_Login();

$test2 = new Module_Controller_Test();

$test3fail = new Module_Controller_TestFail();
