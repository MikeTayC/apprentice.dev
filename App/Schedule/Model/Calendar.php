<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/27/15
 * Time: 8:24 AM
 *
 * Schedule model calendar is mainly responsibl for
 * readying data to be fired in an event
 **/
class Schedule_Model_Calendar extends Core_Model_Object
{
    /**
     * Dispatches events that loads the specified lesson on the object to be dipatched
     * @return $this
     **/
    public function loadLesson()
    {
        Bootstrap::dispatchEvent('lesson_load_event', $this);
        return $this;
    }

    /**
     * Dispatches events that repares the nested lesson object for firing
     * @return $this
     **/
    public function loadEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_set', $this);
        return $this;
    }

    /**
     * Dispatches events that fires the lesson with google calendar
     *
     * @return $this
     **/
    public function fireEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_fire', $this);
        return $this;
    }

    /**
     * Dispatches events that will check each user in the scheduled event
     * and mark the coourses completed in CoursesCompltedMap
     * @return $this
     **/
    public function afterEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_after', $this);
        return $this;
    }

}