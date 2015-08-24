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
        $this->_checkIfUserIsLoggedIn();

        //load view
        $view = $this->loadLayout();

        $this->_flashCheck();

        //load model
        $allLessonModels = Bootstrap::getModel('incubate/lesson')->loadAll();


        //if lesson data is properly retrieved from database and available, bind data to views content block

        $view->getContent()->setLesson($allLessonModels);

        $view->render();
    }

    public function editAction($lessonId)
    {
        $request = $this->_getRequest();
        $lesson = Bootstrap::getModel('incubate/lesson');

        if ($request->isPost()) { //@todo: Convert to save action to prevent ambiguity and heavy controller

            //set post and session data
            $lessonId = $this->_sessionGet('lesson_id');
            $lessonName = $request->getPost('name');
            $lessonDescription = $request->getPost('description');
            $lessonDuration = $request->getPost('duration');
            $lessonTags = $request->getPost('tags');

            //prepare tags
            $lessonTagsArray = $this->explode($lessonTags);

            //delete current tag map of lesson, easier to just create a new one
            $event = Bootstrap::getModel('core/event')->setData('lessonId', $lessonId)->setTags($lessonTagsArray);

            //dispatch event that will delete current lessons tags
            Bootstrap::dispatchEvent('lesson_edit_before', $event);

            //set new updates
            $lesson->load($lessonId)->setName($lessonName)->setDescription($lessonDescription)->setDuration($lessonDuration)->save();

            //dispatch events that will create a lesson to tag map
            Bootstrap::dispatchEvent('lesson_create_after', $event);


            /*
             * load the lesson data to be updated based on the lesson id
             * call update funciton
             * save the updates
             */

            $this->_sessionDelete('lesson_id');
            $this->_successFlash('Successfully updated');
            $this->_thisModuleRedirect('lesson');
        }
        elseif($this->_idCheck($lessonId, 'lesson')) { //@todo: Could this be handled by load returning false or 'id-less' lesson model
            $lesson->load($lessonId);

            //retrieves lesson map for a particular lesson
            $lessonTagMap = $lesson->load($lessonId)->getTagLessonMapForLesson();

            //for each tag in the map, get the specific tag names from the tag table
            $lessonTags = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($lessonTagMap);

            //store lesson id in the session
            $this->_sessionSet('lesson_id', $lessonId);

            //load view, set data for use in edit form, and render
            $view = $this->loadLayout();
            $view->getContent()->setLesson($lesson)->setTags($lessonTags);
            $view->render();
        }
        else {
            $this->_dangerFlash('You did not specify a lesson to edit');
            $this->_thisModuleRedirect('index');
        }
    }

    public function deleteAction($lessonId)
    {
        $this->_idCheck($lessonId, 'lesson');

        //delete current tag map of lesson, then delte the lessson
        $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

        $lesson->delete();

        $event = Bootstrap::getModel('core/event')->setData('lessonId', $lesson->getId());
        Bootstrap::dispatchEvent('delete_lesson_after', $event);

        $this->_successFlash('Successfully deleted');

        $this->_thisModuleRedirect('lesson');
    }

}