<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class User_Controller_Edit extends Incubate_Controller_Admin
{
	public function adminAction($userId)
	{
		$this->_idCheck($userId, 'user');

		Bootstrap::getModel('user/model')->load($userId)->setRole('admin')->save();

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