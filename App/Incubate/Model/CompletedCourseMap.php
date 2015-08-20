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
       $this->deleteMultiArguments(array('user_id', '=', $this->getUser()), array('lesson_id', '=', $this->getLesson()));
    }

    public function markUserCompletedCourseMap()
    {
        if(!$this->_db->getMultiArgument($this->_table, array('user_id', '=', $this->getUser()), array('lesson_id', '=', $this->getLesson()))->count()) {
            $this->create(array('user_id' => $this->getUser(), 'lesson_id' => $this->getLesson()));
        }
    }
}