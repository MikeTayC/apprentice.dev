<?php

class Core_Model_Database
{
    protected $_handle;
    protected $_query;

    public function dbConnection()
    {
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

    public function printDrivers(){
        print_r(PDO::getAvailableDrivers());
    }

    public function standardQuery()
    {
        $this->_query = $this->_handle->query('SELECT * FROM posts');
        $this->_query->setFetchMode(PDO::FETCH_CLASS, 'Blog_Model_Post');
    }

    public function fetchStandard()
    {
        echo '<pre>';
        while($r = $this->_query->fetch()){
            echo $r->entry . '<br>';
        }
        echo'</pre>';
    }

    public function insertData()
    {
        $author = 'Elaine';
        $body = 'whatever whatever';
        $sql = "INSERT INTO posts (author, body) VALUES (?, ?)";

        $this->_query = $this->_handle->prepare($sql);

        $this->_query->execute(array($author, $body));
    }
}