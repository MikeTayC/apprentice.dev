<?php

class Incubate_Controller_Create extends Core_Controller_Admin
{

    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {
        $view = $this->loadLayout();
        $view->getDefault()->setUser($this->user);
        $view->render();
    }

    public function lessonAction()
    {
        $view = $this->loadLayout();
        $view->getDefault()->setUser($this->user);
        if(!empty($_POST)) {
            $user = new Incubate_Model_User();
            $user->createLesson(array(
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ));
        }
        $view->render();
    }

    public function tagAction()
    {
        $db = Core_Model_Database::getInstance()->get('tag', array('1', '=', '1'));
        $tag = $db->results();

        $view = $this->loadLayout();
        $view->getDefault()->setUser($this->user);
        $view->getContent()->setTag($tag);
        if(!empty($_POST)) {

        }
        $view->render();
    }

}