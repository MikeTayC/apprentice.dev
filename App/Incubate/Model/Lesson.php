<?php

class Incubate_Model_Lesson extends Core_Model_Abstract
{
	protected $_lessonId;
	public $AE;
	public $FE;
	public $QA;

	public function __construct()
	{
		$this->_table = 'lesson';
		parent::__construct();

    }


	public function getTagLessonMap()
	{
		if($map = $this->_db->getAll('lesson_tag_map')->results()) {
			return $map;
		}
		return null;
	}


	public function getTagLessonMapForLesson()
	{
		if($lessonTags = $this->_db->get('lesson_tag_map', array('lesson_id', '=', $this->getId()))->results()){
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

	public function createTagMap($lesson_id, $tag_id)
	{
		$this->_db->insert('lesson_tag_map', array(
			'lesson_id' => $lesson_id,
			'tag_id' => $tag_id
		));
	}

	public function checkForGroupTagAndAssign($tagId)
	{
		switch($tagId) {
			case '1' :
				$this->AE = true;
				break;
			case '2':
				$this->QA = true;
				break;
			case '3' :
				$this->FE = true;
				break;
		}
	}

    public function deleteCompletedCourseMap($lessonId)
    {
        $this->_db->delete('completed_courses',array('lesson_id', '=', $lessonId));
    }


}