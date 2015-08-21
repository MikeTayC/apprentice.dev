<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:37 AM
 */
class Incubate_Model_TagObserver extends Core_Model_Object
{
    public function addNewTagsToDb($eventObject)
    {
        //tags where passed, add the new ones to the database if there
        if($tagArray = $eventObject->getTags()) {
            Bootstrap::getModel('incubate/tag')->addNewTagsToDb($tagArray);
        }
    }

    public function attachTagsToLesson($eventObject)
    {
        $tagArray = $eventObject->getTags();
        $lessonId = $eventObject->getData('lessonId');
        if($tagArray[0] && $lessonId) {
            foreach ($tagArray as $tags) {
                $tagId = Bootstrap::getModel('incubate/tag')->loadByName($tags)->getId();
                Bootstrap::getModel('incubate/tagMap')->setLesson($lessonId)->setTag($tagId)->createTagMap();
            }
        }
    }

    public function deleteLessonTagMap($eventObject)
    {
        $lessonId = $eventObject->getData('lessonId');
        if($lessonId) {
            Bootstrap::getModel('incubate/tagMap')->setId($lessonId)->deleteLessonTagMap();
        }
    }

    public function deleteTagMapOfLessonBasedOnTagId($eventObject)
    {
        $tagId = $eventObject->getTag();
        if($tagId) {
            Bootstrap::getModel('incubate/tagMap')->setId($tagId)->deleteTagMapOfLessonBasedOnTagId();
        }
    }

    public function setNewUserTag($eventObject)
    {
        $user = $eventObject->getUser();
        $group = $user->getGroups();
        $userId = $user->loadByName($user->getName())->getId();

        $tagId = Bootstrap::getModel('incubate/tag')->loadByName($group)->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/userTagMap')->setData('user_id', $userId)->setData('tag_id', $tagId)->saveNoLoad();
        }
    }

    public function deleteUserTags($eventObject)
    {
        $userId = $eventObject->getId();

        if($userId) {
            Bootstrap::getModel('incubate/userTagMap')->deleteAllUserTags($userId);
        }
    }

    public function addTagsToUser($eventObject)
    {
        $userId = $eventObject->getId();
        $tags = $eventObject->getTags();

        if($userId && $tags) {
           foreach($tags as $tag) {
               $tagId= Bootstrap::getModel('incubate/tag')->loadByName($tag)->getId();
               Bootstrap::getModel('incubate/userTagMap')->setData('user_id', $userId)->setData('tag_id', $tagId)->saveNoLoad();
           }
        }
    }

    public function deleteAllUserMapTags($eventObject)
    {
        $tagId = $eventObject->getTag();

        if($tagId) {
            Bootstrap::getModel('incubate/userTagMap')->deleteAllTagsByTagId($tagId);
        }
    }

}