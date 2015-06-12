<?php

require_once "lib/bootstrap.php";

Bootstrap::setIncludePath();

Bootstrap::registerAutoload();

Bootstrap::initJsonConfig();

Bootstrap::loadRunFrontController();
