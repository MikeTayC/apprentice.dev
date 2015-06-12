<?php
require_once "Lib/Bootstrap.php";

Bootstrap::setIncludePath();

Bootstrap::registerAutoload();

Bootstrap::initJsonConfig();

Bootstrap::loadRunFrontController();
