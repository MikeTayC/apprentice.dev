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

		if($tagArray[0]) {
			foreach ($tagArray as $tag) {
				if (!in_array($tag, $dbTags)) {
					Bootstrap::getModel('incubate/tag')->setName($tag)->save();
				}
			}
		}
	}

    public function getTagLessonMapFromTagId($tagId)
    {
        if($lessonTags = $this->_db->get('TagMap', array('tag_id', '=', $tagId))->results()){
            return $lessonTags;
        }
        return null;
    }

    public function getTagNamesFromTagMap($tagMap)
    {
        if($tagMap) {
            foreach($tagMap as $mapValue) {
                $tagName = Bootstrap::getModel('incubate/tag')->load($mapValue['tag_id'])->getName();
                $lessonTags[] = $tagName;
            }
            return $lessonTags;
        }
    }

    protected function _afterDelete()
    {
        //dispatch event prioer to delteing tag, remove tag from lesson tag map
        Bootstrap::dispatchEvent('delete_tag_before', $this);
    }
}