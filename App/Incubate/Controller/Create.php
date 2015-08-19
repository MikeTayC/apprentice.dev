<?php

class Incubate_Controller_Create extends Incubate_Controller_Abstract
{


    public function lessonAction()
    {
        //check if user is logged in and admin
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        //load request objecgt
        $request = $this->_getRequest();


        //load view
        $view = $this->loadLayout();

        //load  models
        $tag = Bootstrap::getModel('incubate/tag');
        $lesson = Bootstrap::getModel('incubate/lesson');

        /*
         * if form is set, add it to the database
         * else load a blank form
         *
         * TODO FORM VALIDATION
         */
        if ($request->isPost()) {

            /*
             * set all post information,
             *
             * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
             */
            $tagArray = explode(',', $request->getPost('tags'));

            /*
             * adds any newly entered tags into database,
             *
             * takes an array of $tags as argument
             */
            $tag->addNewTagsToDb($tagArray);
            /*
             * add the lesson to the database,
             * TODO FORM VALIDATION
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
        else {
            $view->render();
        }
    }

    public function tagAction()
    {
        $request = $this->_getRequest();
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        //load user mode
        $tag = Bootstrap::getModel('incubate/tag');

        /*
         * if post is set, add the created tag to the database
         *
         * TODO FORM VALIDATION
         */
        if($request->isPost()) {

            $tagArray = explode(',', $request->getPost('tags'));

            $tag->addNewTagsToDb($tagArray);

            $this->headerRedirect('incubate', 'tag', 'index');
        }
        else {
            //load view
            $this->loadLayout();
            $this->render();
        }

    }

}