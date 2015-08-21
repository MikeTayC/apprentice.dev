<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:31 AM
 */
class Incubate_Model_TagMap extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'TagMap';
        parent::__construct();
    }

    public function createTagMap()
    {
        $this->_db->insert($this->_table, array(
            'lesson_id' => $this->getLesson(),
            'tag_id' => $this->getTag()
        ));
    }

    public function deleteLessonTagMap()
    {
        $this->_db->delete($this->_table, array('lesson_id', '=', $this->getId()));
    }

    public function deleteTagMapOfLessonBasedOnTagId()
    {
        $this->_db->delete($this->_table, array('tag_id', '=', $this->getId()));
    }
}