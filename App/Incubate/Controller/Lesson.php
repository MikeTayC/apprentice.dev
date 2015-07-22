<?php

class Incubate_Controller_Lesson extends Core_Controller_Admin
{

    public function __construct()
    {
        parent::__construct();
    }
    public function indexAction()
    {

        $view = $this->loadLayout();

        $dbTagMap = Core_Model_Database::getInstance()->get('lesson_tag_map', array('1', '=', '1'));
        $tagMap = $dbTagMap->results();

        $dbLesson = Core_Model_Database::getInstance()->get('lesson', array('1', '=', '1'));
        $lessons = $dbLesson->results();

        $dbTag = Core_Model_Database::getInstance()->get('tag', array('1', '=', '1'));
        $tags = $dbTag->results();
        $view->getDefault()->setUser($this->user);
        $view->getContent()->setLesson($lessons);
        $view->getContent()->setMap($tagMap);
        $view->getContent()->setTag($tags);
        $view->render();
    }

}