<?php

class Incubate_Controller_Create extends Incubate_Controller_Admin
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->auth->isLoggedIn()) {
            $this->redirect('Incubate','Index','indexAction');
        }
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
        if(!empty($_POST)) {
            $tagArray = array();

            foreach ($_POST as $post) {
                if(is_numeric($post)){
                    $tagArray[] = $post;
                }
            }
            $user = new Incubate_Model_User();

            $user->create('lesson', array(
                'name' => $_POST['name'],
                'description' => $_POST['description']
            ));

            $db = Core_Model_Database::getInstance()->get('lesson', array('name', '=', $_POST['name']));
            $newLesson = $db->first();

            foreach($tagArray as $tag) {
                $user->create('lesson_tag_map', array(
                   'lesson_id' => $newLesson->lesson_id,
                    'tag_id' => $tag
                ));
            }

            $this->redirect('Incubate', 'Lesson', 'indexAction');
        }

        $db = Core_Model_Database::getInstance()->get('tag', array('1', '=', '1'));
        $tag = $db->results();
        $view->getContent()->setTag($tag);
        $view->getDefault()->setUser($this->user);
        $view->render();
    }

    public function tagAction()
    {

        $view = $this->loadLayout();
        if(!empty($_POST)) {

            $user = new Incubate_Model_User();
            $user->create('tag', array(
               'name' => $_POST['name']
            ));
        }
        $db = Core_Model_Database::getInstance()->get('tag', array('1', '=', '1'));
        $tag = $db->results();
        $view->getDefault()->setUser($this->user);
        $view->getContent()->setTag($tag);
        $view->render();
    }

}