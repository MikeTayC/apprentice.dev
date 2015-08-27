<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 *
 * Controller which handles deleting of tags
 **/
class Tag_Controller_Delete extends Incubate_Controller_Admin
{
	public function idAction($tagId)
	{
        /** Checks to ensure tag exists */
        $this->_idCheck($tagId,'tag');

        /** Delete this lesson */
        Bootstrap::getModel('tag/model')->load($tagId)->delete();

        /** flash success */
        $this->_successFlash('Successfully deleted');
        $this->_thisModuleRedirect('view');
	}
}