<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 10:31 AM
 *
 * Incubate model TagMap, is a model responsible for keeping track
 * of lesson relationships with tags, extneds the core model abstract class
 **/
class Incubate_Model_TagMap extends Core_Model_Abstract
{

    /**
     * Sets the table to 'TagMap',
     * Parent constructor sets up sigleton database connection
     **/
    public function __construct()
    {
        $this->_table = 'TagMap';
        parent::__construct();
    }

    /**
     * Creates a TagMap for a new lesson
     *
     * _data['tag'] must be set to tag id
     * _data['lesson'] must be set to lesson id
     **/
    public function createTagMap()
    {
        $this->create(array(
            'lesson_id' => $this->getLesson(),
            'tag_id' => $this->getTag()
        ));
    }

    /**
     * Deletes all record of a lesson from tagmap table,
     * Used when deleteing a lesson,
     *
     * _data['id'] must be set to lesson id
     **/
    public function deleteLessonTagMap()
    {
        $this->deleteAll(array('lesson_id', '=', $this->getId()));
    }


    /**
     * Deletes all record of a tag from tagmap table,
     * Used when deleting a tag
     *
     * _data['id'] must be set to tag id
     **/
    public function deleteTagMapOfLessonBasedOnTagId()
    {
        $this->deleteAll(array('tag_id', '=', $this->getId()));
    }
}