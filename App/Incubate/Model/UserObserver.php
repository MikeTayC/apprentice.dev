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
        $studentList = explode(',', $eventObject->getData('student_list'));
        $lessonId = $eventObject->getId();
        $dateTime = $eventObject->getData('endDateTime');

        foreach($studentList as  $studentName) {
            $userId = Bootstrap::getModel('incubate/user')->loadByName($studentName)->getId();
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setlesson($lessonId)->setDate($dateTime)->markUserCompletedCourseMap();
        }
    }

    public function setSuggestedStudentNamesOnLesson($eventObject)
    {
        $lessonId = $eventObject->getId();
        $userTagMap = $eventObject->getData('userTagMap');
        $completedCourseMap = Bootstrap::getModel('incubate/completedCourseMap');

        $studentInviteList = array();
        foreach ($userTagMap as $id) {
            if (!$completedCourseMap->completedCheck($id, $lessonId)) {
                $studentInviteList[] = Bootstrap::getModel('incubate/user')->load($id);
            }
        }

        $eventObject->setData('studentInviteList', $studentInviteList);
    }

    public function setEventStudentEmail($eventObject)
    {
        $studentList = $eventObject->getData('student_list');
        $studentNameArray = explode(',', $studentList);
        foreach ($studentNameArray as $student) {
            $studentEmailArray[] = Bootstrap::getModel('incubate/user')->loadByName($student)->getEmail();
        }

        $eventObject->setData('studentEmailArray', $studentEmailArray);
    }
}