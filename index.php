<?php
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once "Lib/Bootstrap.php";

Bootstrap::setIncludePath();

Bootstrap::registerAutoload();

Bootstrap::registerGoogleFiles();

Bootstrap::initJsonConfig();

Bootstrap::setBaseUrl();

Bootstrap::registerObservers();

Bootstrap::loadRunFrontController();

