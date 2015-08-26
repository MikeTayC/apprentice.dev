<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 3:02 PM
 */
class User_Model_Observer
{
    public function setUserNamesForLessonView($eventObject)
    {
        $suggestedStudents = $eventObject->getData('studentInviteList');
        $studentNameList = '';
        $x = 1;
        if($suggestedStudents) {
            foreach($suggestedStudents as $student) {
                $studentNameList .= $student->getName();
               if($x < count($suggestedStudents)) {
                   $studentNameList .= ', ';
               }
                $x++;
            }
        }
        $eventObject->setSuggestedStudentNames($studentNameList);
    }
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
		$userId = $eventObject->getId();

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
        $dateTime = $eventObject->getEndDateTime();

        foreach($studentList as  $studentName) {
            $userId = Bootstrap::getModel('user/model')->loadByName($studentName)->getId();
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
                $studentInviteList[] = Bootstrap::getModel('user/model')->load($id);
            }
        }

        $eventObject->setData('studentInviteList', $studentInviteList);
    }

    public function setEventEmail($eventObject)
    {
        $studentList = $eventObject->getData('student_list');
        $teacher = $eventObject->getTeacher();
        $studentNameArray = explode(',', $studentList);

        foreach ($studentNameArray as $student) {
            $emailArray[] = Bootstrap::getModel('user/model')->loadByName($student)->getEmail();
        }
        if(!empty($teacher)) {
            $emailArray[] = Bootstrap::getModel('user/model')->loadByName($teacher)->getEmail();
        }

        $eventObject->setEmailArray($emailArray);
    }

    public function setUserCompletedCourses($eventObject)
    {
        $userId = $eventObject->getId();
        $userCompletedCourseIdArray = array();
        $userHiatusCourseIdArray = array();
        $hiatusIdToArrayMap = array();
        if($userCompletedCourseIdMap = Bootstrap::getModel('incubate/completedCourseMap')->getAllBasedOnGivenFields(array('user_id', '=', $userId))) {

            foreach($userCompletedCourseIdMap as $mapValue) {

                if (new DateTime() >= new DateTime($mapValue['date']) ) {
                    $userCompletedCourseIdArray[] = $mapValue['lesson_id'];
                } else {
                    $userHiatusCourseIdArray[] = $mapValue['lesson_id'];
                    $hiatusIdToArrayMap[$mapValue['lesson_id']] = $mapValue['date'];
                }
            }
        }
        $eventObject->setHiatus($userHiatusCourseIdArray);
        $eventObject->setData('hiatusToDate', $hiatusIdToArrayMap);
        $eventObject->setCompleted($userCompletedCourseIdArray);

    }

    public function setUserProgress($eventObject)
    {
        $userId = $eventObject->getId();
        $totalLessonCount = $eventObject->getTotalLessonCount();
        $completedCourseCount = Bootstrap::getModel('incubate/completedCourseMap')->getCompletedCourseCount($userId);
        $userProgress = $this->_getUserProgress($totalLessonCount, $completedCourseCount);

        $eventObject->setProgress($userProgress);
    }

    public function setUserIncubationTime($eventObject)
    {
        $eventObject->setUserIncubationTime();
    }

    private function _getUserProgress($totalCourseCount, $completedCourseCount)
    {
        if($totalCourseCount != 0) {
            $progress = round($completedCourseCount / $totalCourseCount * 100);
            return $progress;
        }
        else{
            return 0;
        }
    }
}