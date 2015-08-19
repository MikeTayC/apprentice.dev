<?php

class Incubate_Controller_Lesson extends Core_Controller_Abstract
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
        if(!Core_Model_Session::get('logged_in')){
           $this->redirect('Incubate', 'Login', 'indexAction');
        }

        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');
        //load view
        $view = $this->loadLayout();

        //load model
        $lessonData = Bootstrap::getModel('incubate/lesson')->getAll();


        //if lesson data is properly retrieved from database and available, bind data to views content block
        if($lessonData) {
            $view->getContent()->setLesson($lessonData);
        }

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
                $lessonTagsArray = explode(',', $lessonTags);

                //Add any new tags into database
                $tag->AddNewTagsToDb($lessonTagsArray);

                //create a tag map for the newly edited lesson
                foreach ($lessonTagsArray as $tags) {
                    $tagId = $tag->loadByName($tags)->getId;
                    $lesson->createTagMap($lessonId, $tagId);
                }
            }

            /*
             * load the lesson data to be updated based on the lesson id
             * call update funciton
             * save the updates
             */
             $lesson->loadLesson($lessonId)
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
            $lessonTags = array();
            if($lessonTagMap) {
                foreach ($lessonTagMap as $mapValue) {

                    //loads tag based on mapvalue tag id and returns the name of the tag and puts in array for use
                    $tagName = $tag->load($mapValue['tag_id'])->getName();
                    $lessonTags[] = $tagName;
                }
            }

            //store lesson id in the session
            Core_Model_Session::set('lesson_id', $lessonId);

            //set data for use in edit form
            $view->getContent()
                 ->setName($lesson->getName())
                 ->setDescription($lesson->getDescription())
                 ->setDuration($lesson->getDuration())
                 ->setTags($lessonTags);
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

            $tag = Bootstrap::getModel('incubate/tag');
            $lesson = Bootstrap::getModel('incubate/lesson');

            if($lesson->load($lessonId)) {

                //delete current tag map of lesson
                $tag->deleteTagMapOfLesson($lessonId);

                //delete completed lesson map specific
                $lesson->deleteCompletedCourseMap($lessonId);

                //delete this lesson
                $lesson->delete();

                Core_Model_Session::successFlash('message', 'Successfully deleted');
            }
        }
        $this->headerRedirect('incubate','lesson','index');
        exit;
    }

}