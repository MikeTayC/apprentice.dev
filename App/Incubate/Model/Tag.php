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

	public function getTagsFromTagTableByTagId($tagId)
	{
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
		$dbTags = $this->getAll();
		if(is_array($tagArray)) {
			foreach ($tagArray as $tag) {
				if (!in_array($tag, $dbTags)) {
					$this->create('tag', array(
						'value' => $tag
					));
				}
			}
		}
	}

}