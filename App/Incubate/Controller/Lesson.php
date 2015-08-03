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

        //redirect if not logged in
        if(!Core_Model_Session::get('logged_in')){
           $this->redirect('Incubate', 'Login', 'indexAction');
        }

        //load view
        $view = $this->loadLayout();

        //load model
        $user = Bootstrap::getModel('incubate/user');

        $lessonData = $user->getAllLessonsFromLessonTable();
        $tagData = $user->getAllTagsFromTagTable();
        $mapData = $user->getTagLessonMap();

        //if lesson data is properly retrieved from database and available, bind data to views content block
        if($lessonData && $tagData && $mapData) {
            $view->getContent()->setLesson($lessonData);
            $view->getContent()->setTag($tagData);
            $view->getContent()->setMap($mapData);
        }

        $view->render();
    }

}