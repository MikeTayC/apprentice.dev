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

	public function deleteAllUserCompletedCourseMap($eventObject)
	{
		$userId = $eventObject->getUser();

		if($userId) {
			Bootstrap::getModel('incubate/completedCourseMap')->setId($userId)->deleteAllUserCompletedCourseMap();
		}
	}

    public function markUserCompletedCourseMap($eventObject)
    {
        $userId = $eventObject->getUser();
        $lessonId = $eventObject->getLesson();
        $dateTime = $eventObject->getDate();
        if($userId && $lessonId)
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setlesson($lessonId)->setDate($dateTime)->markUserCompletedCourseMap();
        }
    }

    public function setCompletedCourseDateForAllStudentsInList($eventObject)
    {
        $studentList = $eventObject->getStudents();
        $lessonId = $eventObject->getLesson();
        $dateTime = $eventObject->getDate();

        foreach($studentList as  $studentName) {
            $userId = Bootstrap::getModel('incubate/user')->loadByName($studentName)->getId();
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setlesson($lessonId)->setDate($dateTime)->markUserCompletedCourseMap();
        }
    }

    public function addNewUserToDatabase($eventObject)
    {
        $user = $eventObject->getUser();
        $user->save();
    }
}