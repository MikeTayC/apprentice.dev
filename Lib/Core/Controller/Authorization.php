<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/17/15
 * Time: 9:54 AM
 */
class Core_Controller_Authorization extends Core_Controller_Abstract
{
    public $googleClient;
    public $googlePlus;
    public function __construct($googleClient = null)
    {
        $this->googleClient = new Google_Client();
//        $auth = new Core_Helpers_GoogleAuth($googleClient);

        //check if the user has requested to clear login information,
        $this->googleClient->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
        $this->googleClient->setRedirectUri('http://apprentice.dev/incubate/index/index');
        $this->googleClient->setScopes(array('https://www.googleapis.com/auth/plus.me'));

        $this->googlePlus = new Google_Service_Plus($this->googleClient);
    }

    public function checkRedirectCode()
    {
        if (isset($_GET['code'])) {
            $this->googleClient->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->googleClient->getAccessToken();
            header('Location: http://apprentice.dev/incubate/index/index');
        }
    }

    public function checkAccessTokenSet()
    {
        if(isset($_SESSION['access_token'])) {
            $this->googleClient->setAccessToken($_SESSION['access_token']);
        }
    }
}
