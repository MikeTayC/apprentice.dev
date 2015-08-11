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
            $user->AddNewTagsToDb($tagArray);

            /*
             * add the lesson to the database,
             * TODO FORM VALIDATION
             */
            $user->create('lesson', array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration']
            ));

            $newLesson = $user->get('lesson', array('name', '=', $_POST['name']));

            foreach ($tagArray as $tag) {
                $tagInfo = $user->get('tag', array('value', '=', $tag));
                $user->create('lesson_tag_map', array(
                    'lesson_id' => $newLesson->lesson_id,
                    'tag_id' => $tagInfo->id
                ));
            }

            $this->redirect('Incubate', 'Lesson', 'indexAction');
        }
        else {
            $view->render();
        }
    }

    public function tagAction()
    {

        //load view
        $view = $this->loadLayout();

        //load user model
        $user = Bootstrap::getModel('incubate/user');

        /*
         * if post is set, add theh created tag to the database
         *
         * TODO FORM VALIDATION
         */
        if(!empty($_POST)) {
            $user->AddNewTagstoDb($_POST['tags']);
        }

        $this->redirect('Incubate', 'Lesson', 'indexAction');
    }

}