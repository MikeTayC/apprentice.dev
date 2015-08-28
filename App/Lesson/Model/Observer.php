<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 1:23 PM
 *
 * lesson model observer: listens for events being called, that require
 * lesson data manipulations
 **/
class Lesson_Model_Observer
{
    /**
     * Deletes all lesson data in CompletedCourseMap

     * Called when a lesson is deleted
     *
     * _data['id'] must be sent on event object
     * @param $eventObject
     **/
    public function deleteLessonCompletedCourseMap($eventObject)
    {
        if($lessonId = $eventObject->getId())
        {
            Bootstrap::getModel('incubate/completedCoursesMap')->setId($lessonId)->deleteLessonCompletedCourseMap();
        }
    }

    /**
     * Functions that sets the lesson to be scheduled on the eventObject
     * Called when scheduling event
     *
     * _data['lessonId'] must be set on event object
     * @param $eventObject
     **/
    public function setLessonOnEvent($eventObject)
    {
        if($lessonId = $eventObject->getLessonId()) {
            $lesson = Bootstrap::getModel('lesson/model')->load($lessonId);
            $eventObject->setLesson($lesson);
        }
    }
    /**
     * Functions that sets the lesson Id to be on the eventObject
     * Called when creating a lesson
     *
     * _data['name'] must be set on event object
     * @param $eventObject
     **/
    public function setLessonIdOnEvent($eventObject)
    {
        $lessonName = $eventObject->getName();
        $id = Bootstrap::getModel('lesson/model')->loadByName($lessonName)->getId();

        $eventObject->setLessonId($id);
    }

    /**
     * Sets date time on the eventObject Called when scheduling an event
     * Will format and both end and date time specific to google calendars (RFC-3339)
     *
     * @param $eventObject
     **/
    public function setEventDateTime($eventObject)
    {
        $lesson = $eventObject->getLesson();
        $duration = $lesson->getDuration();
        $date = $lesson->getDate();
        $startTime = $lesson->getData('start_time');

        //format and set start date time for event
        $startDateTime = $this->_formatStartDateTime($date,$startTime);
        $lesson->setStartDateTime($startDateTime);

        //format and set end date time for event
        $endDateTime = $this->_formatEndDateTime($date,$startTime, $duration);
        $lesson->setEndDateTime($endDateTime);
    }

    /**
     * Formats description and tags and sets it on event object
     * Called when scheduling an event with google calendar
     *
     * @param $eventObject
     **/
    public function setEventDescriptionAndTags($eventObject)
    {
        $lesson = $eventObject->getLesson();
        $tags = $lesson->getTags();
        $description = $lesson->getDescription();
        //append tags on to description for google event
        if(!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        $descriptionAndTags = $this->_appendTagsAndDescription($description, $tags);

        $lesson->setDescriptionAndTags($descriptionAndTags);
    }

    /**
     * Similar to function above, but used when formating and setting
     * description and tags on the lesson view
     *
     * @param $eventObject
     **/
    public function setViewDescriptionAndTags($eventObject)
    {
        $tags = $eventObject->getTags();
        $description = $eventObject->getDescription();
        //append tags on to description for google event
        if(!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        $descriptionAndTags = $this->_appendTagsAndDescription($description, $tags);

        $eventObject->setDescriptionAndTags($descriptionAndTags);
    }

    /**
     * Sets the total count of lesson in the lesson tables,
     * Used for tracking user progress percentage
     *
     * @param $eventObject
     **/
    public function setTotalLessonCount($eventObject)
    {
        $totalLessonCount = Bootstrap::getModel('lesson/model')->getTotalCount();
        $eventObject->setTotalLessonCount($totalLessonCount);
    }

    /**
     * retrieves all lessons, and sets it on the event object
     *
     * @param $eventObject
     **/
    public function setAllLessons($eventObject)
    {
        $lessons = Bootstrap::getModel('lesson/model')->loadAll();
        $eventObject->setLessons($lessons);
    }

    /**
     * formates start date for scheduling events
     *
     * @param $date
     * @param $startTime
     * @return bool|string
     **/
    private function _formatStartDateTime($date, $startTime)
    {
        $startTime = strtotime($startTime);
        $startTime = date("g:i a", $startTime);
        $startDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $startTime));
        return $startDateTime;
    }

    /**
     * Calculates and formtes date time for scheduling events
     * @param $date
     * @param $startTime
     * @param $duration
     * @return bool|string
     **/
    private function _formatEndDateTime($date, $startTime, $duration)
    {
        $startTime = strtotime($startTime);
        $timeDuration = '+' . $duration . 'minutes';
        $endTime = date("H:i", strtotime($timeDuration, $startTime));
        $endTime = date("g:i a", strtotime($endTime));
        $endDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $endTime));
        return $endDateTime;
    }

    /**
     * Formats description and tags into a  readable format for schedulign events
     * or reading from lesson view
     * @param $description
     * @param $tagsArray
     * @return string
     **/
    private function _appendTagsAndDescription($description, $tagsArray)
    {
        if(isset($tagsArray)) {
            foreach ($tagsArray as $tag) {
                $description .= ' #' . $tag;
            }
        }
        return $description;
    }
}