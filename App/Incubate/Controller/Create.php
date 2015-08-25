<?php

class Incubate_Controller_Create extends Incubate_Controller_Abstract
{
    public function __construct()
    {
        //check if user is logged in and admin
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
    }

    public function lessonAction()
    {

        //load request object
        $request = $this->_getRequest();

        /*
         * if form is set, add it to the database
         * else load a blank form
         *
         */
        if ($request->isPost() && $request->hasPost('name')) {

            //load  model
            $lesson = Bootstrap::getModel('incubate/lesson');

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
            $this->_thisModuleRedirect('lesson');
        }
        //else load view
        $this->loadLayout();
        $this->render();

    }

    public function tagAction()
    {
        $request = $this->_getRequest();

        /*
         * if post is set, add the created tag to the database
         */
        if($request->isPost() && $request->hasPost('tags')) {

            $tagArray = $this->explode($request->getPost('tags'));

            Bootstrap::getModel('incubate/tag')->addNewTagsToDb($tagArray);

            $this->_successFlash('You made tag(s)');
            $this->_thisModuleRedirect('tag');
        }

        //load view
        $this->loadLayout();
        $this->render();


    }

}