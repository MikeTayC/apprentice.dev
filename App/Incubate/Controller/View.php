<?php

/**
 * Class Incubate_Controller_View
 *
 * responbsible for viewing all incubator progress,
 *
 * Will show all students completed course status, against all lessons
 **/
class Incubate_Controller_View extends Incubate_Controller_Admin
{
    /**
     * Uses factory methods to load all lessons and all students
     *
     * Sets this information on view layout.
     **/
    public function progressAction()
    {
        /**
         * Loading will dispatch events and add all necessary information
         **/
        $allLessonData = Bootstrap::getModel('lesson/model')->loadAll();
        $allStudentUsers = Bootstrap::getModel('user/model')->loadAllStudents();

        /**
         * Loads views, binds information to the layout and renders
         **/
        $view = $this->loadLayout();
        $view->getContent()->setUsers($allStudentUsers)->setLessons($allLessonData);
        $view->render();
    }

}