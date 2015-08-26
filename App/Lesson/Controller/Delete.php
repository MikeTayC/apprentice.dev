<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Lesson_Controller_Delete extends Incubate_Controller_Admin
{
	public function idAction($lessonId)
	{
        {
            $this->_idCheck($lessonId, 'lesson');

            //delete current tag map of lesson, then delte the lessson
            Bootstrap::getModel('lesson/model')->load($lessonId)->delete();

            $this->_successFlash('Successfully deleted');

            $this->_thisModuleRedirect('view');
        }
	}

}