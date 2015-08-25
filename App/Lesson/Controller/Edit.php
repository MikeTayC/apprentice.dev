<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Lesson_Controller_Edit extends Incubate_Controller_Admin
{
	public function saveAction()
	{
        $request = $this->_getRequest();

        if ($request->isPost()) {

            //set post and session data
            $lessonId = $this->_sessionGet('lesson_id');
            $lesson = Bootstrap::getModel('lesson/lesson')->load($lessonId);
            $lessonTags = $request->getPost('tags');

            //prepare tags
            $tagArray = $this->explode($lessonTags);

            //set new updates
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            $lesson->setTags($tagArray)->save();

            /*
             * load the lesson data to be updated based on the lesson id
             * call update funciton
             * save the updates
             */

            $this->_sessionDelete('lesson_id');
            $this->_successFlash('Successfully updated');
            $this->_thisModuleRedirect('view');
        }
	}

}