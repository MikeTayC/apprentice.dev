<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 8/16/15
 * Time: 10:46 PM
 *
 * Tag model model class is responible for handlign data in the tag table
 **/
class Tag_Model_Model extends Core_Model_Abstract
{
    /**
     * Sets table to tag, creates a database instance
     **/
	public function __construct()
	{
		$this->_table = 'tag';
		parent::__construct();
	}

    /**
     * loads all tags except tags for :
     * Application Engineer, Quality Assurance Analyst, Front End Developers
     *
     * @return array of tag models each with data specific to a new row
     **/
    public function LoadAllEditableTags()
    {
        $loadedTags = $this->loadAllBasedOnFields(array('id', '>', '3'));
        return $loadedTags;
    }

    /**
     * Searches for new tags to be added to the array
     * if they are new they are added
     *
     * @param $tagArray
     **/
	public function addNewTagsToDb($tagArray)
	{
		$dbTags = $this->getAllNamesAsArray();
		if($tagArray[0]) {
			foreach ($tagArray as $tag) {
				if (!in_array($tag, $dbTags)) {
					Bootstrap::getModel('tag/model')->setName($tag)->save();
				}
			}
		}
	}

    /**
     * funciton is used to get an array of tag names, based on array of
     * tagUd
     *
     * @param $tagMap : array of tagmap models with information specific to a tag
     * @return array : returns all names associated with the specific tag ids
     **/
    public function getTagNamesFromTagMap($tagMap)
    {
        if($tagMap) {
            foreach($tagMap as $mapValue) {
                $tagName = Bootstrap::getModel('tag/model')->load($mapValue['tag_id'])->getName();
                $lessonTags[] = $tagName;
            }
            return $lessonTags;
        }
    }

}