<?php

class Incubate_Controller_Create extends Incubate_Controller_Abstract
{


    public function lessonAction()
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

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
        if (!empty($_POST)) {

            /*
             * set all post information,
             *
             * if the post variable is tag, it is a comma seprated list, explode it into $tagArray
             */
            $tagArray = explode(',', $_POST['tags']);

            /*
             * adds any newly entered tags into database,
             *
             * takes an array of $tags as argument
             */
            $tag->AddNewTagsToDb($tagArray);
            /*
             * add the lesson to the database,
             * TODO FORM VALIDATION
             */
            foreach(array('name','description','duration') as $field) {
                $lesson->setData($field, $_POST[$field]);
            }

            $lesson->save();

            /*
             * load the new lesson by name
             */
            $lessonName = $_POST['name'];

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
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        //load user mode
        $tag = Bootstrap::getModel('incubate/tag');

        /*
         * if post is set, add the created tag to the database
         *
         * TODO FORM VALIDATION
         */
        if(!empty($_POST)) {

            $tagArray = explode(',', $_POST['tags']);
            $tag->AddNewTagsToDb($tagArray);
            $this->redirect('Incubate', 'Tag', 'indexAction');
        }
        else {
            //load view
            $this->loadLayout();
            $this->render();
        }

    }

}