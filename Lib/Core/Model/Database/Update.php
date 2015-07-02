<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 1:36 PM
 */
class Core_Model_Database_Update
{
    protected $_pdoHandle = null;

    public function update()
    {
        $validInput = true;
        $authorError = null;
        $bodyError = null;

        $author = $_POST['author'];

        $body = $_POST['body'];

        $id = $_POST['id'];

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

            $sql = "UPDATE posts SET author = ?, body = ? WHERE id = ?";

            $query = $_pdoHandle->prepare($sql);

            $query->execute(array($author, $body, $id));

            header("Location: ../post/index");
        }
    }
}