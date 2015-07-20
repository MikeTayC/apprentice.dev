<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/14/15
 * Time: 1:21 PM
 */
class Core_Helpers_GoogleAuth
{
//    protected $_client;
//    public function __construct(Google_Client $googleClient = null)
//    {
//        $this->_client = $googleClient;
//
//        if($this->_client) {
//            $this->_client->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
//            $this->_client->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
//            $this->_client->setRedirectUri('http://apprentice.dev/admin/core/index');
//            $this->_client->setScopes('email');
//        }
//    }
//
//    public function isLoggedIn()
//    {
//        return isset($_SESSION['access_token']);
//    }
//
//    public function getAuthUrl()
//    {
//        return $this->_client->createAuthUrl();
//    }
//
//    public function checkRedirectCode()
//    {
//        if(isset($_GET['code'])) {
//            $this->_client->authenticate($_GET['code']);
//            $this->setToken($this->_client->getAccessToken());
//
//            return true;
//        }
//        return false;
//    }
//
//    public function setToken($token)
//    {
//        $_SESSION['access_token'] = $token;
//
//        $this->_client->setAccessToken($token);
//    }
//
//    public function logout()
//    {
//        unset($_SESSION['access_token']);
//    }
    public $googleClient;
    public $googlePlus;

    public $googleUserData;
    public $userData;

    public function __construct(Google_Client $client = null)
    {
        $this->googleClient = $client;
        //check if the user has requested to clear login information,
        $this->googleClient->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
        $this->googleClient->setRedirectUri('http://apprentice.dev/incubate/index/index');
        $this->googleClient->setScopes(array('https://www.googleapis.com/auth/plus.me'));

        $this->googlePlus = new Google_Service_Plus($this->googleClient);
    }

    public function setAllUserData()
    {
        $googleId = $this->googlePlus->people->get('me')->getId();

        $this->googleUserData = $this->googlePlus->people->get('me');
        $this->userData = Core_Model_Database::getInstance()->get('user', array ('google_id', '=', $googleId))->first();

        Core_Model_Request::getInstance()->setUser($this->userData);
        Core_Model_Request::getInstance()->setGoogle($this->googleUserData);

        $_SESSION['access_token'] = $this->googleClient->getAccessToken();
    }

    public function getUserData()
    {
        return $this->userData;
    }

    public function getGoogleUserData()
    {
        return $this->googleUserData;
    }
    public function checkRedirectCode()
    {
        if (isset($_GET['code'])) {
            $this->googleClient->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->googleClient->getAccessToken();
            return true;
        }
        return false;
    }

    public function checkAccessTokenSet()
    {
        if(isset($_SESSION['access_token'])) {
            $this->googleClient->setAccessToken($_SESSION['access_token']);
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['access_token']);
    }

    public function logout()
    {
        unset($_SESSION['access_token']);
    }
}