<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Tag_Controller_Delete extends Incubate_Controller_Admin
{
	public function idAction($tagId)
	{
        if(isset($tagId)){
            $this->_idCheck($tagId,'tag');

            //delete this lesson
            Bootstrap::getModel('tag/tag')->load($tagId)->delete();

            $this->_successFlash('Successfully deleted');
        }

        $this->_thisModuleRedirect('view');
	}
}