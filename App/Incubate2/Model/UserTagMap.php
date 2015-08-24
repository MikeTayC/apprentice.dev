<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/21/15
 * Time: 8:44 AM
 */
class Incubate_Model_UserTagMap extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'UserTagMap';
        parent::__construct();
    }

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

    public function getUserIdAsArrayByTagId($tagId)
    {
        $allData = $this->loadAllBasedOnFields(array('tag_id', '=', $tagId));
        $allUserId = array();

        foreach($allData as $data) {
            $allUserId[] = $data->getData('user_id');
        }

        return $allUserId;
    }

    public function deleteAllUserTags($userId)
    {
        $this->deleteAll(array('user_id', '=', $userId));
    }

    public function deleteAllTagsByTagId($tagId)
    {
        $this->deleteAll(array('tag_id', '=', $tagId));
    }
}