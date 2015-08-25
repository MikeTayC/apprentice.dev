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


        $user = Bootstrap::getModel('incubate/user')->load($userId)->loadProfile();

        $view = $this->loadLayout();
        $view->getContent()->setData('userData', $user);
        $view->render();

    }

    public function unmarkAction($userId, $lessonId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        $this->_idCheck($lessonId, 'lesson');
        $this->_idCheck($userId, 'user');

        $event = Bootstrap::getModel('core/event')->setUser($userId)->setLesson($lessonId);
        Bootstrap::dispatchEvent('unmark_completed_course', $event);
        $this->_thisModuleRedirectParams('user','profile', $userId);


    }
    public function markAction($userId, $lessonId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        $this->_idCheck($lessonId, 'lesson');
        $this->_idCheck($userId, 'user');

        $event = Bootstrap::getModel('core/event')->setUser($userId)->setLesson($lessonId);
        Bootstrap::dispatchEvent('mark_completed_course', $event);


        $this->_thisModuleRedirectParams('user','profile', $userId);
    }

	public function removeAction($userId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        $this->_idCheck($userId, 'user');

       Bootstrap::getModel('incubate/user')->load($userId)->delete();


        $this->_successFlash('User successfully removed');
        $this->_thisModuleRedirect('user');
    }


	public function adminAction($userId)
	{

        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();
        $this->_idCheck($userId, 'user');


        Bootstrap::getModel('incubate/user')->load($userId)->makeUserAdmin()->saveNoLoad();

        $this->_successFlash('Successfully made this user an admin');
        $this->_thisModuleRedirect('user');
}

    public function tagAction($userId)
    {
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsLoggedIn();
        $this->_idCheck($userId, 'user');

        $request = $this->_getRequest();

        if($request->isPost()) {

            $tags = $this->explode($request->getPost('tags'));
            $event = Bootstrap::getModel('core/event')->setId($userId)->setTags($tags);
            Bootstrap::dispatchEvent('edit_user_tags', $event);

            $this->_successFlash('You changed this users tags!');
            $this->_thisModuleRedirectParams('user', 'profile', $userId);
        }
    }

}