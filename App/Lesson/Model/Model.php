<?php

/**
 * Class Lesson_Model_Model handles all lesson data
 *
 * extends core model abstract class
 */
class Lesson_Model_Model extends Core_Model_Abstract
{
    /**
     * Sets table to be manipulated 'lesson' table in database
     *
     * parent constructor sets up database connection
     **/
    public function __construct()
    {
        $this->_table = 'lesson';
        parent::__construct();
    }

    /**
     * Function dispatches events to prep lesson data with required
     * information for scheduling an event
     * @return $this :
     **/
    public function loadEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_set', $this);
        return $this;
    }

    /**
     * Follows loadEvent() function, dispatchess prepared information
     * to be sent to google event scheduler functions
     * @return $this
     */
    public function fireEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_fire', $this);
        return $this;
    }

    /**
     * Follows fireEvent() function, will set manipulate
     * user completed course status, setting user id, lesson id and
     * the date of completion into CompletedCoursesMap
     *
     * @return $this
     **/
    public function afterEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_after', $this);
        return $this;
    }

    /**
     * Dispatches events so lesson data can have information specific
     * for viewing
     *
     * @return $this
     **/
    public function loadView()
    {
        Bootstrap::dispatchEvent('lesson_view_after', $this);
        return $this;
    }

    /**
     * Dispatches events that will associate tags to a specific lesson
     *
     * @return $this
     **/
    public function saveTags()
    {
        Bootstrap::dispatchEvent('lesson_attach_tags', $this);
        return $this;
    }

}