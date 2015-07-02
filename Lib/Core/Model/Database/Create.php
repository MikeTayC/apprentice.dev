<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 8:48 AM
 */
class Core_Model_Database_Create
{
    protected $_pdoHandle = null;

    public function create()
    {
        $validInput = true;
        $authorError = null;
        $bodyError = null;

        $author = $_POST['author'];

        $body = $_POST['body'];

        if(empty($author)){
            $authorError = 'Please enter author name';
            $validInput = false;
        }
        if(empty($body)){
            $bodyError = "Please enter something to post";
            $validInput = false;
        }
        if($validInput) {
            $_pdoHandle = Core_Model_Database_Connection::getInstance();

            $sql = "INSERT INTO posts(author, body) VALUES (?,?)";

            $query = $_pdoHandle->prepare($sql);

            $query->execute(array($author, $body));

            header("Location: ../post/index");
        }
    }
}