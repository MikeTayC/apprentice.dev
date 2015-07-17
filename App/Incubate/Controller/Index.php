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

        //check redirect6 code,if its set, get access token
        $this->checkRedirectCode();

        //check if access token is set
        $this->checkAccessTokenSet();


        if($this->googleClient->getAccessToken()) {
            $view->getContent()->setMe($this->googlePlus->people->get('me'));

            $optParams = array('maxResults' => 100);

            $view->getContent()->setActivities($this->googlePlus->activities->listActivities('me', 'public', $optParams));

            $_SESSION['access_token'] = $this->googleClient->getAccessToken();
        }
        else {
            $view->getContent()->setAuthurl($this->googleClient->createAuthUrl());
        }
        $view->render();
    }
}