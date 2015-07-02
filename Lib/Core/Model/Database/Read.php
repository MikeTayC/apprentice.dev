<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 10:18 AM
 */
class Core_Model_Database_Read
{
    protected $_pdoHandle;

    public $readData;

    public function read($id){
        $this->_pdoHandle = Core_Model_Database_Connection::getInstance();

        $sql = "SELECT * FROM posts WHERE id = ?";

        $query = $this->_pdoHandle->prepare($sql);

        $query->execute(array($id));

        $this->readData = $query->fetch(PDO::FETCH_ASSOC);

        return $this->readData;
    }
}