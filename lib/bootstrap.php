<?php
require_once 'autoloader.php';


class Bootstrap
{
    public function __construct()
    {
        spl_autoload_register('Autoloader::autoload');
    }
}
