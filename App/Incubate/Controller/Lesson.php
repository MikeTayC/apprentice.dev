<?php

class Incubate_Controller_Lesson extends Incubate_Controller_Abstract
{

    /*
     * indexAction() is responsible for loading all the created lessons
     * Here, admins can create update delete lessons
     * standard users can only read a list of all lessons
     *
     * if session variable : logged_in is false, return to login
     * if session variable : admin_status is true, then load create and edit links
     * if admin_status is false, then just load list of current courses
     */
    public function indexAction()
    {

        //redirect if not logged in
        $this->checkIfUserIsLoggedIn();

        //load view
        $view = $this->loadLayout();

        $this->flashCheck();

        //load model
        $allLessonModels = Bootstrap::getModel('incubate/lesson')->loadAll();


        //if lesson data is properly retrieved from database and available, bind data to views content block

        $view->getContent()->setLesson($allLessonModels);

        $view->render();
    }

    public function editAction($lessonId)
    {
        $view = $this->loadLayout();

        $lesson = Bootstrap::getModel('incubate/lesson');
        $tag = Bootstrap::getModel('incubate/tag');

        if (!empty($_POST)) {
            $lessonId = Core_Model_Session::get('lesson_id');
            $lessonName = $_POST['name'];
            $lessonDescription = $_POST['description'];
            $lessonDuration = $_POST['duration'];
            $lessonTags = $_POST['tags'];

            //delete current tag map of lesson, easier to just create a new one
            $tag->deleteTagMapOfLesson($lessonId);

            //check if lesson tag is empty
            if(!empty($lessonTags)) {

                //if $lessonTags is not empty, explode into an array
                $lessonTagsArray = $this->explode($lessonTags);

                //Add any new tags into database
                $tag->addNewTagsToDb($lessonTagsArray);

                //create a tag map for the newly edited lesson
                foreach ($lessonTagsArray as $tags) {
                    $tagId = $tag->loadByName($tags)->getId();
                    $lesson->createTagMap($lessonId, $tagId);
                }
            }

            /*
             * load the lesson data to be updated based on the lesson id
             * call update funciton
             * save the updates
             */
             $lesson->load($lessonId)
                    ->setName($lessonName)
                    ->setDescription($lessonDescription)
                    ->setDuration($lessonDuration)
                    ->save();

            Core_Model_Session::delete('lesson_id');
            Core_Model_Session::successFlash('message', 'Successfully updated');

            $this->headerRedirect('incubate','lesson','index');
            exit;
        }
        elseif(isset($lessonId)) {

            $lessonTagMap = $lesson->load($lessonId)->getTagLessonMapForLesson();

            //for each tag in the map, get the specific tag names from the tag table
            $lessonTags = $tag->getTagNamesFromTagMap($lessonTagMap);

            //store lesson id in the session
            Core_Model_Session::set('lesson_id', $lessonId);

            //set data for use in edit form
            $view->getContent()->setLesson($lesson)->setTags($lessonTags);

            $view->render();
        }
        else {
            Core_Model_Session::dangerFlash('error', 'You did not specify a lesson to edit');
            $this->headerRedirect('incubate','index','index');
            exit;
        }
    }

    public function deleteAction($lessonId)
    {
        if(!empty($lessonId)){

            //delete current tag map of lesson, then delte the lessson

            Bootstrap::getModel('incubate/lesson')->load($lessonId)->deleteTagMapOfLesson()->deleteCompletedCourseMap()->delete();

            Core_Model_Session::successFlash('message', 'Successfully deleted');
        }
        $this->headerRedirect('incubate','lesson','index');
        exit;
    }

}