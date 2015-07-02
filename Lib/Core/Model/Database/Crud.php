<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/1/15
 * Time: 4:16 PM
 */
class Core_Model_Database_Crud
{
    protected $_pdoHandle;

    public function __construct(){
        $this->_pdoHandle = Core_Model_Database_Connection::getInstance();
    }

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

            $sql = "INSERT INTO posts(author, body) VALUES (?,?)";

            $query = $this->_pdoHandle->prepare($sql);

            $query->execute(array($author, $body));

            header("Location: ../post/index");
        }
    }

    public function update($id)
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
        if(empty($id)){
            $idError = "No id";
            $validInput = false;
        }
        if($validInput) {

            $sql = "UPDATE posts SET author = ?, body = ? WHERE id = ?";

            $query = $this->_pdoHandle->prepare($sql);

            $query->execute(array($author, $body, $id));

            header("Location: ../../post/index");
        }
    }

    public function read($id){

        $sql = "SELECT * FROM posts WHERE id = ?";

        $query = $this->_pdoHandle->prepare($sql);

        $query->execute(array($id));

        $readData = $query->fetch(PDO::FETCH_ASSOC);

        return $readData;
    }

    public function delete($id){
        $sql = "DELETE FROM posts WHERE id = ?";

        $query = $this->_pdoHandle->prepare($sql);

        $query->execute(array($id));

        header("Location: ../../post/index");
    }
}