<?php

class Incubate_Controller_Lesson extends Incubate_Controller_Abstract
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
        $this->_checkIfUserIsLoggedIn();

        //load view
        $view = $this->loadLayout();

        $this->_flashCheck();

        //load model
        $allLessonModels = Bootstrap::getModel('incubate/lesson')->loadAll();


        //if lesson data is properly retrieved from database and available, bind data to views content block

        $view->getContent()->setLesson($allLessonModels);

        $view->render();
    }

    public function editAction($lessonId)
    {
        $request = $this->_getRequest();

        if ($request->isPost()) {

            //set post and session data
            $lessonId = $this->_sessionGet('lesson_id');
            $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);
            $lessonTags = $request->getPost('tags');

            //prepare tags
            $tagArray = $this->explode($lessonTags);

            //set new updates
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            $lesson->setTags($tagArray)->save();


            /*
             * load the lesson data to be updated based on the lesson id
             * call update funciton
             * save the updates
             */

            $this->_sessionDelete('lesson_id');
            $this->_successFlash('Successfully updated');
            $this->_thisModuleRedirect('lesson');
        }
        elseif($this->_idCheck($lessonId, 'lesson')) {

            //loads the lesson based on id, dispatched events will set the tag name array on the lesson object
            $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

            //store lesson id in the session
            $this->_sessionSet('lesson_id', $lessonId);

            //load view, set data for use in edit form, and render
            $view = $this->loadLayout();
            $view->getContent()->setLesson($lesson);
            $view->render();
        }
        else {
            $this->_dangerFlash('You did not specify a lesson to edit');
            $this->_thisModuleRedirect('index');
        }
    }

    public function deleteAction($lessonId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        $this->_idCheck($lessonId, 'lesson');

        //delete current tag map of lesson, then delte the lessson
        Bootstrap::getModel('incubate/lesson')->load($lessonId)->delete();

        $this->_successFlash('Successfully deleted');

        $this->_thisModuleRedirect('lesson');
    }

}