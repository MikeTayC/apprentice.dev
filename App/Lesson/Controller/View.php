<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class Lesson_Controller_View extends Incubate_Controller_Abstract
{
	public function allAction()
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
}