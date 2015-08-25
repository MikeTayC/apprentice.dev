<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:11 AM
 */
class Tag_Controller_Form extends Incubate_Controller_Admin
{
    public function editAction($tagId)
    {
        //load tag name from id
        $tagName = Bootstrap::getModel('incubate/tag')->load($tagId)->getName();

        //set id in session
        $this->_sessionSet('tag_id', $tagId);

        $view = $this->loadLayout();
        $view->getContent()->setTag($tagName);
        $view->render();
    }

    public function createAction()
    {
        $this->loadLayout();
        $this->render();
    }
}