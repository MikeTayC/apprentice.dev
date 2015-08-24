<?php

class Incubate_Controller_View extends Incubate_Controller_Abstract
{
    public function lessonAction($lessonId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_idCheck($lessonId, 'lesson');

        //get lessson data
        $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

        //get asssoiciated tag names
        $lessonTagMap = $lesson->getTagLessonMapForLesson();
        $lessonTags = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($lessonTagMap);

        //append descrition and tags into readable format
        $descriptionAndTags = $this->appendTagsAndDescription($lesson->getDescription(), $lessonTags);
        $lesson->setDescription($descriptionAndTags);

        //ready duration parameter
        $duration = $lesson->getDuration() . 'Min.';
        $lesson->setDuration($duration);

        //store lesson id in session
        $this->_sessionSet('lesson_id', $lessonId);

        //load,bind and render
        $view = $this->loadLayout();
        $view->getContent()->setLesson($lesson);
        $view->render();
    }

    public function allAction()
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