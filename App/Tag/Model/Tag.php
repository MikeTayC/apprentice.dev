<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/16/15
 * Time: 10:46 PM
 */
class Tag_Model_Tag extends Core_Model_Abstract
{
	public function __construct()
	{
		$this->_table = 'tag';

		parent::__construct();
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
					Bootstrap::getModel('tag/tag')->setName($tag)->save();
				}
			}
		}
	}

    public function getTagNamesFromTagMap($tagMap)
    {
        if($tagMap) {
            foreach($tagMap as $mapValue) {
                $tagName = Bootstrap::getModel('tag/tag')->load($mapValue['tag_id'])->getName();
                $lessonTags[] = $tagName;
            }
            return $lessonTags;
        }
    }

}