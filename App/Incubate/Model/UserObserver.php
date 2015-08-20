<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 3:02 PM
 */
class Incubate_Model_userObserver extends Core_Model_Object
{
    public function deleteUserCompletedCourseMap($eventObject)
    {
        $userId = $eventObject->getUser();
        $lessonId = $eventObject->getLesson();
        if($userId && $lessonId)
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setLesson($lessonId)->deleteUserCompletedCourseMap();
        }
    }

    public function markUserCompletedCourseMap($eventObject)
    {
        $userId = $eventObject->getUser();
        $lessonId = $eventObject->getLesson();
        if($userId && $lessonId)
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setlesson($lessonId)->markUserCompletedCourseMap();
        }
    }
}