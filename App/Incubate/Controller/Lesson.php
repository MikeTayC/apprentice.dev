<?php

class Incubate_Controller_Lesson extends Core_Controller_Abstract
{

        /*
         * indexAction() is responsible for loading all the created lessons
         * Here, admins can create update delete lessons
         * standard users can only read a list of all lessons
         *
         * if session variable : logged_in is false, return to login
         * if session variable : admin_status is true, then load create and edit links
         * if admin_status is false, then just load list of current courses
         */
    public function indexAction()
    {
        $view = $this->loadLayout();
        if(Core_Model_Session::)
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