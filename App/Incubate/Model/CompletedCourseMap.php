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
        $this->_table = 'completed_courses';
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

        $mapModel = $this->loadCompletedCourseRow($userId,$lessonId);
        $mapModel->setData('user_id', $userId)->setData('lesson_id', $lessonId)->saveNoLoad();

    }

    public function loadCompletedCourseRow($userId, $lessonId)
    {
        $this->getMultiArguments(array('user_id', '=', $userId), array('lesson_id', '=', $lessonId));
        return $this;
    }
}