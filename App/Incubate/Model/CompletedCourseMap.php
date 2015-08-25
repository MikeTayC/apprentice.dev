<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:32 AM
 */

class Incubate_Model_CompletedCourseMap extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'CompletedCoursesMap';
        parent::__construct();
    }

    public function deleteLessonCompletedCourseMap()
    {
        $this->_db->delete($this->_table, array('lesson_id', '=', $this->getId()));
        return $this;
    }

    public function deleteUserCompletedCourseMap()
    {
        $userId = $this->getUser();
        $lessonId = $this->getLesson();
        $mapModel = $this->loadCompletedCourseRow($userId, $lessonId);
        $mapModel->delete();
    }

    public function markUserCompletedCourseMap()
    {
        $userId = $this->getUser();
        $lessonId = $this->getLesson();
        $datetime = $this->getDate();
        $mapModel = $this->loadCompletedCourseRow($userId,$lessonId);
        $mapModel->setData('user_id', $userId)->setData('lesson_id', $lessonId)->setDate($datetime)->saveNoLoad();

    }

    public function loadCompletedCourseRow($userId, $lessonId)
    {
        $this->getMultiArguments(array('user_id', '=', $userId), array('lesson_id', '=', $lessonId));
        return $this;
    }

    public function completedCheck($userId, $lessonId)
    {
        $rowData = $this->loadCompletedCourseRow($userId, $lessonId);
        if($rowData->_data) {
            return true;
        }
        return false;
    }
	public function deleteAllUserCompletedCourseMap()
	{
		$this->deleteAll(array('user_id', '=', $this->getId()));
		return $this;
	}

    public function getCompletedCourseCount($userId)
    {
        $coursesToCount = array();
        if($countData = $this->getAllBasedOnGivenFields(array('user_id', '=', $userId))) {
            foreach($countData as $data) {
                $currentTime = new DateTime();
                if ($currentTime >= new DateTime($data['date'])) {
                    $coursesToCount[] = $data;
                }
            }
            return count($coursesToCount);
        }
        return null;
    }
}