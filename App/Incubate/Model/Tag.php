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

    public function updateBasedOnTagId($fields = array())
    {
        $this->update($this->_data->id, 'id', $fields);
    }

	public function getTagsFromTagTableByTagId($tagId)
	{
		if($tag = $this->getAllBasedOnGivenFields(array('id', '=', $tagId))) {
			return $tag->name;
		}
		return null;
	}
    public function getTagNameByTagId($tagId) {
        if($tag = $this->get(array('id', '=', $tagId))) {
            return $tag->name;
        }
        return null;
    }

    public function LoadAllEditableTags()
    {
        $loadedTags = $this->loadAllBasedOnFields(array('id', '>', '3'));
        return $loadedTags;
    }
	/*
 * searches for new tags to be added to the array,
 * if it is not an rray
 */
	public function addNewTagsToDb($tagArray)
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
        $this->_db->delete($this->_table, array('id', '=', $tagId));
    }

    public function getTagLessonMapFromTagId($tagId)
    {
        if($lessonTags = $this->_db->get('lesson_tag_map', array('tag_id', '=', $tagId))->results()){
            return $lessonTags;
        }
        return null;
    }

    public function getTagNamesFromTagMap($lessonTagMap)
    {
        if($lessonTagMap) {
            foreach($lessonTagMap as $mapValue) {
                $tagName = Bootstrap::getModel('incubate/tag')->load($mapValue['tag_id'])->getName();
                $lessonTags[] = $tagName;
            }
            return $lessonTags;
        }
    }
}