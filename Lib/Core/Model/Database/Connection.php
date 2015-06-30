<?php

class Core_Model_Database_Connection
{
    private static $_connection = null;

    private function __construct()
    {
        //instantiate pdo connection
        try {
            /*
             * PDO has three params upon construction
             * specify driver, username, password
             * specify driver: mysql,needs host and database name
             * host = server you are running uysually 127.0.0.1
             *
             */
            $this->_handle = new PDO('mysql:host=127.0.0.1;dbname=kohana', 'root', 'root');
            $this->_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if(self::$_connection == null){
            self::$_connection = new Core_Model_Database_Connection();
        }
        return self::$_connection;
    }
}
