<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/27/15
 * Time: 8:23 AM
 *
 * Handles post data, formats information to fire an event with gogle calendar
 *
 * User must be a logged in admin to schedule an event
 **/

class Schedule_Controller_Event extends Incubate_Controller_Admin
{
    public function fireAction()
    {
        /** @var $request handles post data */
        $request = $this->_getRequest();

        /** if post is not set, redirect with error message */
        if($request->isPost()) {

            /** @var  $lessonId retrieves lesson id from session */
            $lessonId = $this->_sessionGet('lessonId');

            /** @var $event instance of schedule_model_calendar **/
            $event  = Bootstrap::getModel('schedule/calendar');

            /**
             * sets lesson id on event model, then calls function which dispatches an event
             * dispatched event will nest an instance of the lesson model in the event model
             **/
            $event->setLessonId($lessonId)->loadLesson($lessonId);

            /**
             * Use request object to set data on the nested lesson model in the event model
             **/
            foreach(array('tags','description','student_list', 'start_time','date','teacher') as $field) {
                $event->getLesson()->setData($field, $request->getPost($field));
            }

            /**
             * Calls function that will dispatch events to prepare, fire the scheduled event
             * information,
             *
             * dispatches will then mark the courses to be completed in the CompltedCoursesMap table
             **/
            $event->loadEvent()->fireEvent()->afterEvent();

            /** Delete session, flash message, redirect */

            $this->_sessionDelete('lessonId');
            $this->_successFlash('Your event has been scheduled');
            $this->headerRedirect('incubate','calendar','index');
        }
        else {
            $this->_dangerFlash('Something went wrong!');
            $this->_thisModuleRedirect('calendar');
        }
    }
}