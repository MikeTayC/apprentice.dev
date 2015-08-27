<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 *
 * Handles all viewing of lesson data,
 * extend incubate controller abstract class
 *
 * Both students and admins can view
 */
class Lesson_Controller_View extends Incubate_Controller_Abstract
{

    /**
     * Loads lesson index,
     *
     * create/delete and edit options are only available to admins,
     * View option is avaialbe to all
     **/
	public function indexAction()
	{
		/** load view early so we can check for flash **/
		$view = $this->loadLayout();
        $this->_flashCheck();

		/** loads all lesson models **/
		$allLessonModels = Bootstrap::getModel('lesson/model')->loadAll();

		/**
         * if lesson data is properly retrieved from database and
         * available, bind data to views content block
         **/
		$view->getContent()->setLesson($allLessonModels);
		$view->render();
	}

    /**
     * View individual lesson based on id
     * @param $lessonId : id of lesson to be viewed
     **/
    public function idAction($lessonId)
    {
        /** Lesson id check */
        $this->_idCheck($lessonId, 'lesson');

        /**
         * Retrieves lessson data
         *
         * Loads necessary information for viewing lesson:
         * Duratin, Tags, Description,
         *
         * Admins can see current suggested students
         **/
        $lesson = Bootstrap::getModel('lesson/model')->load($lessonId)->loadView();

        /** Store lesson id in session **/
        $this->_sessionSet('lesson_id', $lessonId);

        /** Load,bind and render **/
        $view = $this->loadLayout();
        $view->getContent()->setLesson($lesson);
        $view->render();
    }
}