<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Core_Controller_Abstract
{
    public function indexAction($param)
    {
        if(!Core_Model_Session::get('logged_in')) {
            $this->headerRedirect('Incubate', 'Login', 'index');
            exit;
        }

        if($param  && Core_Model_Session::get('admin_status')) {
            $param = strtolower($param);

            $this->redirect('Incubate', 'Schedule', $param . 'Action');
        }
        else {
            $this->loadLayout();
            $this->render();
        }
    }

    public function lessonAction()
    {
        if(!empty($_POST) && isset($_POST['lesson_name'])) {

            //store post in local variable
            $lessonName = $_POST['lesson_name'];

            //load model
            $user = Bootstrap::getModel('incubate/user');

            //redirect if lesson is not in database
            if(!$user->get('lesson', array('name', '=', $lessonName))){
                $this->headerRedirect('incubate', 'schedule', 'index');
                exit;
            }

            //store lesson name in session so we can tell which info to call
            Core_Model_Session::set('lesson_name', $lessonName);

            //redirect to next stage in scheduling, students invite list
            $this->redirect('Incubate', 'Schedule', 'studentAction');
        }
        else {
            $this->loadLayout();
            $this->render();
        }
    }

    public function createAction()
    {
        if(!empty($_POST)) {

        }
        $client = new Google_Client();

        $calendar = new Core_Model_Calendar($client);

        $calendar->setEvent('first event', 'this is a test', '2015-08-07T09:00:00-07:00', '2015-08-07T17:00:00-07:00');
    }

    public function studentAction()
    {
        //load view
        $view = $this->loadLayout();

        //load model
        $user = Bootstrap::getModel('incubate/user');

        //check post is not empty and set
        if(!empty($_POST) && isset($_POST['student_list'])) {
        
        }
        else {

            //store session avariable with lesson name in local variable;
            $lessonName = Core_Model_Session::get('lesson_name');

            //redirect if lesson is not in database, check again
            if(!$lessonData = $user->get('lesson', array('name', '=', $lessonName))){
                $this->headerRedirect('incubate', 'schedule', 'index');
                exit;
            }

            /*
             * Check which students should be invited, based on group tag and if they have yet to take
             * the course yet.
             */
            $studentInviteList = $user->addStudentsToInviteListIfTaggedAndNotTaken($lessonData);

            $view->getContent()->setStudents($studentInviteList);

            $view->render();
        }
    }
}