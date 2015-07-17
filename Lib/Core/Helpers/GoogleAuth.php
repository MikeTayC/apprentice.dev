<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/14/15
 * Time: 1:21 PM
 */
class Core_Helpers_GoogleAuth
{
    protected $_client;
    public function __construct(Google_Client $googleClient = null)
    {
        $this->_client = $googleClient;

        if($this->_client) {
            $this->_client->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
            $this->_client->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
            $this->_client->setRedirectUri('http://apprentice.dev/admin/core/index');
            $this->_client->setScopes('email');
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['access_token']);
    }

    public function getAuthUrl()
    {
        return $this->_client->createAuthUrl();
    }

    public function checkRedirectCode()
    {
        if(isset($_GET['code'])) {
            $this->_client->authenticate($_GET['code']);
            $this->setToken($this->_client->getAccessToken());

            return true;
        }
        return false;
    }

    public function setToken($token)
    {
        $_SESSION['access_token'] = $token;

        $this->_client->setAccessToken($token);
    }

    public function logout()
    {
        unset($_SESSION['access_token']);
    }
}