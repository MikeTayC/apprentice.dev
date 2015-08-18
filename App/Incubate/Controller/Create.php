<?php

class Incubate_Controller_Create extends Core_Controller_Abstract
{
    public function indexAction($param)
    {
        if(!Core_Model_Session::get('logged_in') || !Core_Model_Session::get('admin_status')) {
            $this->headerRedirect('Incubate', 'Login', 'index');
            exit;
        }

        if($param) {
            $param = strtolower($param);

            $this->redirect('Incubate', 'Create', $param . 'Action');
        }
        else {
            $this->redirect('Incubate','Lesson', 'Index');
        }
    }

    public function lessonAction()
    {
        //load view
        $view = $this->loadLayout();

        //load user model
        $user = Bootstrap::getModel('incubate/user');
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
            $lesson->create(array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration']
            ));

            $newLesson = $lesson->get(array('name', '=', $_POST['name']));

            foreach ($tagArray as $tags) {
                $tagInfo = $tag->get(array('name', '=', $tags));
                $lesson->createTagMap($newLesson->lesson_id, $tagInfo->tag_id);
            }

            $this->redirect('Incubate', 'Lesson', 'indexAction');
        }
        else {
            $view->render();
        }
    }

    public function tagAction()
    {
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