<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/18/15
 * Time: 9:15 AM
 */
class Incubate_Controller_Tag extends Core_Controller_Abstract
{
    public function indexAction()
    {
        //redirect if not logged in
        if(!Core_Model_Session::get('logged_in')){
            Core_Model_Session::dangerFlash('error', 'You are not logged in!');
            $this->redirect('Incubate', 'Lesson', 'indexAction');
        }

        //redirect if not Admin
        if(!Core_Model_Session::get('admin_status')){
            Core_Model_Session::dangerFlash('error', 'You are not an admin!');
            $this->redirect('Incubate', 'Lesson', 'indexAction');
        }

        //load view
        $view = $this->loadLayout();
        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');

        //load model
        $tag = Bootstrap::getModel('incubate/tag');

        $tagData = $tag->getAllBasedOnGivenFields(array('tag_id', '>', '3'));

        //if lesson data is properly retrieved from database and available, bind data to views content block
        if($tagData) {
            $view->getContent()->setTag($tagData);
        }

        $view->render();
    }

    public function editAction($tagId)
    {
        $view = $this->loadLayout();

        $tag = new Incubate_Model_Tag();

        if (!empty($_POST)) {

            $tagId = Core_Model_Session::get('tag_id');

            $tagNewName = $_POST['tag'];

            if(!$tagData = $tag->get(array('tag_id', '=', $tagId))){
                Core_Model_Session::dangerFlash('error', 'This tag does not exist');
                $this->headerRedirect('incubate','tag', 'index');
                exit;
            }

            $tag->loadTag($tagId)->changeTagName($tagNewName)->saveUpdate();

            Core_Model_Session::delete('tag_id');
            Core_Model_Session::successFlash('message', 'Successfully updated');
            $this->headerRedirect('incubate','tag','index');
            exit;
        }
        elseif(isset($tagId)) {

            if(!$tagData = $tag->get(array('tag_id', '=', $tagId))) {
                Core_Model_Session::dangerFlash('error', 'This tag does not exist');
                $this->headerRedirect('incubate','tag', 'index');
                exit;
            }
            $tagName = $tagData->name;
            $tagId = $tagData->tag_id;

            Core_Model_Session::set('tag_id', $tagId);
            $view->getContent()->setTag($tagName);

            $view->render();
        }
        else {
            Core_Model_Session::dangerFlash('error', 'You did not specify a tag to edit');
            $this->headerRedirect('incubate','tag','index');
        }
    }

    public function deleteAction($tagId)
    {
        if(!empty($tagId)){

            $tag = Bootstrap::getModel('incubate/tag');
            $lesson = Bootstrap::getModel('incubate/lesson');

            if($tag->get(array('tag_id', '=', $tagId))) {

                //delete current tag map of lesson
                $tag->deleteTagMapOfLessonBasedOnTagId($tagId);


                //delete this lesson
                $tag->deleteThisTag($tagId);

                Core_Model_Session::successFlash('message', 'Successfully deleted');
            }
        }
        $this->headerRedirect('incubate','tag','index');
    }
}