<?php
error_reporting(E_ALL);
ini_set('error_reporting', 1);

require_once "Lib/Bootstrap.php";

Bootstrap::setIncludePath();

Bootstrap::registerAutoload();

Bootstrap::initJsonConfig();

Bootstrap::loadRunFrontController();
