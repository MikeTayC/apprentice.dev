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

    public function setLessonOnEvent($eventObject)
    {
        $lessonId = $eventObject->getLessonId();
        $lesson = Bootstrap::getModel('lesson/model')->load($lessonId);

        $eventObject->setLesson($lesson);
    }

    public function setLessonIdOnEvent($eventObject)
    {
        $lessonName = $eventObject->getName();
        $id = Bootstrap::getModel('lesson/model')->loadByName($lessonName)->getId();

        $eventObject->setData('lessonId', $id);
    }

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