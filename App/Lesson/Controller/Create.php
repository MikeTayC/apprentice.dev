<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 *
 * Controller is responsible for parsing post data, creating and saving lessons,
 *
 * extends admin controller to ensure logged in admins only can access
 **/
class Lesson_Controller_Create extends Incubate_Controller_Admin
{
    /**
     * Action which handles post data and sets lesson model up
     * with information before binding to view layout
     **/
	public function saveAction()
	{
        /** Gets an singleton instance of request object **/
        $request = $this->_getRequest();

        /**
         * Request object checks if post is set and if specific field ['name']
         * is set
         *
         * if false, flash error, and send back to calendar index
         **/
        if ($request->isPost() && $request->hasPost('name')) {

            /** load model **/
            $lesson = Bootstrap::getModel('lesson/model');

            /** lesson check name if name is in database, if so redirect **/
            if($lesson->checkByName($request->getPost('name'))) {
                $this->_dangerFlash('This lesson already exists!');
                $this->_thisModuleRedirect('view');
            }

            /**
             * set all post information,
             * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
             * and set data on the lesson model entity
             **/
            $tagArray = $this->explode($request->getPost('tags'));
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            /**
             * will add new lesson to database, and dispatched events will
             * add any new lessons to the database, and attach those tags to
             * the lesson
             **/
            $lesson->setTags($tagArray)->save()->saveTags();



            /** flash success and redirect **/
            $this->_successFlash('You successfully created a lesson!');
        }
        else {
            $this->_dangerFlash('You must have done something wrong!');
        }
        $this->_thisModuleRedirect('view');
	}
}