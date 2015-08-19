<?php

class Incubate_Controller_Create extends Incubate_Controller_Abstract
{


    public function lessonAction()
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
        if ($request->isPost()) {

            //load  models
            $tag = Bootstrap::getModel('incubate/tag');
            $lesson = Bootstrap::getModel('incubate/lesson');

            /*
             * set all post information,
             * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
             */
            $tagArray = explode(',', $request->getPost('tags'));

            /*
             * adds any newly entered tags into database,
             * takes an array of $tags as argument
             */
            $tag->addNewTagsToDb($tagArray);
            /*
             * add the lesson to the database,
             */
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            $lesson->save();

            /*
             * load the new lesson by name
             */
            $lessonName = $request->getPost('name');

            $newLessonId = $lesson->loadByName($lessonName)->getId();

            foreach ($tagArray as $tags) {
                $tagId = $tag->loadByName($tags)->getId();
                $lesson->createTagMap($newLessonId, $tagId);
            }

            $this->headerRedirect('incubate', 'lesson', 'index');
            exit;
        }
        //else load view
        $this->loadLayout();
        $this->render();

    }

    public function tagAction()
    {
        $request = $this->_getRequest();
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();

        /*
         * if post is set, add the created tag to the database
         */
        if($request->isPost()) {

            $tagArray = $this->explode($request->getPost('tags'));

            Bootstrap::getModel('incubate/tag')->addNewTagsToDb($tagArray);

            $this->headerRedirect('incubate', 'tag', 'index');
            exit;
        }

        //load view
        $this->loadLayout();
        $this->render();


    }

}