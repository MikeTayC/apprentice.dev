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
        $this->checkIfUserIsLoggedIn();
        $this->loadLayout();
        $this->flashCheck();
        $this->render();

	}

    public function eventAction()
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if(!empty($_POST)) {


            $lessonName = $_POST['lesson_name'];
			$tags = $_POST['tags'];
            $description = $_POST['description'];
            $studentList = $_POST['student_list'];
            $startTime = $_POST['start_time'];
			$date = $_POST['date'];


			$duration = Bootstrap::getModel('incubate/lesson')->loadByName($lessonName)->getDuration();
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
            $descriptionAndTags = $this->appendTagsAndDescition($description, $tagsArray);


            //load new google calendar event, and fire event
            $client = new Google_Client();
            $calendar = new Core_Model_Calendar($client);
            $calendar->setEvent($lessonName, $descriptionAndTags, $startDateTime, $endDateTime, $studentEmailArray);

            $this->headerRedirect('incubate', 'schedule', 'index');
            exit;
        }
    }

	public function lessonAction($lessonId)
	{
        $this->checkIfUserIsLoggedIn();

        $this->checkIfUserIsAdmin();

        if(isset($lessonId)) {

			$view = $this->loadLayout();

			$lesson = Bootstrap::getModel('incubate/lesson');

			$lessonTagMap = $lesson->load($lessonId)->getTagLessonMapForLesson();

            //for each tag in the map, get the specific tag names from the tag table
            $lessonTags = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($lessonTagMap);

            //assigns $AE,$QA, $FE properties to true if, they are tagged.
            $lesson->checkForGroupTagAndAssign($lessonTagMap);

            //for each student whos group is tagged, add themt o the list of recommended students to take
            $studentInviteList = Bootstrap::getModel('incubate/user')->addStudentsIfTaggedAndNotTaken($lesson->AE, $lesson->QA, $lesson->FE, $lessonId);


            $view->getContent()->setStudents($studentInviteList)->setTags($lessonTags)->setLesson($lesson);

		}
		$view->render();
	}
}