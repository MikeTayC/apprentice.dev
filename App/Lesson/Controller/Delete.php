<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 *
 * Controller handles deleting of a lesson,
 *
 * extends admin abstract class to ensure logged in admins only
 */
class Lesson_Controller_Delete extends Incubate_Controller_Admin
{
    /**
     * Deletes a lesson
     *
     * @param $lessonId: id of lesson to be deleted
     **/
	public function idAction($lessonId)
    {
        if (isset($lessonId)) {
            /** checks if lesson is in database **/
            $this->_idCheck($lessonId, 'lesson');


            /** Dispatches events to delete current tag map of lesson, then deletes the lessson **/
            Bootstrap::getModel('lesson/model')->load($lessonId)->delete();

            /** flashes message and redirects **/
            $this->_successFlash('Successfully deleted');
            $this->_thisModuleRedirect('view');
        }
    }
}