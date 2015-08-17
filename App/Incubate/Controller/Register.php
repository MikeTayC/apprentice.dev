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

            $user->create('user', array(
				'name' => $name,
				'email' => $email,
               	'groups' => $group,
				'google_id' => $googleId,
				'joined' => date('Y-m-d'),
				'role' => 'student'
            ));

			if($user->checkUserDataForGoogleId($googleId)) {
				Core_Model_Session::flash('message', '<div class="uk-alert uk-alert-success" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>You have been successfully added to Incubate!</p></div>');
			}
			else {
				Core_Model_Session::flash('error', '<div class="uk-alert uk-alert-danger" data-uk-alert=""><a class="uk-alert-close uk-close" href=""></a><p>There was a problem adding you to Incubate!</p></div>');
				$this->headerRedirect('incubate', 'logout', 'index');
				exit;
			}
		}
		$this->headerRedirect('incubate', 'index', 'index');
    }
}