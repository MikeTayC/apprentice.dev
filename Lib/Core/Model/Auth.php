<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/14/15
 * Time: 1:21 PM
 */

/*
 * TODO move to models
 * this is going to contain all functionality regarding to authentication from google
 * checking if signed in, sessions set, checking code from google so we can authenticate with it
 */
class Core_Helpers_GoogleAuth
{
    //Reference to client
    protected $_client;

    /*
     * We need to pass in an instance of the google client we set = to null incase it doesnt exists
     * we can instantiate this class alone to logout out users
     * need to set client id, client secrete, the redirect uri, and the scopes we want to get from.
     *
     */
    public function __construct(Google_Client $googleClient = null)
    {
        $this->_client = $googleClient;

        if($this->_client) {
            $this->_client->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
            $this->_client->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
            $this->_client->setRedirectUri('http://apprentice.dev/incubate/login/index');
            $this->_client->setScopes('email');
        }
    }

    /*
     * checks if sessions is set with the access token.
     * this is where google access token is stored
     *
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['access_token']);
    }

    /*
     * returns a request url using the google client
     *
     */
    public function getAuthUrl()
    {
        return $this->_client->createAuthUrl();
    }

    /*
     * Checks for redirect code, which will be in the url
     */
    public function checkRedirectCode()
    {
        /*
         * if code is set in the get superglobal, then authenticate using this code
         * client function authenticates code:
         * "we have had a request back form google now we are passsing this code that has been generated
         * on googles end, back through to google to verify.
         *
         * then we set this token in a session. and return true for logic control
         *
         */
        if(isset($_GET['code'])) {
            $this->_client->authenticate($_GET['code']);
            $this->setToken($this->_client->getAccessToken());

            return true;
        }
        return false;
    }

    /*
     * $token comes from client (google client checkredirect code), we cant generate oursevles
     *
     * we set an access_token variable = $token(which will be recieved from google client in check
     * redirect
     *
     * we also want to set accesss token by the client as well
     *
     */
    public function setToken($token)
    {
        $_SESSION['access_token'] = $token;

        $this->_client->setAccessToken($token);
    }

    public function logout()
    {
        unset($_SESSION['access_token']);
    }

    public function getPayload()
    {
        $payload = $this->_client->verifyIdToken()->getAttributes()['payload'];
        return $payload;
    }

    protected function storeUser($payload)
    {
        //insert into user if new
    }
//    public function setAllUserData()
//    {
//
//        $this->userData = Core_Model_Database::getInstance()->get('user', array ('google_id', '=', $googleId))->first();
//
//        Core_Helpers_Session::set('userData', $this->userData);
//
//        Core_Helpers_Session::set('googleUserData', $this->googleUserData);
//
//        $_SESSION['access_token'] = $this->googleClient->getAccessToken();
//    }
/**}

//    public $googleClie;
//    public $googlePl;/
//    public $googleUserDa;
//    public $userDa;/
//    public function __construct(Google_Client $client = nu)
//  {
//        $this->googleClient = $clie;
//        //check if the user has requested to clear login informati,
//        $this->googleClient->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com;
//        $this->googleClient->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro;
//        $this->googleClient->setRedirectUri('http://apprentice.dev/incubate/index/index;
//        $this->googleClient->setScopes(array('https://www.googleapis.com/auth/plus.me';/
//        $this->googlePlus = new Google_Service_Plus($this->googleClien;
//  }/
//    public function setAllUserDat)
//  {
//        $googleId = $this->googlePlus->people->get('me')->getId;/
//        $this->googleUserData = $this->googlePlus->people->get('me;
//        $this->userData = Core_Model_Database::getInstance()->get('user', array ('google_id', '=', $googleId))->first;/
//        Core_Model_Request::getInstance()->setUser($this->userDat;/
//        Core_Helpers_Session::set('userData', $this->userDat;/
//        Core_Model_Request::getInstance()->setGoogle($this->googleUserDat;/
//        Core_Helpers_Session::set('googleUserData', $this->googleUserDat;/
//        $_SESSION['access_token'] = $this->googleClient->getAccessToken;
//  }/
//    public function getUserDat)
//  {
//        return $this->userDa;
//  }/
//    public function getGoogleUserDat)
//  {
//        return $this->googleUserDa;
//  }
//    public function checkRedirectCod)
//  {
//        if (isset($_GET['code']){
//            $this->googleClient->authenticate($_GET['code';
//            $_SESSION['access_token'] = $this->googleClient->getAccessToken;
//            return tr;
//      }
//        return fal;
//  }/
//    public function checkAccessTokenSe)
//  {
//        if(isset($_SESSION['access_token']){
//            $this->googleClient->setAccessToken($_SESSION['access_token';
//      }
//  }/
//    public function checkAccessTokenExpire)
//  {
//        if ($this->googleClient->isAccessTokenExpired)
//      {
//            $this->logout;
//      }
//  }
//    public function logou)
//  {
//        unset($_SESSION['access_token';
//  }/
//    public function isLoggedI)
//  {
//        return isset($_SESSION['access_token';
//  }/
//    public function getGoogleUserI)
//  {
//        $googleId = $this->googlePlus->people->get('me')->getId;
//        return $google;
//  }/
//    public function getGoogleEmai)
//  {
//        $googleEmail = $this->googlePlus->people->get('me')->getEmails;
//        return $googleEma;
//  }/
//    public function getGoogleNam)
//  {
//        $googleName = $this->googlePlus->people->get('me')->getDisplayName;
//        return $googleNa;
//  */  }
