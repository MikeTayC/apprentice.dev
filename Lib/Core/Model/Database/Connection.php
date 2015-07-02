<?php

class Core_Model_Database_Connection
{
    private static $_connection = null;

    private function __construct(){}

    public static function getInstance()
    {
        if(self::$_connection == null){
            try {
                /*
                 * PDO has three params upon construction
                 * specify driver, username, password
                 * specify driver: mysql,needs host and database name
                 * host = server you are running uysually 127.0.0.1
                 *
                 */
                self::$_connection = new PDO('mysql:host=127.0.0.1;dbname=kohana', 'root', 'root');
                self::$_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo $e->getMessage();
            }
        }
        return self::$_connection;
    }

    public static function disconnect(){
        self::$_connection = null;
    }
}
