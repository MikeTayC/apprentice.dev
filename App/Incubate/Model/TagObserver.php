<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:37 AM
 */
class Incubate_Model_TagObserver extends Core_Model_Object
{
    public function addNewTagsAfterLesson($eventObject)
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
        if($tagArray && $lessonId) {
            foreach ($tagArray as $tags) {
                $tagId = Bootstrap::getModel('incubate/tag')->loadByName($tags)->getId();
                Bootstrap::getModel('incubate/tagMap')->createTagMap($lessonId, $tagId);
            }
        }
    }

}