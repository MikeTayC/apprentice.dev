<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 *
 * Controller responsible for editing tags
 *
 * Must be logged in admin to access
 **/
class Tag_Controller_Edit extends Incubate_Controller_Admin
{

	public function saveAction()
	{
        /** @var instance of request object4 $request */
		$request = $this->_getRequest();
		if ($request->isPost()) {
            Core_Model_Validator::check($_POST);
            if(!Core_Model_Validator::passed()) {
                $this->_dangerFlash('Your form was not valid, please try again!');
                $this->headerRedirect('tag','form', 'edit', $this->_sessionGet('tagId'));
            }

            /** @var $tagId tag id retrieved from session*/
			$tagId = $this->_sessionGet('tagId');

            /** @var  $tagNewName new tag name retrieved froom post data*/
			$tagNewName = $request->getPost('tags');

            /** Updates the new tag name into the database */
			Bootstrap::getModel('tag/model')->load($tagId)->setName($tagNewName)->save();

            /** Deletes the session and data nd flashes message */
			$this->_sessionDelete('tagId');
			$this->_successFlash('Successfully updated');
		} else {
            $this->_dangerFlash('Something went wrong!');
        }
        $this->_thisModuleRedirect('view');
	}
}