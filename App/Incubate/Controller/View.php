<?php

class Incubate_Controller_View extends Incubate_Controller_Admin
{
    public function progressAction()
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();

        $this->_checkIfUserIsLoggedIn();

        $lesson = Bootstrap::getModel('lesson/model');
        $allLessonData = $lesson->loadAll();

        $allStudentUsers = Bootstrap::getModel('user/model')->loadAllStudents();

        $view = $this->loadLayout();
        $view->getContent()->setUsers($allStudentUsers)->setLessons($allLessonData);
        $view->render();
    }

}