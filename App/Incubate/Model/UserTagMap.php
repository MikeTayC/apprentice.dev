<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/21/15
 * Time: 8:44 AM
 *
 * Incubate model UserTagMap, is responsible for interacting with data
 * from the UserTagMap table,
 *
 * Keeps track of user to tag relationships
 *
 * extends the core model abstract class
 **/
class Incubate_Model_UserTagMap extends Core_Model_Abstract
{

    /**
     * Sets the table to UserTagMap
     * parent constructor sets an singleton instance database connection
     **/
    public function __construct()
    {
        $this->_table = 'UserTagMap';
        parent::__construct();
    }

    /**
     * Load all a users associated tags
     * returns an array of models specific to each row, with tag and user information
     *
     * returns an array of
     * @param $userId
     * @return array|null
     **/
    public function loadUserTags($userId)
    {

        $allUserTagData = $this->loadAllBasedOnFields(array('user_id', '=', $userId));
        foreach($allUserTagData as $userData) {
            $allUserTags[] = $userData->_data;
        }
        if(!empty($allUserTags)) {
            return $allUserTags;
        }
        return null;
    }

    /**
     * Loads user ids based on an array of many tags
     *
     *
     * @param $tagArray, array of tag ids
     * @return array: array of user ids,
     **/
    public function loadAllByTagIds($tagArray)
    {
        $userMapMergeArray = array();
        if($tagArray){
            foreach($tagArray as $tag){
                $tagId = $tag['tag_id'];
                $userMapIds = $this->getUserIdAsArrayByTagID($tagId);
                $userMapMergeArray = array_merge($userMapMergeArray, $userMapIds);
            }
            $userMapMergeArray = array_unique($userMapMergeArray);
        }
        return $userMapMergeArray;
    }

    /**
     * returns all users ids specific to a single tag id as an array
     *
     * @param $tagId : tag id to check against
     * @return array : array of user ids
     **/
    public function getUserIdAsArrayByTagId($tagId)
    {
        $allData = $this->loadAllBasedOnFields(array('tag_id', '=', $tagId));
        $allUserId = array();

        foreach($allData as $data) {
            $allUserId[] = $data->getData('user_id');
        }

        return $allUserId;
    }

    /**
     * Deletes all user tags, based on tag id,
     * Used when a user is deleted, or when replacing user tag map
     * 
     * @param $userId
     **/
    public function deleteAllUserTags($userId)
    {
        $this->deleteAll(array('user_id', '=', $userId));
    }

    /**
     * Deletes record of any tag,
     *
     * Used when deleting a tag
     *
     * @param $tagId
     **/
    public function deleteAllTagsByTagId($tagId)
    {
        $this->deleteAll(array('tag_id', '=', $tagId));
    }
}