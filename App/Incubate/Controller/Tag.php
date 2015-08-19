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
        $this->checkIfUserIsLoggedIn();

        //redirect if not Admin
        $this->checkIfUserIsAdmin();

        //load view
        $view = $this->loadLayout();

        //echo flashes if set
        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');

        //load model
        $tagData = Bootstrap::getModel('incubate/tag')->getAllBasedOnGivenFields(array('id', '>', '3'));

        //if lesson data is properly retrieved from database and available, bind data to views content block
        if($tagData) {
            $view->getContent()->setTag($tagData);
        }

        $view->render();
    }

    public function editAction($tagId)
    {
        $view = $this->loadLayout();

        $tag = Bootstrap::getModel('incubate/tag');

        if (!empty($_POST)) {

            $tagId = Core_Model_Session::get('tag_id');

            $tagNewName = $_POST['tag'];


            $tag->load($tagId)->setName($tagNewName)->save();

            Core_Model_Session::delete('tag_id');
            Core_Model_Session::successFlash('message', 'Successfully updated');
            $this->headerRedirect('incubate','tag','index');
            exit;
        }
        elseif(isset($tagId)) {

            //load tag name from id
            $tagName = Bootstrap::getModel('incubate/tag')->load($tagId)->getName();

            //set id in session
            Core_Model_Session::set('id', $tagId);

            $view->getContent()->setTag($tagName);
            $view->render();
        }
        else {

            //all else fails, send back to index
            Core_Model_Session::dangerFlash('error', 'You did not specify a tag to edit');
            $this->headerRedirect('incubate','tag','index');
            exit;
        }
    }

    public function deleteAction($tagId)
    {
        if(!empty($tagId)){

            $tag = Bootstrap::getModel('incubate/tag');


                //delete current tag map of lesson
                $tag->deleteTagMapOfLessonBasedOnTagId($tagId);

                //delete this lesson
                $tag->delete($tagId);

                Core_Model_Session::successFlash('message', 'Successfully deleted');
        }

        $this->headerRedirect('incubate','tag','index');
        exit;
    }
}