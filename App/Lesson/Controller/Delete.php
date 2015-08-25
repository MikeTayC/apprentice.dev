<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Lesson_Controller_Delete extends Incubate_Controller_Admin
{
	public function deleteAction($lessonId)
	{
		$this->_idCheck($lessonId, 'lesson');

		//delete current tag map of lesson, then delte the lessson
		$lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

		$lesson->delete();

		$event = Bootstrap::getModel('core/event')->setData('lessonId', $lesson->getId());
		Bootstrap::dispatchEvent('delete_lesson_after', $event);

		$this->_successFlash('Successfully deleted');

		$this->_thisModuleRedirect('lesson');
	}

}