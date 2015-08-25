<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/20/15
 * Time: 1:23 PM
 */
class Incubate_Model_LessonObserver extends Core_Model_Object
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
        $id = Bootstrap::getModel('incubate/lesson')->loadByName($lessonName)->getId();

        $eventObject->setData('lessonId', $id);
    }

    public function setEventDateTime($eventObject)
    {
        $duration = $eventObject->getDuration();
        $date = $eventObject->getDate();
        $startTime = $eventObject->getData('start_time');

        //format and set start date time for event
        $startDateTime = $this->_formatStartDateTime($date,$startTime);
        $eventObject->setData('startDateTime', $startDateTime);

        //format and set end date time for event
        $endDateTime = $this->_formatEndDateTime($date,$startTime, $duration);
        $eventObject->setData('endDateTime', $endDateTime);
    }

    public function setEventDescriptionAndTags($eventObject)
    {
        $tags = $eventObject->getTags();
        $description = $eventObject->getDescription();
        //append tags on to description for google event
        $tagsArray = explode(',', $tags);
        $descriptionAndTags = $this->_appendTagsAndDescription($description, $tagsArray);

        $eventObject->setData('descriptionAndTags', $descriptionAndTags);
    }

    public function fireGoogleCalendarEvent($eventObject)
    {
        $lessonName = $eventObject->getName();
        $descriptionAndTags  = $eventObject->getData('descriptionAndTags');
        $startDateTime = $eventObject->getData('startDateTime');
        $endDateTime = $eventObject->getData('endDateTime');
        $studentEmailArray = $eventObject->getData('studentEmailArray');

        $client = new Google_Client();
        $calendar = new Core_Model_Calendar($client);
        $calendar->setEvent($lessonName, $descriptionAndTags, $startDateTime, $endDateTime, $studentEmailArray);
    }

    public function setTotalLessonCount($eventObject)
    {
        $totalLessonCount = Bootstrap::getModel('incubate/lesson')->getTotalCount();
        $eventObject->setData('totalLessonCount', $totalLessonCount);
    }

    public function setAllLessons($eventObject)
    {
        $lessons = Bootstrap::getModel('incubate/lesson')->loadAll();
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