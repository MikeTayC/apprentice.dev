<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/31/15
 * Time: 3:03 PM
 */
class Incubate_Controller_User extends Incubate_Controller_Abstract
{
    protected function _isLoggedIn(){
        if (!Core_Model_Session::get('logged_in') || !Core_Model_Session::get('admin_status')) {
            Core_Model_Session::dangerFlash('error', 'You cannot go there');
            $this->redirect('Incubate', 'Login', 'indexAction');
            //@todo: Allow redirect function to handle below:
            // $this->redirect('*', 'Login'); OR
            // $this->redirect('module', 'Login', 'IndexAction');
            exit;
        }
    }

    protected function _checkAdminStatus(){
        if(!Core_Model_Session::get('admin_status')) {

            Core_Model_Session::dangerflash('error', 'Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }
    }

    public function indexAction()
    {
            $this->checkIfUserIsLoggedIn();

            /*
             * instantiate user  model using boot strap factory,
             * string indicates which module and name of model to insantiate
             */
            $user = Bootstrap::getModel('incubate/user');
			$lesson = Bootstrap::getModel('incubate/lesson');

            //gets all students in user table
            $allUsers = $user->getAllStudents();


            $completedCourses = array();
			if(isset($allUsers)) {
            	foreach ($allUsers as $users) {
                	$courseCount = $user->getCompletedCourseCount($users['id']);
                	$completedCourses[$users['name']] = $courseCount;
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

    public function profileAction($userId)
    {
        $this->checkIfUserIsLoggedIn();

        if(Core_Model_Session::get('admin_status') || Core_Model_Session::get('user_id') == $userId) {
            $view = $this->loadLayout();

            //        $user = Bootstrap::getModel('incubate/user');

            $lesson = Bootstrap::getModel('incubate/lesson');

            //if user id is set
            if ($userId) {

                $user = Bootstrap::getModel('incubate/user')->load($userId);
                //use user id to get specific user data

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
                if ($totalCourseCount != 0) {
                    $percentageCoursesTaken = round($completedCourseCount / $totalCourseCount * 100);
                } else {
                    $percentageCoursesTaken = 0;
                }
                //binds data to view
                $view->getContent()
                    ->setData('completed_courses', $userCompletedCourses)
                    ->setData('percentage_taken', $percentageCoursesTaken)
                    ->setData('userData', $user)
                    ->setData('lesson_data', $lessonData);
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
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if ($userId && $lessonId) {

            try {
                Bootstrap::getModel('incubate/user')->load($userId)->markCourseIncomplete($lessonId);

            } catch (Exception $e) {
                Core_Model_Session::dangerFlash('error', 'Could  not mark incomplete');
            }

        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
    }

    public function addAction($userId, $lessonId)
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if ($userId && $lessonId) {

            try {
                Bootstrap::getModel('incubate/user')->load($userId)->markCourseComplete($lessonId);
            } catch (Exception $e) {
                Core_Model_Session::dangerFlash('error', 'Could not mark Complete');
            }

        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
        exit;
    }

	public function removeAction($userId)
	{
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

		if(!empty($userId)) {

			$user = Bootstrap::getModel('incubate/user')->load($userId);

			$user->deleteCompletedCourseMap();

            $user->delete();

			Core_Model_Session::successFlash('message', 'User successfully removed');
			$this->headerRedirect('incubate','user','index');
			exit;
		}
		else {
			Core_Model_Session::dangerFlash('error', 'You did not specify a user to remove');
			$this->headerRedirect('incubate','index','index');
			exit;
		}
	}

	public function adminAction($userId)
	{

        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

		if(!empty($userId)) {

			Bootstrap::getModel()->load($userId)->setRole('admin')->save();

			Core_Model_Session::successFlash('message', 'Successfully made this user an admin');
			$this->headerRedirect('incubate','user','index');

		}
		else {
			Core_Model_Session::dangerFlash('error', 'You did not specify a user to remove');
			$this->headerRedirect('incubate','index','index');
			exit;
		}
	}

}