<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/18/15
 * Time: 9:15 AM
 */
class Incubate_Controller_Tag extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        //redirect if not logged in
        $this->_checkIfUserIsLoggedIn();

        //redirect if not Admin
        $this->_checkIfUserIsAdmin();

        //load view
        $view = $this->loadLayout();

        //echo flashes if set
        $this->_flashCheck();

        //load model
        $tagData = Bootstrap::getModel('incubate/tag')->LoadAllEditableTags();

        //if lesson data is properly retrieved from database and available, bind data to views content block
		$view->getContent()->setTag($tagData);

        $view->render();
    }

    public function editAction($tagId)
    {
        $this->_checkIfUserIsAdmin();
        $this->_checkIfUserIsLoggedIn();
        $request = $this->_getRequest();

        $view = $this->loadLayout();
        $this->_flashCheck();

        $tag = Bootstrap::getModel('incubate/tag');

        if ($request->isPost()) {

            $tagId = $this->_sessionGet('tag_id');

            $tagNewName = $request->getPost('tag');

            $tag->load($tagId)->setName($tagNewName)->save();

            $this->_sessionDelete('tag_id');
            $this->_successFlash('Successfully updated');
            $this->headerRedirect('incubate','tag','index');
            exit;
        }
        elseif(isset($tagId)) {

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
            exit;
        }
    }

    public function deleteAction($tagId)
    {
        if(!empty($tagId)){

            $tag = Bootstrap::getModel('incubate/tag');


            //dispatch event prioer to delteing tag, remove tag from lesson tag map
            $event = Bootstrap::getModel('core/event')->setTag($tagId);
            Bootstrap::dispatchEvent('delete_tag_before', $event);

            //delete this lesson
            $tag->load($tagId)->delete();

            $this->_successFlash('Successfully deleted');
        }

        $this->headerRedirect('incubate','tag','index');
        exit;
    }
}