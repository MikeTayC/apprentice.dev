<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Tag_Controller_Edit extends Incubate_Controller_Admin
{

	public function saveAction()
	{
		$request = $this->_getRequest();
		if ($request->isPost()) {

			$tagId = $this->_sessionGet('tag_id');

			$tagNewName = $request->getPost('tag');

			Bootstrap::getModel('tag/tag')->load($tagId)->setName($tagNewName)->save();

			$this->_sessionDelete('tag_id');
			$this->_successFlash('Successfully updated');
			$this->_thisModuleRedirect('view');
		} else {

			//all else fails, send back to index
			$this->_dangerFlash('You did not specify a tag to edit');
			$this->_thisModuleRedirect('view');
		}
	}
}