<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Tag_Controller_Edit extends Incubate_Controller_Abstract
{

	public function idAction($tagId)
	{

		$this->_checkIfUserIsAdmin();
		$this->_checkIfUserIsLoggedIn();

		$request = $this->_getRequest();

		$view = $this->loadLayout();


		$tag = Bootstrap::getModel('incubate/tag');
		if ($request->isPost()) {

			$tagId = $this->_sessionGet('tag_id');

			$tagNewName = $request->getPost('tag');

			$tag->load($tagId)->setName($tagNewName)->save();

			$this->_sessionDelete('tag_id');
			$this->_successFlash('Successfully updated');
			$this->_thisModuleRedirect('tag');
		}
		elseif($this->_idCheck($tagId, 'tag')) {

			//load tag name from id
			$tagName = Bootstrap::getModel('incubate/tag')->load($tagId)->getName();

			//set id in session
			$this->_sessionSet('tag_id', $tagId);

			$view->getContent()->setTag($tagName);

			$view->render();
		}
		else {

			//all else fails, send back to index
			$this->_dangerFlash('You did not specify a tag to edit');
			$this->headerRedirect('incubate','tag','index');
			$this->_thisModuleRedirect('tag');
		}
	}
}