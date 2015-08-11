<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/5/15
 * Time: 9:43 AM
 */

class Incubate_Controller_Schedule extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (!Core_Model_Session::get('logged_in')) {
            $this->redirect('Incubate', 'Login', 'indexAction');
        } else {
            $view = $this->loadLayout();
            $view->render();
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
}