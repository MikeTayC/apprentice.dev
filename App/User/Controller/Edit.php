<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 *
 * Controller handles updating of user information information
 *
 * Extends Abstract admin class to ensure only admins can access
 **/
class User_Controller_Edit extends Incubate_Controller_Admin
{
    /**
     * Handles making a user an admin
     *
     * @param $userId : userid of user to turn into admin
     **/
	public function adminAction($userId)
	{
        //check
		$this->_idCheck($userId, 'user');

        /**
         * loads user, sets role to admin and saves,
         * dispatched events will
         **/
		Bootstrap::getModel('user/model')->load($userId)->makeUserAdmin();

		$this->_successFlash('Successfully made this user an admin');
		$this->_thisModuleRedirect('view');
	}

    public function unmarkAction($userId, $lessonId)
    {
        $this->_idCheck($lessonId, 'lesson');
        $this->_idCheck($userId, 'user');

        $event = Bootstrap::getModel('core/event')->setUser($userId)->setLesson($lessonId);
        Bootstrap::dispatchEvent('unmark_completed_course', $event);

        $this->_thisModuleRedirectParams('view','profile', $userId);


    }
    public function markAction($userId, $lessonId)
    {
        $this->_idCheck($lessonId, 'lesson');
        $this->_idCheck($userId, 'user');

        $event = Bootstrap::getModel('core/event')->setUser($userId)->setLesson($lessonId);
        Bootstrap::dispatchEvent('mark_completed_course', $event);

        $this->_thisModuleRedirectParams('view','profile', $userId);
    }

    public function tagAction($userId)
    {
        $this->_idCheck($userId, 'user');

        $request = $this->_getRequest();
        if($request->isPost()) {

            $tags = $this->explode($request->getPost('tags'));

            //TODO dispatch from user model
            $event = Bootstrap::getModel('core/event')->setId($userId)->setTags($tags);
            Bootstrap::dispatchEvent('user_edit_tags_after', $event);

            $this->_successFlash('You changed this users tags!');
            $this->_thisModuleRedirectParams('view', 'profile', $userId);
        }
    }
}