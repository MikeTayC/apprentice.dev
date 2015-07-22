<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 10:26 AM
 */
class Incubate_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = $this->loadLayout();

        //check if user asked to log out, then logout
        $googleClient = new Google_Client();

        $auth = new Core_Helpers_GoogleAuth($googleClient);

        //check redirect code,if its set, get access token
        if($auth->checkRedirectCode()) {
            header('Location: http://apprentice.dev/incubate/index/index');
        }

        //ensure access token is set
        $auth->checkAccessTokenSet();

        //checks if usr is logged in
        if($googleClient->getAccessToken()) {

            $auth->setAllUserData();

            $user = $auth->getUserData();
            $view->getDefault()->setUser($user);

            $googleUser = $auth->getGoogleUserData();
            $view->getDefault()->setGoogle($googleUser);
        }
        else {
            $view->getDefault()->setTemplate('App/Incubate/View/Template/Landing.phtml');
            $view->setContent(null);
            $view->getDefault()->setAuthurl($googleClient->createAuthUrl());
        }
        $view->render();
    }

    public function logoutAction()
    {
        $googleClient = new Google_Client();
        $auth = new Core_Helpers_GoogleAuth($googleClient);
        $auth->logout();
        $this->redirect('Incubate', 'Index', 'indexAction');
    }
}