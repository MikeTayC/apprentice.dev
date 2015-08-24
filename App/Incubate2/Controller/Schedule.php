<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        $this->_checkIfUserIsLoggedIn();
        $this->loadLayout();
        $this->_flashCheck();
        $this->render();

	}

    public function eventAction()
    {

        $request = $this->_getRequest();
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();

        if($request->isPost()) {

            $lessonName = $request->getPost('lesson_name'); //@todo: convert other direct post accessors to this method

            $tags =  $request->getPost('tags');
            $description = $request->getPost('description');
            $studentList = $request->getPost('student_list');
            $startTime = $request->getPost('start_time');
			$date = $request->getPost('date');


			$lesson  = Bootstrap::getModel('incubate/lesson')->loadByName($lessonName);
            $duration = $lesson->getDuration();

			//get lesson data from lesson name

			//get end time calculated from class duration, keeping same format
            $startDateTime  = $this->formatStartDateTime($date, $startTime);
            $endDateTime = $this->formatEndDateTime($date, $startTime,$duration);

			//prepare student email array to be added to google event
            $studentNameArray = $this->explode($studentList);
            foreach ($studentNameArray as $student) {
                $studentEmailArray[] = Bootstrap::getModel('incubate/user')->loadByName($student)->getEmail();
            }

			//append tags on to description for google event
            $tagsArray = $this->explode($tags);
            $descriptionAndTags = $this->appendTagsAndDescription($description, $tagsArray);

            //load new google calendar event, and fire event
            $client = new Google_Client();
            $calendar = new Core_Model_Calendar($client);
            $calendar->setEvent($lessonName, $descriptionAndTags, $startDateTime, $endDateTime, $studentEmailArray);

            //TODO save end date in completed course table
            $event = Bootstrap::getModel('core/event')->setLesson($lesson->getId())->setStudents($studentNameArray)->setDate($endDateTime);
            Bootstrap::dispatchEvent('schedule_event_after', $event);

            $this->_successFlash('Your event has been scheduled');
            $this->_thisModuleRedirect('schedule');
        }
    }

	public function lessonAction($lessonId)
	{
        $this->_checkIfUserIsLoggedIn();

        $this->_checkIfUserIsAdmin();

        $this->_idCheck($lessonId, 'lesson');

        $view = $this->loadLayout();

        /** @var Incubate_Model_Lesson $lesson */
        $lesson = Bootstrap::getModel('incubate/lesson');

        $lessonTagMap = $lesson->load($lessonId)->getTagLessonMapForLesson();

        //for each tag in the map, get the specific tag names from the tag table
        $lessonTags = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($lessonTagMap);

        $userTagMap = Bootstrap::getModel('incubate/userTagMap')->loadAllByTagIds($lessonTagMap);

        $completedCourseMap = Bootstrap::getModel('incubate/completedCourseMap');

        $studentInviteList = array();
        foreach($userTagMap as $id) {
            if(!$completedCourseMap->completedCheck($id,$lessonId)) {
                $studentInviteList[] = Bootstrap::getModel('incubate/user')->load($id);
            }
        }

        $view->getContent()->setStudents($studentInviteList)->setTags($lessonTags)->setLesson($lesson);
		$view->render();
	}
}