<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Incubate_Controller_Abstract
{
    public function indexAction()
    {
        $this->_checkIfUserIsLoggedIn();
        $this->loadLayout();
        $this->_flashCheck();
        $this->render();

	}

    public function eventAction()
    {

        $request = $this->_getRequest();
        $this->_checkIfUserIsLoggedIn();
        $this->_checkIfUserIsAdmin();

        if($request->isPost()) {

            $lessonId = $this->_sessionGet('lessonId');
			$lesson  = Bootstrap::getModel('incubate/lesson')->load($lessonId);

            foreach(array('tags','description','student_list', 'start_time','date') as $field) {
                $lesson->setData($field, $request->getPost($field));
            }

            $lesson->loadEvent();
            $lesson->fireEvent();
            $lesson->afterEvent();
            

            $this->_successFlash('Your event has been scheduled');
            $this->_thisModuleRedirect('schedule');
        }
    }

	public function lessonAction($lessonId)
	{
        $this->_checkIfUserIsLoggedIn();

        $this->_checkIfUserIsAdmin();

        $this->_idCheck($lessonId, 'lesson');

        $view = $this->loadLayout();

        /** @var Incubate_Model_Lesson $lesson */
        $lesson = Bootstrap::getModel('incubate/lesson')->load($lessonId);

        $this->_sessionSet('lessonId', $lessonId);

        $view->getContent()->setLesson($lesson);

		$view->render();
	}
}