<?php

class Incubate_Controller_View extends Incubate_Controller_Abstract
{
    public function lessonAction($lessonId)
    {
        $this->checkIfUserIsLoggedIn();

        //get lessson data
        $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

        //get asssoiciated tag names
        $lessonTagMap = $lesson->getTagLessonMapForLesson();
        $lessonTags = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($lessonTagMap);

        //append descrition and tags into readable format
        $descriptionAndTags = $this->appendTagsAndDescition($lesson->getDescription(), $lessonTags);
        $lesson->setDescription($descriptionAndTags);

        //ready duration parameter
        $duration = $lesson->getDuration() . 'Min.';
        $lesson->setDuration($duration);

        //store lesson id in session
        Core_Model_Session::set('lesson_id', $lessonId);

        //load,bind and render
        $view = $this->loadLayout();
        $view->getContent()->setLesson($lesson);
        $view->render();
    }
}