<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 */
class Tag_Controller_Create extends Incubate_Controller_Admin
{

	public function saveAction()
	{
		$request = $this->_getRequest();

		/*
 		 * if post is set, add the created tag to the database
 		 */
		if($request->isPost() && $request->hasPost('tags')) {

			$tagArray = $this->explode($request->getPost('tags'));

			Bootstrap::getModel('tag/tag')->addNewTagsToDb($tagArray);

			$this->_successFlash('You made tag(s)');
			$this->_thisModuleRedirect('view');
		}
	}
}