<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/31/15
 * Time: 3:03 PM
 */
class Incubate_Controller_User extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (!Core_Model_Session::get('logged_in') || !Core_Model_Session::get('admin_status')) {
            Core_Model_Session::dangerFlash('error', 'You cannot go there');
            $this->redirect('Incubate', 'Login', 'indexAction');
        } else {

            /*
             * instantiate user  model using boot strap factory,
             * string indicates which module and name of model to insantiate
             */
            $user = Bootstrap::getModel('incubate/user');
			$lesson = Bootstrap::getModel('incubate/lesson');
            //gets all users in user table
            $allUsers = $user->getAllBasedOnGivenFields(array('role', '=' ,'student'));
            //$user->get('user', array('1', '=', '1'));

            $completedCourses = array();
			if(isset($allUsers)) {
            	foreach ($allUsers as $users) {
                	$courseCount = $user->getCompletedCourseCount($users->user_id);
                	$completedCourses[$users->name] = $courseCount;
            	}
			}
            $totalLessonCount = $lesson->getTotalCount();

            /*
             * load layout,
             * set data on the content block
             * render
             */
            $view = $this->loadLayout();
            $view->getContent()->setData('userData', $allUsers);
            $view->getContent()->setData('userCompletedCourses', $completedCourses);
            $view->getContent()->setData('totalLessonCount', $totalLessonCount);
            $view->render();
        }
    }

    public function profileAction($userId)
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }

        if(Core_Model_Session::get('admin_status') || Core_Model_Session::get('user_id') == $userId) {
			$view = $this->loadLayout();

	//        $user = Bootstrap::getModel('incubate/user');
			$user = new Incubate_Model_User();
			$lesson = Bootstrap::getModel('incubate/lesson');

			//if user id is set
			if ($userId) {

				//use user id to get specific user data
				if ($userData = $user->get(array('user_id', '=', $userId))) {

					//retrieve all lesson data
					$lessonData = $lesson->getAll();

					/*
					 * get all of the users completed course ids
					 * we will need them to tell which courses are compeleted when rendering
					 */
					$userCompletedCourses = $user->getAllUserCompletedCourseId($userId);

					//gives us a count of tthe total amount of users completed courses
					$completedCourseCount = count($userCompletedCourses);

					//gives count of all courses in databases
					$totalCourseCount = count($lessonData);

					//user percentage of completed course
					if($totalCourseCount != 0) {
						$percentageCoursesTaken = round($completedCourseCount / $totalCourseCount * 100);
					} else {
						$percentageCoursesTaken = 0;
					}
					//binds data to view
					$view->getContent()->setData('completed_courses', $userCompletedCourses);
					$view->getContent()->setData('percentage_taken', $percentageCoursesTaken);
					$view->getContent()->setData('userData', $userData);
					$view->getContent()->setData('lesson_data', $lessonData);
				}

			}
			$view->render();
		}
		else {
			Core_Model_Session::dangerFlash('error', 'Admins only');
			$this->headerRedirect('incubate','index','index');
			exit;
		}
    }

    public function deleteAction($userId, $lessonId)
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

        if ($userId && $lessonId) {
            $user = new Incubate_Model_User();

            try {
                $user->markCourseIncomplete($userId, $lessonId);
            } catch (Exception $e) {
                Core_Model_Session::flash('error', '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>Database Connection, could not mark complete!</p></div>');
            }

        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
    }

    public function addAction($userId, $lessonId)
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

        if ($userId && $lessonId) {
            $user = new Incubate_Model_User();

            try {
                $user->markCourseComplete($userId, $lessonId);
            } catch (Exception $e) {
                Core_Model_Session::flash('error', '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>Database Connection, could not mark complete!</p></div>');
            }

        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
    }

	public function removeAction($userId)
	{
		if(!Core_Model_Session::get('admin_status')) {
			Core_Model_Session::dangerFlash('error', 'Admins only');
			$this->headerRedirect('incubate','index','index');
			exit;
		}

		if(!empty($userId)) {
			$user = new Incubate_Model_User();

			if($userData = $user->get(array('user_id', '=', $userId))){
				$user->deleteCompletedCourseMap($userId);
				$user->deleteThisUser($userId);
				Core_Model_Session::successFlash('message', 'User successfully removed');
				$this->headerRedirect('incubate','user','index');
				exit;
			}
		}
		else {
			Core_Model_Session::dangerFlash('error', 'You did not specify a user to remove');
			$this->headerRedirect('incubate','index','index');
			exit;
		}
	}

	public function adminAction($userId)
	{
		if(!Core_Model_Session::get('admin_status')) {
			Core_Model_Session::dangerFlash('error', 'Admins only');
			$this->headerRedirect('incubate','index','index');
			exit;
		}

		if(!empty($userId)) {
			$user = new Incubate_Model_User();

			if($userData = $user->get(array('user_id', '=', $userId))){
				$user->loadUser($userId)->makeUserAdmin();
				Core_Model_Session::successFlash('message', 'Successfully made this user an admin');
				$this->headerRedirect('incubate','index','index');
				exit;
			}
		}
		else {
			Core_Model_Session::dangerFlash('error', 'You did not specify a user to remove');
			$this->headerRedirect('incubate','index','index');
			exit;
		}
	}

}