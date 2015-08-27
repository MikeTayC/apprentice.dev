<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 *
 * Controller is responsible for saving edited lessons
 *
 * extnds admin class to ensure logged in admins only
 **/
class Lesson_Controller_Edit extends Incubate_Controller_Admin
{
    /** Handles post data and calls models necessary to update lesson info **/
	public function saveAction()
	{
        /** instance of request object **/
        $request = $this->_getRequest();

        /** Checks for post data **/
        if ($request->isPost()) {

            /**
             * Get lesson Id from session
             * load the lesson with lesson id
             * Get tags from post data
             **/
            $lessonId = $this->_sessionGet('lesson_id');
            $lesson = Bootstrap::getModel('lesson/model')->load($lessonId);
            $lessonTags = $request->getPost('tags');

            /**
             * Explodes comma separted list of tags into an array
             * Set tag array on lesson model
             * set post data on lesson model entity
             **/
            $tagArray = $this->explode($lessonTags);
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            /** Dispatches tag array to be attached to lesson, and saves new data lesson in db **/
            $lesson->setTags($tagArray)->save()->saveTags();

            /**
             * Delete lesson id froom session,
             * flash message and redirect
             **/
            $this->_sessionDelete('lesson_id');
            $this->_successFlash('Successfully updated');
        } else {
            $this->_dangerFlash('Something went wrong!');
        }
        $this->_thisModuleRedirect('view');
	}

}