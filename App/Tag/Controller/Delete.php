<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 */
class Tag_Controller_Delete extends Incubate_Controller_Abstract
{
	public function idAction($tagId)
	{
		$tag = Bootstrap::getModel('incubate/tag');
		if(isset($tagId) && $tag->check($tagId)){

			//dispatch event prioer to delteing tag, remove tag from lesson tag map
			$event = Bootstrap::getModel('core/event')->setTag($tagId);
			Bootstrap::dispatchEvent('delete_tag_before', $event);

			//delete this lesson
			$tag->load($tagId)->delete();

			$this->_successFlash('Successfully deleted');
		}

		$this->_thisModuleRedirect('tag');
	}
}