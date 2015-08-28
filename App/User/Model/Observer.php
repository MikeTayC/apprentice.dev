<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 3:02 PM
 *
 * User observer listens for events regarding user inforation
 * acts accordingly
 **/
class User_Model_Observer
{
    /**
     * Gets a list of user names for a list of suggested students
     * Used when admin is looking at a specific lesson
     * @param $eventObject
     **/
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

    /**
     * Delete a users completed course mapping for a specific lesson
     * Used when marking a user course as incomplete
     *
     * @param $eventObject
     **/
    public function deleteUserCompletedCourseMap($eventObject)
    {
        $userId = $eventObject->getUser();
        $lessonId = $eventObject->getLesson();
        if($userId && $lessonId)
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setLesson($lessonId)->deleteUserCompletedCourseMap();
        }
    }

    /**
     * Deletes an entire completed course map for a user
     *
     * Used when deleting a suser
     *
     * @param $eventObject
     **/
	public function deleteAllUserCompletedCourseMap($eventObject)
	{
		$userId = $eventObject->getId();

		if($userId) {
			Bootstrap::getModel('incubate/completedCourseMap')->setId($userId)->deleteAllUserCompletedCourseMap();
		}
	}

    /**
     * Used to mark course as completed for a specific user,
     * adds user id, lesson id and date completed
     * @param $eventObject
     **/
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

    /**
     * Marks a course to be completed for a list of students in an invite list
     * Used specifically when scheduling events,
     *
     * @param $eventObject
     **/
    public function setCompletedCourseDateForAllStudentsInList($eventObject)
    {
        $lesson = $eventObject->getLesson();
        $studentList = explode(',', $lesson->getData('student_list'));
        $lessonId = $lesson->getId();
        $dateTime = $lesson->getEndDateTime();

        foreach($studentList as  $studentName) {
            $userId = Bootstrap::getModel('user/model')->loadByName($studentName)->getId();
            Bootstrap::getModel('incubate/completedCourseMap')->setUser($userId)->setlesson($lessonId)->setDate($dateTime)->markUserCompletedCourseMap();
        }
    }

    /**
     * Sets an array of suggested students on lesson event object,
     * Checks if a user tagged for a course has already completed the course
     * @param $eventObject
     **/
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

    /**
     * Sets a array of emails on the event object,
     *
     * Used specifically when firing an event to google calendar,
     * as an attendee array
     *
     * @param $eventObject
     **/
    public function setEventEmail($eventObject)
    {
        $lesson = $eventObject->getLesson();

        $studentList = $lesson->getData('student_list');
        $teacher = $lesson->getTeacher();
        $studentNameArray = explode(',', $studentList);

        foreach ($studentNameArray as $student) {
            $emailArray[] = Bootstrap::getModel('user/model')->loadByName($student)->getEmail();
        }
        if(!empty($teacher)) {
            $emailArray[] = Bootstrap::getModel('user/model')->loadByName($teacher)->getEmail();
        }

        $lesson->setEmailArray($emailArray);
    }

    /**
     * Sets a list of a users completed course ids, a list of courses set to be
     * compelted and their completion date on event object
     *
     * @param $eventObject
     **/
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

    /**
     * Sets user progress on the event object
     * Calculates based on total lesson count, and a users completed course count
     *
     * @param $eventObject
     **/
    public function setUserProgress($eventObject)
    {
        $userId = $eventObject->getId();
        $totalLessonCount = $eventObject->getTotalLessonCount();
        $completedCourseCount = Bootstrap::getModel('incubate/completedCourseMap')->getCompletedCourseCount($userId);
        $userProgress = $this->_getUserProgress($totalLessonCount, $completedCourseCount);

        $eventObject->setProgress($userProgress);
    }

    /**
     * Sets a users incubation time
     *
     * @param $eventObject
     **/
    public function setUserIncubationTime($eventObject)
    {
        $eventObject->setUserIncubationTime();
    }

    /**
     * Calculates a users progress as a percentage
     *
     * @param $totalCourseCount
     * @param $completedCourseCount
     * @return float|int
     */
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