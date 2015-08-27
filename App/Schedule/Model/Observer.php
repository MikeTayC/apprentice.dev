<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/27/15
 * Time: 8:29 AM
 */
class Schedule_Model_Observer extends Core_Model_Abstract
{
    public function fireGoogleCalendarEvent($eventObject)
    {
        $lesson = $eventObject->getLesson();
        $lessonName = $lesson->getName();
        $descriptionAndTags  = $lesson->getDescriptionAndTags();
        $startDateTime = $lesson->getStartDateTime();
        $endDateTime = $lesson->getEndDateTime();
        $emailArray = $lesson->getEmailArray();

        $googleClient = new Google_Client();
        $calendar = new Core_Model_Calendar($googleClient);
        $calendar->setEvent($lessonName, $descriptionAndTags, $startDateTime, $endDateTime, $emailArray);
    }
}