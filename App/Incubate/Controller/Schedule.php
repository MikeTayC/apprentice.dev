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
        echo Core_Model_Session::dangerFlash('error');
        echo Core_Model_Session::successFlash('message');
        $this->render();

	}

    public function eventAction()
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if(!empty($_POST)) {

            $user = Bootstrap::getModel('incubate/user');
			$duration = Bootstrap::getModel('incubate/lesson')->loadByName($lessonName)->getDuration();

            $lessonName = $_POST['lesson_name'];
			$tags = $_POST['tags'];
            $description = $_POST['description'];
            $studentList = $_POST['student_list'];
            $startTime = $_POST['start_time'];
			$date = $_POST['date'];

			//get lesson data from lesson name

			//get end time calculated from class duration, keeping same format
			$time = strtotime($startTime);
			$timeDuration = '+' . $duration . 'minutes';
			$endTime = date("H:i", strtotime($timeDuration, $time));
			$endTime = date("g:i a", strtotime($endTime));
			$startTime = date("g:i a", $time);
			$startDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $startTime));
			$endDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $endTime));

			//prepare student email array to be added to google event
            $studentNameArray = explode(',', $studentList);
            foreach ($studentNameArray as $student) {
                $studentEmailArray[] = $user->loadByName($student)->getEmail();
            }

			//append tags on to description for google event
			$tagsArray = explode(',', $tags);
			foreach($tagsArray as $tag) {
				$description .= ' #' . $tag;
			}

            $client = new Google_Client();

            $calendar = new Core_Model_Calendar($client);

            $calendar->setEvent($lessonName, $description, $startDateTime, $endDateTime, $studentEmailArray);

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

			$user = Bootstrap::getModel('incubate/user');
			$tag = Bootstrap::getModel('incubate/tag');
			$lesson = Bootstrap::getModel('incubate/lesson');

			$lessonTagMap = $lesson->load($lessonId)->getTagLessonMapForLesson();

				//for eaach tag in the map, get the specific tag names from the tag table
                $lessonTags = array();
                if(isset($lessonTagMap)) {
                    foreach ($lessonTagMap as $mapValue) {

                        $tagName = $tag->load(($mapValue->tag_id))->getName();

                        $lesson->checkForGroupTagAndAssign($mapValue->tag_id);

                        $lessonTags[] = $tagName;
                    }
                }

				//for each student whos group is tagged, add themt o the list of recommended students to take
				$studentInviteList = array();
				if($lesson->AE) {
					$user->AddStudentsIfNotTaken('Application Engineer', $lessonId);
				}
				if($lesson->QA) {
					$user->AddStudentsIfNotTaken('Quality Assurance Analyst', $lessonId);
				}
				if($lesson->FE){
					$user->AddStudentsIfNotTaken('Front End Developer', $lessonId);
				}

                $studentInviteList = $user->studentInviteList;

				$view->getContent()->setStudents($studentInviteList);
				$view->getContent()->setTags($lessonTags);
				$view->getContent()->setName($lesson->getName());
				$view->getContent()->setDescription($lesson->getDescription());
			}

			$view->render();
		}
}