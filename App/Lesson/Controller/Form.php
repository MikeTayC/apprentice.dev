<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 10:45 AM
 */
class Lesson_Controller_Form extends Incubate_Controller_Admin
{
    public function createAction()
    {
        //check if user is logged in and admin
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        //else load view
        $this->loadLayout();
        $this->render();
    }

    public function editAction($lessonId)
    {
        //loads the lesson based on id, dispatched events will set the tag name array on the lesson object
        $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

        //store lesson id in the session
        $this->_sessionSet('lesson_id', $lessonId);

        //load view, set data for use in edit form, and render
        $view = $this->loadLayout();
        $view->getContent()->setLesson($lesson);
        $view->render();
    }
}