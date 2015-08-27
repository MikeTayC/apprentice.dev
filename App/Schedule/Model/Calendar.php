<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/27/15
 * Time: 8:24 AM
 */
class Schedule_Model_Calendar extends Core_Model_Object
{
    public function loadLesson()
    {
        Bootstrap::dispatchEvent('lesson_load_event', $this);
        return $this;
    }

    public function loadEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_set', $this);
        return $this;
    }

    public function fireEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_fire', $this);
        return $this;
    }

    public function afterEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_after', $this);
        return $this;
    }

}