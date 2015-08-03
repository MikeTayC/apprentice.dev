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
class Core_Model_Auth
{
    //Reference to client
    protected $_client;

    //Reference to Database wrapper
    protected $_user;

    //googleplus info
    protected $_plus;

    protected $_googleUser;

    protected $_email;

    /*
     * We need to pass in an instance of the google client we set = to null incase it doesnt exists
     * we can instantiate this class alone to logout out users
     * need to set client id, client secrete, the redirect uri, and the scopes we want to get from.
     *
     */
    public function __construct(Incubate_Model_User $user = null, Google_Client $googleClient = null)
    {
        $this->_client = $googleClient;
        $this->_user = $user;

        if ($this->_client) {
            $this->_client->setClientID('433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com');
            $this->_client->setClientSecret('iehdSyaJgoH5uwgsjwPYO9ro');
            $this->_client->setRedirectUri('http://apprentice.dev/incubate/login/index');
            $this->_client->setScopes('email','scopes');

            /*
             * google_service_plus : interface for accessing google plus information
             */
            $this->_plus = new Google_Service_Plus($googleClient);
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
        if (isset($_GET['code'])) {
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
        Core_Model_Session::set('access_token', $token);
        $this->_client->setAccessToken($token);
    }

    /*
     * essentially logs the user out by ridding the session of its token
     */
    public function logout()
    {
        unset($_SESSION['access_token']);
        Core_Model_Session::set('logged_in', false);
    }

    /*
     * To acquire the users email, we need to access the google clientwss attributes array 'payload',
     *   'payload' =>
            array (size=9)
          'iss' => string 'accounts.google.com' (length=19)
          'sub' => string '104525396091787311683' (length=21)
          'azp' => string '433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com' (length=72)
          'email' => string 'tayzerphazerlazerblazer@gmail.com' (length=33)
          'at_hash' => string 'UQ82_t_wLvsTXmHDHp-7bA' (length=22)
          'email_verified' => boolean true
          'aud' => string '433657982361-lev74410eid7ejpnbu30dgi3crl0m3c1.apps.googleusercontent.com' (length=72)
          'iat' => int 1438206109
          'exp' => int 1438209709
     */
    public function setEmail()
    {
        return $this->_client->verifyIdToken()->getAttributes()['payload']['email'];

    }

    //returns set email
    public function getEmail()
    {
        return $this->_email;
    }


    /*
     * creates an instance of the google service plus class to retrieve all
     * of the users google profile information
     */
    public function setGooglePlusInfo()
    {
        $this->_googleUser = $this->_plus->people->get('me');
        return $this->_googleUser;
    }

    /*
     * returns the google id, uses an instance of the google_servie plus class to retrieve it
     */
    public function getGooglePlusId()
    {

        $googleId = $this->_googleUser->getId();
        return $googleId;
    }
    /*
     * need to access google profile name to access users name,
     * if we ever need more profile information manage here
     */
    public function getGooglePlusDisplayName()
    {
        $displayName = $this->_googleUser->getDisplayName();
        return $displayName;
    }

    /*
     * checks database against google user information, will use google id, and email to do this.
     * if located, will store user info in the session
     *
     */
    public function checkDatabaseForUser($googleId)
    {
        /*
         * check user data for google id, against the person trying to log in
         */
        if($this->_user->checkUserDataForGoogleId($googleId)) {
            Core_Model_Session::set('google_id', $googleId);
            Core_Model_Session::set('logged_in', true);
            return true;
        }
        return false;
    }

    /*
     * this will be called in the even the user is attempting to login, but is not located in the database
     * we will pass validation and see if the user has a blue acorn email addres
     *
     */
    public function validateNewEmailAddress($email)
    {
        $blueAcorn =  substr($email, -14);
        if($blueAcorn == "@blueacorn.com") {
            return true;
        }
        return false;

    }

    /*
     * will check if the user logged has admin status
     */
    public function checkAdminStatus()
    {
        //check return true if admin has been set, if it hasnt, return false
        if(Core_Model_Session::get('admin_status')){
            return true;
        }
        return false;


    }
    public function assignAdminStatus()
    {
        //set local variable, assigned session google_id
        $googleId = Core_Model_Session::get('google_id');

        //check admin status in database using google_id
        if($this->_user->checkUserDataForAdminStatus($googleId)) {
            Core_Model_Session::set('admin_status', true);
        }
        else {
            Core_Model_Session::set('admin_status', false);
        }
    }

    /*
     * if we are adding a new user, blueacorn email address should have been validated already
     *
     * after user has been added to database, after checking, sttart the new users session.
     */
    public function addNewUser($googleId,$googleDisplayName, $email)
    {
        $this->_user->create('user', array(
            'google_id' => $googleId,
            'name'      => $googleDisplayName,
            'email'     => $email,
            'role'      => 'standard',
            'joined'    => date('Y-m-d H:i:s')
        ));

        if($this->_user->checkUserDataForGoogleId($googleId)) {
            Core_Model_Session::set('google_id', $googleId);
            Core_Model_Session::set('logged_in', true);
        }
    }
}