<?php

class Core_Controller_Index extends Core_Controller_Authorization
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        $googleClient = new Google_Client();
//        $auth = new Core_Helpers_GoogleAuth($googleClient);

        //check if the user has requested to clear login information,
        $googleClient->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
        $googleClient->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
        $googleClient->setRedirectUri('http://apprentice.dev/core/index/index');
        $googleClient->setScopes(array('https://www.googleapis.com/auth/plus.me'));

        $plus = new Google_Service_Plus($googleClient);

        if(isset($_REQUEST['logout'])) {
            unset($_SESSION['access_token']);
        }

        if(isset($_GET['code'])){
            $googleClient->authenticate($_GET['code']);
            $_SESSION['access_token'] = $googleClient->getAccessToken();
            header('Location: http://apprentice.dev/core/index/index');
        }

        if(isset($_SESSION['access_token'])) {
            $googleClient->setAccessToken($_SESSION['access_token']);
        }

        if($googleClient->getAccessToken()) {
            $view->getContent()->setMe($plus->people->get('me'));

            $optParams = array('maxResults' => 100);

            $view->getContent()->setActivities($plus->activities->listActivities('me', 'public', $optParams));

            $_SESSION['access_token'] = $googleClient->getAccessToken();
        }
        else {
            $view->getContent()->setAuthurl($googleClient->createAuthUrl());
        }
        $view->render();
    }
}