<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class Lesson_Controller_Create extends Incubate_Controller_Abstract
{
	public function formAction()
	{
		//check if user is logged in and admin
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();
		//else load view
		$this->loadLayout();
		$this->render();
	}

	public function newAction()
	{
		//check if user is logged in and admin
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();

		//load request objecgt
		$request = $this->_getRequest();

		/*
		 * if form is set, add it to the database
		 * else load a blank form
		 *
		 */
		if ($request->isPost() && $request->hasPost('name')) {

			//load  models
			$lesson = Bootstrap::getModel('incubate/lesson');

			/*
			 * set all post information,
			 * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
			 */
			$tagArray = $this->explode($request->getPost('tags'));

			/*
			 * add the lesson to the database,
			 */
			foreach(array('name','description','duration') as $field) {
				$lesson->setData($field, $request->getPost($field));
			}

			$lessonId = $lesson->save()->getId();

			$event = Bootstrap::getModel('core/event')->setData('lessonId', $lessonId)->setData('tags', $tagArray);

			//add new tags to the database if any, and map tags to t
			Bootstrap::dispatchEvent('lesson_create_after', $event);

			$this->_successFlash('You successfully created a lesson!');

			$this->_thisModuleRedirect('lesson');
		}
	}
}