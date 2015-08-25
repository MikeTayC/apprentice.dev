<?php

class Incubate_Controller_View extends Incubate_Controller_Admin
{
    public function progressAction()
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();

        $this->_checkIfUserIsLoggedIn();

        $lesson = Bootstrap::getModel('incubate/lesson');
        $totalLessonCount = $lesson->getTotalCount();
        $allLessonData = $lesson->loadAll();

        $allStudentUsers = Bootstrap::getModel('incubate/user')->loadAllStudents();
        foreach($allStudentUsers as $student) {
            $student->setUserProgress($totalLessonCount)->setUserIncubationTime()->getAllUserCompletedCourseId();
        }

        $view = $this->loadLayout();
        $view->getContent()->setUsers($allStudentUsers)->setLessons($allLessonData);
        $view->render();
    }

}