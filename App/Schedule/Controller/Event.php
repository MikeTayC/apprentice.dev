<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/27/15
 * Time: 8:23 AM
 */

class Schedule_Controller_Event extends Incubate_Controller_Admin
{
    public function fireAction()
    {
        $request = $this->_getRequest();

        if($request->isPost()) {

            $lessonId = $this->_sessionGet('lessonId');

            $event  = Bootstrap::getModel('schedule/calendar');

            $event->setLessonId($lessonId)->loadLesson($lessonId);

            foreach(array('tags','description','student_list', 'start_time','date','teacher') as $field) {
                $event->getLesson()->setData($field, $request->getPost($field));
            }

            $event->loadEvent()->fireEvent()->afterEvent();

            $this->_sessionDelete('lessonId');
            $this->_successFlash('Your event has been scheduled');
            $this->headerRedirect('incubate','calendar','index');
        }
    }
}