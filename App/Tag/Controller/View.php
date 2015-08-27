<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:28 AM
 *
 * Controller mainly responsible for loading all the tags for viewing
 **/
class Tag_Controller_View extends Incubate_Controller_Admin
{

	public function indexAction()
	{
        //load view
        $view = $this->loadLayout();

        //echo flashes if set
        $this->_flashCheck();

        //load model and tags, except first three, AE,FE, QA tags cannot be edited
        $tagData = Bootstrap::getModel('tag/model')->LoadAllEditableTags();

        //if lesson data is properly retrieved from database and available, bind data to views content block
        $view->getContent()->setTag($tagData);

        $view->render();
	}
}