<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 *
 * Controller handles creating and saving new tags to the database
 **/
class Tag_Controller_Create extends Incubate_Controller_Admin
{

	public function saveAction()
	{
        /** @var instance of request object $request */
		$request = $this->_getRequest();

		/**
         * $request checks if post variable is set, and specifically th epsot['tags'] variable
 		 * if post is set, add the created tag to the database
         *
         * redirects if both false
 		 **/
		if($request->isPost() && $request->hasPost('tags')) {

            /** @var  $tagArray explodes the post variable 'tags', which is a comma separated list,  */
			$tagArray = $this->explode($request->getPost('tags'));

            /** function adds the new tags to the database */
			Bootstrap::getModel('tag/model')->addNewTagsToDb($tagArray);

            /** flashes messages and redirects */
			$this->_successFlash('You made tag(s)');
		}
        else {
            $this->_dangerFlash('Something went wrong!');
        }
        $this->_thisModuleRedirect('view');
	}
}