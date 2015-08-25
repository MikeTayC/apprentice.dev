<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class Lesson_Controller_View extends Incubate_Controller_Abstract
{
	public function indexAction()
	{
		//load view
		$view = $this->loadLayout();
        $this->_flashCheck();

		//load model
		$allLessonModels = Bootstrap::getModel('lesson/model')->loadAll();

		//if lesson data is properly retrieved from database and available, bind data to views content block
		$view->getContent()->setLesson($allLessonModels);

		$view->render();
	}

    public function idAction($lessonId)
    {
        //TODO REFACTOR

        $this->_idCheck($lessonId, 'lesson');

        //get lessson data
        $lesson = Bootstrap::getModel('lesson')->load($lessonId);

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
}