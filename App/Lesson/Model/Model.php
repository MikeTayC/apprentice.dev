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
    }

    public function fireEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_fire', $this);
    }

    public function afterEvent()
    {
        Bootstrap::dispatchEvent('lesson_event_after', $this);
    }

}