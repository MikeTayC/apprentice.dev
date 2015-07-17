<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 10:26 AM
 */
class Incubate_Controller_Index extends Core_Controller_Authorization
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        //check if user asked to log out, then logout

        //check redirect code,if its set, get access token
        $this->checkRedirectCode();

        //check if access token is set
        $this->checkAccessTokenSet();


        if($this->googleClient->getAccessToken()) {
            $googleId = $this->googlePlus->people->get('me')->getId();
            $user = Core_Model_Database::getInstance()->get('user', array ('google_id', '=', $googleId))->first();

            Core_Helpers_Session::set('user', $user);

            $_SESSION['access_token'] = $this->googleClient->getAccessToken();

            if($user->permission == 3) {
                $this->redirect('Incubate', 'Admin', 'indexAction');
            }
            elseif($user->permission == 2) {
                $this->redirect('Incubate', 'Teacher', 'indexAction');
            }
            else {
                $this->redirect('Incubate', 'Student', 'indexAction');
            }
        }
        else {
            $view->getContent()->setAuthurl($this->googleClient->createAuthUrl());
        }
    }
}