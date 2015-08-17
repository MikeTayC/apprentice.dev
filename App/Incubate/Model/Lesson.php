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

    public function loadLesson($lessonId)
    {
        $this->_data = $this->get(array('lesson_id', '=', $lessonId));
    }

    public function updateLessonName($lessonName)
    {
        if($this->_data) {
            $this->_data->name = $lessonName;
        }
    }

    public function updateLessonDescription($lessonDescription)
    {
        if($this->_data) {
            $this->_data->description = $lessonDescription;
        }
    }

    public function updateLessonDuration($lessonDuration)
    {
        if($this->_data) {
            $this->_data->duration = $lessonDuration;
        }
    }

    public function saveUpdate()
    {
        if($this->_data->lesson_id) {
            $lessonId = $this->_data->lesson_id;
            $lessonName = $this->_data->name;
            $lessonDescription = $this->_data->description;
            $lessonDuration = $this->_data->duration;

            $lessonDataCheck = $this->get(array('lesson_id','=', $lessonId));
            if($lessonDataCheck->name != $lessonName) {
                $updateArray['name'] = $lessonName;
            }
            if($lessonDataCheck->description  != $lessonDescription) {
                $updateArray['description'] = $lessonDescription;
            }
            if($lessonDataCheck->duration  != $lessonDuration) {
                $updateArray['duration'] = $lessonDuration;
            }

            if($updateArray) {
                foreach($updateArray as $updateKey => $updateValue) {
                    $this->updateBasedOnLessonId(array(
                        $updateKey  => $updateValue
                    ));
                }
            }
        }
    }

    public function updateBasedOnLessonId($fields = array())
    {
        $this->update($this->_data->lesson_id, 'lesson_id', $fields);
    }

	public function getTagLessonMap()
	{
		if($map = $this->_db->getAll('lesson_tag_map')->results()) {
			return $map;
		}
		return null;
	}

	public function getTagLessonMapFromLessonId($lessonId)
	{
		//TODO set lesson id somewhere
		if($lessonTags = $this->_db->get('lesson_tag_map', array('lesson_id', '=', $lessonId))->results()){
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

    public function deleteThisLesson($lessonId)
    {
        $this->_db->delete($this->_table, array('lesson_id', '=', $lessonId));
    }
}