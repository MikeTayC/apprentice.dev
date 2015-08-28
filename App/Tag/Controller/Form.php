<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:11 AM
 *
 * Controller responsible for creating form data
 *
 * Must be admin
 **/
class Tag_Controller_Form extends Incubate_Controller_Admin
{
    /**
     * Produces editing form
     *
     * gathers data necessary for editing
     * @param $tagId
     */
    public function editAction($tagId)
    {
        $this->_idCheck($tagId,'tag');

        //load tag name from id
        $tagName = Bootstrap::getModel('tag/model')->load($tagId)->getName();

        //set id in session
        $this->_sessionSet('tagId', $tagId);

        /** @var loads layour and binds tag name to view $view */
        $view = $this->loadLayout();
        $this->_flashCheck();
        $view->getContent()->setTag($tagName);
        $view->render();
    }

    /**
     * Creates form for creating tags
     */
    public function createAction()
    {
        $this->loadLayout();
        $this->_flashCheck();
        $this->render();
    }
}