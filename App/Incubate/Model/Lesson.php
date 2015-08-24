<?php

class Incubate_Model_Lesson extends Core_Model_Abstract
{
	protected $_lessonId;

	public function __construct()
	{
		$this->_table = 'lesson';

		parent::__construct();

    }

	public function getTagLessonMap()
	{
		if($map = $this->_db->getAll('TagMap')->results()) {
			return $map;
		}
		return null;
	}

	public function getTagLessonMapForLesson()
	{
		if($lessonTags = $this->_db->get('TagMap', array('lesson_id', '=', $this->getId()))->results()){
			return $lessonTags;
		}
		return null;
	}

    public function getTagMapAsArrayOfIdFromLessonId($lessonId)
    {
        if($lessonTagMap = $this->getTagLessonMapFromLessonId($lessonId)) {
            foreach ($lessonTagMap as $mapValue) {
                $mapTagIdArray[] = $mapValue->tag_id;
            }
            return $mapTagIdArray;
        }
        return null;
    }

    public function deleteTagMapOfLesson()
    {
        $this->_db->delete('TagMap',array('lesson_id', '=', $this->getId()));
        return $this;
    }

    public function loadEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_set', $this);
    }

    public function fireEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_fire', $this);
    }

    public function afterEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_after', $this);
    }

    protected function _beforeSave()
    {
        Bootstrap::dispatchEvent('lesson_create_before', $this);
    }

    protected function _afterSave()
    {
        Bootstrap::dispatchEvent('lesson_create_after', $this);
    }

    protected function _beforeUpdate()
    {
        Bootstrap::dispatchEvent('lesson_edit_before', $this);
    }

    protected function _beforeDelete()
    {
        Bootstrap::dispatchEvent('lesson_delete_after', $this);
    }


    protected function _afterLoad()
    {
        Bootstrap::dispatchEvent('lesson_load_after', $this);
    }
}