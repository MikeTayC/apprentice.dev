<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 10:45 AM
 *
 * Controller is respsonsible for setting of all lesson forms
 * includes forms for creating, editing and scheduling events
 *
 * Must be logged in admin for access
 */
class Lesson_Controller_Form extends Incubate_Controller_Admin
{
    /** Handles new lesson form, doesnt need any data to populate **/
    public function createAction()
    {
        $this->loadLayout();
        $this->_flashCheck();
        $this->render();
    }

    /**
     * Handles lesson edit form,
     *
     * needs to be populated with current name, tags, duration, description
     *
     * @param $lessonId
     */
    public function editAction($lessonId)
    {
        $view = $this->loadLayout();

        $this->_flashCheck();

        $this->_idCheck($lessonId, 'lesson');

        /** loads the lesson based on id, dispatched events will set the tag name array on the lesson object **/
        $lesson = Bootstrap::getModel('lesson/model')->load($lessonId);

        /** store lesson id in the session **/
        $this->_sessionSet('lessonId', $lessonId);

        /** load view, set data for use in edit form, and render **/
        $view->getContent()->setLesson($lesson);
        $view->render();
    }

    /**
     * Sets data for event schedule form,
     * @param $lessonId
     */
    public function eventAction($lessonId)
    {
        $view = $this->loadLayout();
        $this->_flashCheck();
        /** lesson id check **/
        $this->_idCheck($lessonId, 'lesson');

        /**
         * @var Incubate_Model_Lesson $lesson loads lesson, dispatched
         * events will add all necessary information,
         *
         * including tags, and suggested students
         **/
        $lesson = Bootstrap::getModel('lesson/model')->load($lessonId)->loadSuggestedStudents();

        /**  sets lesson id in session**/
        $this->_sessionSet('lessonId', $lessonId);

        /** loads view layout, binds lesson data and renders */
        $view->getContent()->setLesson($lesson);
        $view->render();
    }
}
