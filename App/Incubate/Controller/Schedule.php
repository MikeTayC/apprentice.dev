<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Core_Controller_Abstract
{
    public function indexAction($param)
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::flash('error', '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>You are not logged in, bro. Got redirected</p></div>');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }
        else {
            echo Core_Model_Session::flash('error');
            $this->loadLayout();
            $this->render();

        }
    }

    public function lessondAction()
    {
        //check for admin status, if not admin send back to schedule index
        if(!Core_Model_Session::get('admin_status')) {
            Core_Model_Session::flash('error', '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>Admins only bro</p></div>');
            $this->headerRedirect('incubate', 'schedule', 'index');
            exit;
        }

        if(!empty($_POST) && isset($_POST['lesson_name'])) {

            //store post in local variable
            $lessonName = $_POST['lesson_name'];

            //load model
            $user = Bootstrap::getModel('incubate/user');

            //redirect if lesson is not in database
            if(!$user->get('lesson', array('name', '=', $lessonName))){
                $this->headerRedirect('incubate', 'schedule', 'index');
                exit;
            }

            //store lesson name in session so we can tell which info to call
            Core_Model_Session::set('lesson_name', $lessonName);

            //redirect to next stage in scheduling, students invite list
            $this->headerRedirect('incubate', 'schedule', 'student');
        }
        else {
            $this->loadLayout();
            $this->render();
        }
    }

    public function studentAction()
    {
        //check for admin status, if not admin send back to schedule index
        if(!Core_Model_Session::get('admin_status')) {
            $this->headerRedirect('incubate', 'schedule', 'index');
            exit;
        }
        //load view
        $view = $this->loadLayout();

        //load model
        $user = Bootstrap::getModel('incubate/user');

        //check post is not empty and set
        if(!empty($_POST) && isset($_POST['student_list'])) {

            //store post variable in local variable
            $student_list = $_POST['student_list'];

            //store student list in session for later confirmation
            Core_Model_Session::set('student_list', $student_list);

            //redirect to next page in scheduling process
            $this->headerRedirect('incubate','schedule', 'datetime');
            exit;
        }
        else {

            //store session avariable with lesson name in local variable;
            $lessonName = Core_Model_Session::get('lesson_name');

            //redirect if lesson is not in database, check again
            if(!$lessonData = $user->get('lesson', array('name', '=', $lessonName))){
                $this->headerRedirect('incubate', 'schedule', 'index');
                exit;
            }

            /*
             * Check which students should be invited, based on group tag and if they have yet to take
             * the course yet.
             */
            $studentInviteList = $user->addStudentsToInviteListIfTaggedAndNotTaken($lessonData);

            $view->getContent()->setStudents($studentInviteList);

            $view->render();
        }
    }

    public function datetimeAction()
    {
        //check for admin status, if not admin send back to schedule index
        if(!Core_Model_Session::get('admin_status')) {
            $this->headerRedirect('incubate', 'schedule', 'index');
            exit;
        }


        if(!empty($_POST) && isset($_POST['date']) && isset($_POST['start_time'])) {

            //store post variables in local ones
            $date = $_POST['date'];
            $start_time = $_POST['start_time'];

            //store date and start time in session for later confirmation
            Core_Model_Session::set('date', $date);
            Core_Model_Session::set('start_time', $start_time);

            //redirect to final confirmation page
            $this->headerRedirect('incubate', 'schedule', 'confirmation');
            exit;
        }
        else {
            $view = $this->loadLayout();
            $view->render();
        }
    }

    public function confirmationAction()
    {
        //check for admin status, if not admin send back to schedule index
        if(!Core_Model_Session::get('admin_status')) {
            $this->headerRedirect('incubate', 'schedule', 'index');
            exit;
        }

        //store session varialbes in local variables
        $lesson_name  = Core_Model_Session::get('lesson_name');
        $student_list = Core_Model_Session::get('student_list');
        $date         = Core_Model_Session::get('date');
        $start_time   = Core_Model_Session::get('start_time');

        //if any of these are null and not set, the form is incomplete, send back to index
        if(!($lesson_name && $student_list && $date && $start_time)) {
            echo "You must have forgot to fill something out, try again.";
            $this->headerRedirect('incubate', 'schedule', 'index');
        }

        $view = $this->loadLayout();
        $user = new Incubate_Model_User();

        //from lesson name get all lesson description, duration , lesson id
        $lessonData = $user->get('lesson', array('name', '=', $lesson_name));

        //get tags specific to lesson
        $lessonTagMap = $user->getTagLessonMapFromLessonId($lessonData->lesson_id);

        //for eaach tag in the map, get the specific tag names from the tag table
        foreach ($lessonTagMap as $mapKey => $mapValue) {
            $lessonTags[] = $user->getTagsFromTagTableByTagId($mapValue->tag_id);
        }

        //turn comma separted list into array
        $studentArray = explode(',', $student_list);

        //get end time calculated from class duration, keeping same format
        $time = strtotime($start_time);
        $timeDuration = '+' . $lessonData->duration . 'minutes';
        $endTime = date("H:i", strtotime($timeDuration, $time));
        $endTime = date("g:i a", strtotime($endTime));
        $startTime = date("g:i a", $time);

        //bind name
        $view->getContent()->setName($lessonData->name);

        //bind description
        $view->getContent()->setDescription($lessonData->description);

        //bind tags
        $view->getContent()->setTags($lessonTags);

        //bind student list
        $view->getContent()->setStudents($studentArray);

        //bind start date
        $view->getContent()->setDate($date);

        //bind start time
        $view->getContent()->setStart($startTime);

        //bind endTime
        $view->getContent()->setEnd($endTime);

        $view->render();
    }

    public function eventAction()
    {
        if(!empty($_POST)) {

            $user = Bootstrap::getModel('incubate/user');

            $lessonName = $_POST['lesson_name'];
			$tags = $_POST['tags'];
            $description = $_POST['description'];
            $studentList = $_POST['student_list'];
            $startTime = $_POST['start_time'];
			$date = $_POST['date'];

			//get lesson data from lesson name
			$lessonData = $user->get('lesson', array('name', '=', $lessonName));

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

            $calendar->setEvent($lessonName, $description, $startDateTime, $endDateTime, $studentEmailArray);

            $this->redirect('Incubate', 'Schedule', 'indexAction');
        }
    }

	public function lessonAction($lessonId)
	{
		if(isset($lessonId)) {

			$view = $this->loadLayout();
			$user = new Incubate_Model_User();

			if($lessonData = $user->get('lesson', array('lesson_id','=', $lessonId))) {

				$lessonTagMap = $user->getTagLessonMapFromLessonId($lessonData->lesson_id);

				//for eaach tag in the map, get the specific tag names from the tag table
				foreach ($lessonTagMap as $mapValue) {
					$lessonTags[] = $user->getTagsFromTagTableByTagId($mapValue->tag_id);
				}

				//for each student whos group is tagged, add themt o the list of recommended students to take
				$studentInviteList = $user->addStudentsToInviteListIfTaggedAndNotTaken($lessonData);

				$view->getContent()->setStudents($studentInviteList);
				$view->getContent()->setTags($lessonTags);
				$view->getContent()->setName($lessonData->name);
				$view->getContent()->setDescription($lessonData->description);

			}
			$view->render();
		}
	}
}