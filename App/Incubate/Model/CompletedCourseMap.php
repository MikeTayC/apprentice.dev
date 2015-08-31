<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10
 *
 * Class Incubate_Model_CompletedCourseMap extends the Core model abstract class,
 *
 * Interacts with the CompletedCoursesMap table in database,
 * Keeps track and handles information regarding a users completed courses
 **/
class Incubate_Model_CompletedCourseMap extends Core_Model_Abstract
{
    /**
     * Sets the table to be intracted with
     * parent::__construct() will instantantiate a sigletong database connection
     **/
    public function __construct()
    {
        $this->_table = 'completed_courses_map';
        parent::__construct();
    }

    /**
     * Function will be called if a lesson is deleted, will delete all records of user
     * completion
     *
     * _data]'id'] must be set to lesson id
     *
     * @return $this
     **/
    public function deleteLessonCompletedCourseMap()
    {
        $this->_db->delete($this->_table, array('lesson_id', '=', $this->getId()));
        return $this;
    }

    /**
     * Loads a specific row information in table,
     * based on user id and lesson id
     *
     * @param $userId
     * @param $lessonId
     * @return $this
     */
    public function loadCompletedCourseRow($userId, $lessonId)
    {
        $this->getMultiArguments(array('user_id', '=', $userId), array('lesson_id', '=', $lessonId));
        return $this;
    }

    /**
     * Deletes a specfic row based on user and lesson ids
     * Used to mark a course incomplete for a user
     *
     * _data['user'] must be set to user id
     * _data['lesson'] must be set to lesson id
     **/
    public function deleteUserCompletedCourseMap()
    {
        $userId = $this->getUser();
        $lessonId = $this->getLesson();
        $mapModel = $this->loadCompletedCourseRow($userId, $lessonId);
        $mapModel->delete();
    }


    /**
     * Sets data for a specific user and lesson,
     * Used to mark a course completed for a user
     *
     * _data['user'] must be set to user id
     * _data['lesson'] must be set to lesson id
     * _data['date'] must be set to date to be completed
     **/
    public function markUserCompletedCourseMap()
    {
        $userId = $this->getUser();
        $lessonId = $this->getLesson();
        $datetime = $this->getDate();
        $mapModel = $this->loadCompletedCourseRow($userId,$lessonId);
        $mapModel->setData('user_id', $userId)->setData('lesson_id', $lessonId)->setDate($datetime)->saveNoLoad();

    }


    /**
     *
     * Used to check if a user has completed a course
     *
     * @param $userId
     * @param $lessonId
     * @return bool
     **/
    public function completedCheck($userId, $lessonId)
    {
        $rowData = $this->loadCompletedCourseRow($userId, $lessonId);
        if($rowData->_data) {
            return true;
        }
        return false;
    }

    /**
     *
     * Deletes all record of user from table,
     * Called when user is deleted
     *
     * _data['id'] must be set to userId
     * @return $this
     *
     **/
	public function deleteAllUserCompletedCourseMap()
	{
		$this->deleteAll(array('user_id', '=', $this->getId()));
		return $this;
	}

    /**
     * Gets the number of courses a user has completed a course by
     * the current date time,
     *
     * if a user has no courses completed: 0 will be returned
     *
     * @param $userId
     * @return int|null
     **/
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