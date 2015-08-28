<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:37 AM
 *
 * Tag Observer responsible for listening to events regarding tags
 **/
class Tag_Model_Observer
{
    /**
     * Adds new tags to the database via the event object
     * _data['tags'] must be set
     * @param $eventObject : core_model_object
     **/
    public function addNewTagsToDb($eventObject)
    {
        $tagArray = $eventObject->getTags();
        //tags where passed, add the new ones to the database if there
        if($tagArray[0]) {
            Bootstrap::getModel('tag/model')->addNewTagsToDb($tagArray);
        }
    }

    /**
     * Attaches tags to a lesson by inserting the association into the table
     * TagMap
     *
     * _data['tags'] must set
     * _data['lessonId'] must set
     * @param $eventObject: core model object
     **/
    public function attachTagsToLesson($eventObject)
    {
        $tagArray = $eventObject->getTags();
        $lessonId = $eventObject->getLessonId();
        if($tagArray[0] && $lessonId) {
            foreach ($tagArray as $tags) {
                $tagId = Bootstrap::getModel('tag/model')->loadByName($tags)->getId();
                Bootstrap::getModel('incubate/tagMap')->setLesson($lessonId)->setTag($tagId)->createTagMap();
            }
        }
    }

    /**
     * Deletes a tag lesson map of a lesson
     *
     * @param $eventObject
     **/
    public function deleteLessonTagMap($eventObject)
    {
        $lessonId = $eventObject->getId();
        if($lessonId) {
            Bootstrap::getModel('incubate/tagMap')->setId($lessonId)->deleteLessonTagMap();
        }
    }

    /**
     * deletes a tag map for a specific tag id,
     *
     * @param $eventObject
     **/
    public function deleteTagMapOfLessonBasedOnTagId($eventObject)
    {
        $tagId = $eventObject->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/tagMap')->setId($tagId)->deleteTagMapOfLessonBasedOnTagId();
        }
    }

    /**
     * sets up a tag map for a user
     *
     * @param $eventObject
     **/
    public function setNewUserTag($eventObject)
    {
        $name = $eventObject->getName();
        $userId = $eventObject->loadByName($name)->getId();
        $group = $eventObject->getGroups();

        $tagId = Bootstrap::getModel('tag/model')->loadByName($group)->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/userTagMap')->setData('user_id', $userId)->setData('tag_id', $tagId)->saveNoLoad();
        }
    }

    /**
     * Deletes all of a a specific users tags
     *
     * @param $eventObject
     **/
    public function deleteUserTags($eventObject)
    {
        $userId = $eventObject->getId();

        if($userId) {
            Bootstrap::getModel('incubate/userTagMap')->deleteAllUserTags($userId);
        }
    }

    /**
     * Updates a specific users tags
     *
     * @param $eventObject
     **/
    public function addTagsToUser($eventObject)
    {
        $userId = $eventObject->getId();
        $tags = $eventObject->getTags();

        if($userId && $tags[0]) {
           foreach($tags as $tag) {
               $tagId= Bootstrap::getModel('tag/model')->loadByName($tag)->getId();
               Bootstrap::getModel('incubate/userTagMap')->setData('user_id', $userId)->setData('tag_id', $tagId)->saveNoLoad();
           }
        }
    }

    /**
     * Delete all a tag from usermaptags
     * @param $eventObject
     **/
    public function deleteAllUserMapTags($eventObject)
    {
        $tagId = $eventObject->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/userTagMap')->deleteAllTagsByTagId($tagId);
        }
    }

    /**
     * Add lesson tags to lesson event obect
     *
     * @param $eventObject
     **/
    public function setLessonTagsOnLesson($eventObject)
    {
        if($lessonTagMap = Bootstrap::getModel('incubate/tagMap')->getAllBasedOnGivenFields(array('lesson_id', '=', $eventObject->getId()))){
            $eventObject->setData('lessonTagMap', $lessonTagMap);
            $lessonTags = Bootstrap::getModel('tag/model')->getTagNamesFromTagMap($lessonTagMap);
            if(isset($lessonTags[0])) {
                $eventObject->setTags($lessonTags);
            }
        }
    }

    /**
     * set suggested students ids on lesson event object,
     *
     * for use in sheduling an event
     *
     * @param $eventObject
     **/
    public function setSuggestedStudentIdsOnLesson($eventObject)
    {
        $lessonTagMap = $eventObject->getData('lessonTagMap');
        $userTagMap = Bootstrap::getModel('incubate/userTagMap')->loadAllByTagIds($lessonTagMap);

        $eventObject->setData('userTagMap', $userTagMap);
    }

    /**
     * Set all user related tags on an event object
     *
     * for use in viewing specific user tags
     *
     * @param $eventObject
     **/
    public function setAllUserTags($eventObject)
    {
        $userId = $eventObject->getId();
        $userTagArray = Bootstrap::getModel('incubate/userTagMap')->loadUserTags($userId);
        $tagNames = Bootstrap::getModel('tag/model')->getTagNamesFromTagMap($userTagArray);

        $eventObject->setTags($tagNames);
    }
}