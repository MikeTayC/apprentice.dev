<?php

class Incubate_Controller_View extends Incubate_Controller_Admin
{
    public function progressAction()
    {
        $allLessonData = Bootstrap::getModel('lesson/model')->loadAll();
        $allStudentUsers = Bootstrap::getModel('user/model')->loadAllStudents();

        $view = $this->loadLayout();
        $view->getContent()->setUsers($allStudentUsers)->setLessons($allLessonData);
        $view->render();
    }

}