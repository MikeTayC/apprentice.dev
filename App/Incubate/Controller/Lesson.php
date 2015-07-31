<?php

class Incubate_Controller_Lesson extends Incubate_Controller_Admin
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
        $db = Core_Model_Database::getInstance();
        $tagMap = $db->get('lesson_tag_map', array('1', '=', '1'))->results();

        $lessons = $db->get('lesson', array('1', '=', '1'))->results();

        $tags = $db->get('tag', array('1', '=', '1'))->results();

        $view->getDefault()->setUser($this->user);
        $view->getContent()->setLesson($lessons);
        $view->getContent()->setMap($tagMap);
        $view->getContent()->setTag($tags);
        $view->render();
    }

}