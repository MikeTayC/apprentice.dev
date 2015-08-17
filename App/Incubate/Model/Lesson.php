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

	public function getTagLessonMapFromLessonId()
	{
		//TODO set lesson id somewhere
		if($lessonTags = $this->_db->getAll('lesson_tag_map', array('lesson_id', '=', $this->_lesson_id))){
			return $lessonTags;
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
}