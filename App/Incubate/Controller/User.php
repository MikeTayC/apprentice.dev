<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/31/15
 * Time: 3:03 PM
 */
class Incubate_Controller_User extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (!Core_Model_Session::get('logged_in') || !Core_Model_Session::get('admin_status')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        } else {

            /*
             * instantiate user  model using boot strap factory,
             * string indicates which module and name of model to insantiate
             */
            $user = Bootstrap::getModel('incubate/user');

            //gets all users in user table
            $allUsers = $user->getAllUserDataFromUserTable();
                //$user->get('user', array('1', '=', '1'));

            $completedCourses = array();

            foreach($allUsers as $key => $users)
            {
                $courseCount = $user->getCount('completed_courses', array('user_id', '=', $users->user_id));
                $completedCourses[$users->name] = $courseCount;
            }

            $totalLessonCount = $user->getCount('lesson', array('1', '=', '1'));

            /*
             * load layout,
             * set data on the content block
             * render
             */
            $view = $this->loadLayout();
            $view->getContent()->setData('userData', $allUsers);
            $view->getContent()->setData('userCompletedCourses', $completedCourses);
            $view->getContent()->setData('totalLessonCount', $totalLessonCount);
            $view->render();
        }
    }

    public function profileAction($userId)
    {
        $view = $this->loadLayout();

//        $user = Bootstrap::getModel('incubate/user');
        $user = new Incubate_Model_User();
        if($userId) {

            if($userData = $user->get('user', array('user_id', '=', $userId))) {

                $lessonData = $user->getAllLessonsFromLessonTable();

                $userCompletedCourses = $user->getAllUserCompletedCourses($userId);

                $completedCourseCount = count($userCompletedCourses);

                $totalCourseCount = count($lessonData);

                $percentageCoursesTaken = $completedCourseCount / $totalCourseCount * 100;

                $view->getContent()->setData('completed_courses', $userCompletedCourses);

                $view->getContent()->setData('percentage_taken', $percentageCoursesTaken);

                $view->getContent()->setData('userData', $userData);

                $view->getContent()->setData('lesson_data', $lessonData);
            }

        }


        $view->render();
    }
}