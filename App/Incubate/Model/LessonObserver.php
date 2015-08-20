<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 1:23 PM
 */
class Incubate_Model_LessonObserver extends Core_Model_Object
{
    public function deleteLessonCompletedCourseMap($event)
    {
        if($lessonId = $event->getData('lessonId'))
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setId($lessonId)->deleteLessonCompletedCourseMap();
        }

    }
}