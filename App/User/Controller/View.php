<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class User_Controller_View extends Incubate_Controller_Abstract
{
	public function studentAction()
	{
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();
		$this->_flashCheck();
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
		$this->_checkIfUserIsLoggedIn();
		$this->_idCheck($userId, 'user');
		$this->userProfileCheck($userId);
		$this->_flashCheck();

		$lesson = Bootstrap::getModel('incubate/lesson');
		$totalLessonCount = $lesson->getTotalCount();
		$allLessonData = $lesson->loadAll();
		$user = Bootstrap::getModel('incubate/user')->load($userId)->setUserProgress($totalLessonCount)->setUserIncubationTime()->getAllUserCompletedCourseId();
		$user->getId();
		$userTagArray = Bootstrap::getModel('incubate/userTagMap')->loadUserTags($userId);
		$tagNames = Bootstrap::getModel('incubate/tag')->getTagNamesFromTagMap($userTagArray);

		$view = $this->loadLayout();
		$view->getContent()->setData('userData', $user)->setData('lessonData', $allLessonData)->setTags($tagNames);
		$view->render();
	}

	public function adminAction()
	{
		$this->_checkIfUserIsLoggedIn();
		$this->_checkIfUserIsAdmin();

		$allAdmins = Bootstrap::getModel('incubate/user')->loadAllAdmins();

		$view = $this->loadLayout();
		$view->getContent()->setAdmins($allAdmins);
		$view->render();
	}
}