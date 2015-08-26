<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 1:23 PM
 */
class Lesson_Model_Observer
{
    public function deleteLessonCompletedCourseMap($eventObject)
    {
        if($lessonId = $eventObject->getId())
        {
            Bootstrap::getModel('incubate/completedCourseMap')->setId($lessonId)->deleteLessonCompletedCourseMap();
        }
    }

    public function setLessonIdOnEvent($eventObject)
    {
        $lessonName = $eventObject->getName();
        $id = Bootstrap::getModel('lesson/model')->loadByName($lessonName)->getId();

        $eventObject->setData('lessonId', $id);
    }

    public function setEventDateTime($eventObject)
    {
        $duration = $eventObject->getDuration();
        $date = $eventObject->getDate();
        $startTime = $eventObject->getData('start_time');

        //format and set start date time for event
        $startDateTime = $this->_formatStartDateTime($date,$startTime);
        $eventObject->setStartDateTime($startDateTime);

        //format and set end date time for event
        $endDateTime = $this->_formatEndDateTime($date,$startTime, $duration);
        $eventObject->setEndDateTime($endDateTime);
    }

    public function setEventDescriptionAndTags($eventObject)
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

    public function fireGoogleCalendarEvent($eventObject)
    {
        $lessonName = $eventObject->getName();
        $descriptionAndTags  = $eventObject->getDescriptionAndTags();
        $startDateTime = $eventObject->getStartDateTime();
        $endDateTime = $eventObject->getEndDateTime();
        $emailArray = $eventObject->getEmailArray();

        $client = new Google_Client();
        $calendar = new Core_Model_Calendar($client);
        $calendar->setEvent($lessonName, $descriptionAndTags, $startDateTime, $endDateTime, $emailArray);
    }

    public function setTotalLessonCount($eventObject)
    {
        $totalLessonCount = Bootstrap::getModel('lesson/model')->getTotalCount();
        $eventObject->setTotalLessonCount($totalLessonCount);
    }

    public function setAllLessons($eventObject)
    {
        $lessons = Bootstrap::getModel('lesson/model')->loadAll();
        $eventObject->setLessons($lessons);
    }

    private function _formatStartDateTime($date, $startTime)
    {
        $startTime = strtotime($startTime);
        $startTime = date("g:i a", $startTime);
        $startDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $startTime));
        return $startDateTime;
    }

    private function _formatEndDateTime($date, $startTime, $duration)
    {
        $startTime = strtotime($startTime);
        $timeDuration = '+' . $duration . 'minutes';
        $endTime = date("H:i", strtotime($timeDuration, $startTime));
        $endTime = date("g:i a", strtotime($endTime));
        $endDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $endTime));
        return $endDateTime;
    }

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