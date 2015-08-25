<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class Lesson_Controller_Create extends Incubate_Controller_Admin
{
	public function saveAction()
	{
        $request = $this->_getRequest();

        if ($request->isPost() && $request->hasPost('name')) {

            //load  model
            $lesson = Bootstrap::getModel('lesson/lesson');

            /*
             * set all post information,
             * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
             * and set data on the lesson model entity
             */
            $tagArray = $this->explode($request->getPost('tags'));
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            //will add new lesson to database, and dispatched events will add any new lessons to the database, and attach those tags to the lesson
            $lesson->setTags($tagArray)->save();


            $this->_successFlash('You successfully created a lesson!');
            $this->_thisModuleRedirect('view');
        }
	}
}