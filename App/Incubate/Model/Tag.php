<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/16/15
 * Time: 10:46 PM
 */
class Incubate_Model_Tag extends Core_Model_Abstract
{
	public function __construct()
	{
		$this->_table = 'tag';

		parent::__construct();
	}

    public function loadTag($tagId)
    {
        $this->_data = $this->get(array('tag_id', '=', $tagId));
        return $this;
    }

    public function changeTagName($newTagName)
    {
        if($this->_data) {
            $this->_data->name = $newTagName;
        }
        return $this;
    }

    public function saveUpdate()
    {
        if($this->_data){
            $tagId = $this->_data->tag_id;
            $tagName = $this->_data->name;

            $tagDataCheck = $this->get(array('tag_id','=', $tagId));
            if($tagDataCheck->name != $tagName) {
                $updateArray['name'] = $tagName;
            }

            if($updateArray) {
                foreach($updateArray as $updateKey => $updateValue) {
                    $this->updateBasedOnTagId(array(
                        $updateKey  => $updateValue
                    ));
                }
            }
        }
    }

    public function updateBasedOnTagId($fields = array())
    {
        $this->update($this->_data->tag_id, 'tag_id', $fields);
    }

	public function getTagsFromTagTableByTagId($tagId)
	{
		if($tag = $this->getAllBasedOnGivenFields(array('tag_id', '=', $tagId))) {
			return $tag->name;
		}
		return null;
	}
    public function getTagNameByTagId($tagId) {
        if($tag = $this->get(array('tag_id', '=', $tagId))) {
            return $tag->name;
        }
        return null;
    }

	/*
 * searches for new tags to be added to the array,
 * if it is not an rray
 */
	public function AddNewTagsToDb($tagArray)
	{
		$dbTags = $this->getAllNamesAsArray();
		if(is_array($tagArray)) {
			foreach ($tagArray as $tag) {
				if (!in_array($tag, $dbTags)) {
					$this->create(array(
						'name' => $tag
					));
				}
			}
		}
	}

    public function deleteTagMapOfLesson($lessonId)
    {
        $this->_db->delete('lesson_tag_map',array('lesson_id', '=', $lessonId));
    }

    public function deleteTagMapOfLessonBasedOnTagId($tagId)
    {
        $this->_db->delete('lesson_tag_map',array('tag_id', '=', $tagId));
    }

    public function deleteThisTag($tagId)
    {
        $this->_db->delete($this->_table, array('tag_id', '=', $tagId));
    }

    public function getTagLessonMapFromTagId($tagId)
    {
        if($lessonTags = $this->_db->get('lesson_tag_map', array('tag_id', '=', $tagId))->results()){
            return $lessonTags;
        }
        return null;
    }
}