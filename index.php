<?php
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once "Lib/Bootstrap.php";
require_once 'google-api-php-client/src/Google/autoload.php';
require_once 'google-api-php-client/src/Google/Client.php';

Bootstrap::setIncludePath();

Bootstrap::registerAutoload();

Bootstrap::initJsonConfig();

Bootstrap::loadRunFrontController();

