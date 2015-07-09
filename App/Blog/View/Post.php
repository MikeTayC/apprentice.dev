<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 6/30/15
 * Time: 4:41 PM
 */
class Blog_View_Post extends Page_View_Abstract
{
    protected $_crudModel;

    public function __construct()
    {
        $this->_crudModel = new Core_Model_Database_Crud();

        $allPosts = $this->_crudModel->getAllPosts();

        $this->setPosts($allPosts);
    }
}