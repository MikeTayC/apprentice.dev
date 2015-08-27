<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:37 AM
 */
class Tag_Model_Observer
{
    public function addNewTagsToDb($eventObject)
    {
        $tagArray = $eventObject->getTags();
        //tags where passed, add the new ones to the database if there
        if($tagArray[0]) {
            Bootstrap::getModel('tag/model')->addNewTagsToDb($tagArray);
        }
    }

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

    public function deleteLessonTagMap($eventObject)
    {
        $lessonId = $eventObject->getId();
        if($lessonId) {
            Bootstrap::getModel('incubate/tagMap')->setId($lessonId)->deleteLessonTagMap();
        }
    }

    public function deleteTagMapOfLessonBasedOnTagId($eventObject)
    {
        $tagId = $eventObject->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/tagMap')->setId($tagId)->deleteTagMapOfLessonBasedOnTagId();
        }
    }

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

        if($userId && $tags[0]) {
           foreach($tags as $tag) {
               $tagId= Bootstrap::getModel('tag/model')->loadByName($tag)->getId();
               Bootstrap::getModel('incubate/userTagMap')->setData('user_id', $userId)->setData('tag_id', $tagId)->saveNoLoad();
           }
        }
    }

    public function deleteAllUserMapTags($eventObject)
    {
        $tagId = $eventObject->getId();
        if($tagId) {
            Bootstrap::getModel('incubate/userTagMap')->deleteAllTagsByTagId($tagId);
        }
    }

    public function setLessonTagsOnLesson($eventObject)
    {
        $lessonTagMap = Bootstrap::getModel('incubate/tagMap')->getAllBasedOnGivenFields(array('lesson_id', '=', $eventObject->getId()));
        $eventObject->setData('lessonTagMap', $lessonTagMap);
        $lessonTags = Bootstrap::getModel('tag/model')->getTagNamesFromTagMap($lessonTagMap);
        $eventObject->setTags($lessonTags);
    }

    public function setSuggestedStudentIdsOnLesson($eventObject)
    {

        $lessonTagMap = $eventObject->getData('lessonTagMap');
        $userTagMap = Bootstrap::getModel('incubate/userTagMap')->loadAllByTagIds($lessonTagMap);

        $eventObject->setData('userTagMap', $userTagMap);
    }

    public function setAllUserTags($eventObject)
    {
        $userId = $eventObject->getId();
        $userTagArray = Bootstrap::getModel('incubate/userTagMap')->loadUserTags($userId);
        $tagNames = Bootstrap::getModel('tag/model')->getTagNamesFromTagMap($userTagArray);

        $eventObject->setTags($tagNames);
    }
}