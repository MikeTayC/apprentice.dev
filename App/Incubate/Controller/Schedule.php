<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }
        else {
            echo Core_Model_Session::dangerFlash('error');
            echo Core_Model_Session::successFlash('message');
            $this->loadLayout();
            $this->render();

        }
	}

    public function eventAction()
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }

        if(!Core_Model_Session::get('admin_status')) {

            Core_Model_Session::dangerflash('error', 'Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }

        if(!empty($_POST)) {

            $user = Bootstrap::getModel('incubate/user');
            $lesson = Bootstrap::getModel('incubate/lesson');

            $lessonName = $_POST['lesson_name'];
			$tags = $_POST['tags'];
            $description = $_POST['description'];
            $studentList = $_POST['student_list'];
            $startTime = $_POST['start_time'];
			$date = $_POST['date'];

			//get lesson data from lesson name
			$lessonData = $lesson->get(array('name', '=', $lessonName));

			//get end time calculated from class duration, keeping same format
			$time = strtotime($startTime);
			$timeDuration = '+' . $lessonData->duration . 'minutes';
			$endTime = date("H:i", strtotime($timeDuration, $time));
			$endTime = date("g:i a", strtotime($endTime));
			$startTime = date("g:i a", $time);
			$startDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $startTime));
			$endDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $endTime));

			//prepare student email array to be added to google event
            $studentNameArray = explode(',', $studentList);
            foreach ($studentNameArray as $student) {
                $studentEmailArray[] = $user->getUserEmail($student);
            }

			//append tags on to description for google event
			$tagsArray = explode(',', $tags);
			foreach($tagsArray as $tag) {
				$description .= ' #' . $tag;
			}

            $client = new Google_Client();

            $calendar = new Core_Model_Calendar($client);
//            $auth = new Core_Model_Auth(null, $client);
            $calendar->setEvent($lessonName, $description, $startDateTime, $endDateTime, $studentEmailArray);

            $this->redirect('Incubate', 'Schedule', 'indexAction');
        }
    }

	public function lessonAction($lessonId)
	{
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }

        if(!Core_Model_Session::get('admin_status')) {

            Core_Model_Session::dangerflash('error', 'Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }

        if(isset($lessonId)) {

			$view = $this->loadLayout();
			$user = new Incubate_Model_User();
			$tag = Bootstrap::getModel('incubate/tag');
			$lesson = Bootstrap::getModel('incubate/lesson');

			if($lessonData = $lesson->get(array('lesson_id','=', $lessonId))) {

				$lessonTagMap = $lesson->getTagLessonMapFromLessonId($lessonData->lesson_id);

				//for eaach tag in the map, get the specific tag names from the tag table
                $lessonTags = array();
                if(isset($lessonTagMap)) {
                    foreach ($lessonTagMap as $mapValue) {
                        $tagName = $tag->getTagNameByTagId($mapValue->tag_id);
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
				$view->getContent()->setName($lessonData->name);
				$view->getContent()->setDescription($lessonData->description);
			}

			$view->render();
		}
	}
}