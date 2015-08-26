<?php

class Lesson_Model_Model extends Core_Model_Abstract
{
    public function __construct()
    {
        $this->_table = 'lesson';

        parent::__construct();

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

    public function loadView()
    {
        Bootstrap::dispatchEvent('lesson_view_after', $this);
        return $this;
    }

}