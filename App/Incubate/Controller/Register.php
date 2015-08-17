<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/14/15
 * Time: 4:37 PM
 */
class Incubate_Controller_Register extends Core_Controller_Abstract
{
    public function indexAction($email, $googleDisplayName)
    {
        $view = $this->loadLayout($default = false);
		$view->getContent()->setName($googleDisplayName);
		$view->getContent()->setEmail($email);

        $view->render();
    }

    public function newAction()
    {
        if(!empty($_POST)){
            $user = Bootstrap::getModel('incubate/user');

			$name = $_POST['name'];
			$email = $_POST['email'];
			$group = $_POST['group'];
			$googleId = Core_Model_Session::get('google_id');

			$user->createUser($name, $email, $group, $googleId);

			if($user->checkUserDataForGoogleId($googleId)) {
				Core_Model_Session::successflash('message', 'You have been successfully added to Incubate!');
			}
			else {
				Core_Model_Session::dangerFlash('error', 'There was a problem adding you to Incubate!');
				$this->headerRedirect('incubate', 'logout', 'index');
				exit;
			}
		}
		$this->headerRedirect('incubate', 'index', 'index');
    }
}