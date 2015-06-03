<?php

function autoload($className)
{
    $directory = array('lib/', 'app/');
    foreach( $directory as $current_dir) {
        $fileName = $current_dir . str_replace('_', DIRECTORY_SEPARATOR, strtolower($className)) . '.php';

        if (is_readable($fileName)) {
            require $fileName;
        }
    }
}

spl_autoload_register('autoload');



