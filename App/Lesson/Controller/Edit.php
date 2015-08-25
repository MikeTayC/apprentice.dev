<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Lesson_Controller_Edit extends Incubate_Controller_Admin
{
	public function idAction()
	{
		$request = $this->_getRequest();
		$lesson = Bootstrap::getModel('incubate/lesson');
		if ($request->isPost()) {

			//set post and session data
			$lessonId = $this->_sessionGet('lesson_id');
			$lessonName = $request->getPost('name');
			$lessonDescription = $request->getPost('description');
			$lessonDuration = $request->getPost('duration');
			$lessonTags = $request->getPost('tags');

			//prepare tags
			$lessonTagsArray = $this->explode($lessonTags);

			//delete current tag map of lesson, easier to just create a new one
			$event = Bootstrap::getModel('core/event')->setData('lessonId', $lessonId)->setTags($lessonTagsArray);

			//dispatch event that will delete current lessons tags
			Bootstrap::dispatchEvent('lesson_edit_before', $event);

			//set new updates
			$lesson->load($lessonId)->setName($lessonName)->setDescription($lessonDescription)->setDuration($lessonDuration)->save();

			//dispatch events that will create a lesson to tag map
			Bootstrap::dispatchEvent('lesson_create_after', $event);


			/*
			 * load the lesson data to be updated based on the lesson id
			 * call update funciton
			 * save the updates
			 */

			$this->_sessionDelete('lesson_id');
			$this->_successFlash('Successfully updated');
			$this->_thisModuleRedirect('lesson');
		}
	}

}