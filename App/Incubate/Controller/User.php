<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/31/15
 * Time: 3:03 PM
 */
class Incubate_Controller_User extends Incubate_Controller_Abstract
{

    public function indexAction()
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        /*
         * instantiate user  model using boot strap factory,
         * string indicates which module and name of model to insantiate
         */
        $user = Bootstrap::getModel('incubate/user');

        $totalLessonCount = Bootstrap::getModel('incubate/lesson')->getTotalCount();

        //loads all students
        $allUsers = $user->loadAllStudents();

        //calculates and sets each user progress
        $allUsers = $user->setAllUserProgress($allUsers, $totalLessonCount);

        //calculates and sets each user incubation time
        $allUsers = $user->setAllUserIncubationTime($allUsers);

        /*
         * load layout,
         * set data on the content block
         * render
         */
        $view = $this->loadLayout();
        $view->getContent()->setData('userData', $allUsers);
        $view->render();
    }

    public function profileAction($userId)
    {
        $this->checkIfUserIsLoggedIn();
        $this->userProfileCheck($userId);

        $lesson = Bootstrap::getModel('incubate/lesson');
        $totalLessonCount = $lesson->getTotalCount();
        $allLessonData = $lesson->loadAll();

        $user = Bootstrap::getModel('incubate/user')->load($userId)->setUserProgress($totalLessonCount)->setUserIncubationTime()->getAllUserCompletedCourseId();

        $view = $this->loadLayout();
        $view->getContent()
                ->setData('userData', $user)
                ->setData('lessonData', $allLessonData);
        $view->render();

    }

    public function deleteAction($userId, $lessonId)
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if ($userId && $lessonId) {
            Bootstrap::getModel('incubate/user')->load($userId)->markCourseIncomplete($lessonId);
        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
    }

    public function addAction($userId, $lessonId)
    {
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

        if ($userId && $lessonId) {

            Bootstrap::getModel('incubate/user')->load($userId)->markCourseComplete($lessonId);

        }
        $this->headerRedirect('incubate', 'user', 'profile', $userId);
        exit;
    }

	public function removeAction($userId)
	{
        $this->checkIfUserIsLoggedIn();
        $this->checkIfUserIsAdmin();

		if(!empty($userId)) {

			Bootstrap::getModel('incubate/user')->load($userId)->deleteCompletedCourseMap()->delete();

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

			Bootstrap::getModel('incubate/user')->load($userId)->setRole('admin')->save();

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