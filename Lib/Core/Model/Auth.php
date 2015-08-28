<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/14/15
 * Time: 1:21
 *
 * this is going to contain all functionality regarding to authentication from google
 * checking if signed in, sessions set, checking code from google so we can authenticate with it
 **/
class Core_Model_Auth
{
    //Reference to client
    protected $_client;

    //Reference to Database wrapper
    protected $_user;

    //googleplus info
    protected $_plus;

    //reference to current user google information
    protected $_googleUser;

    //reference to user email
    protected $_email;

    /**
     * We need to pass in an instance of the google client we set = to null incase it doesnt exists
     * we can instantiate this class alone to logout out users if necessary
     * need to set client id, client secret, the redirect uri, and the scopes we want to get from.
     *
     * @param User_Model_Model $user
     * @param Google_Client $googleClient
     **/
    public function __construct(User_Model_Model $user = null, Google_Client $googleClient = null)
    {
        $this->_client = $googleClient;
        $this->_user = $user;

        if ($this->_client) {
            $clientInfo = Bootstrap::getGoogleClientInfo();
            $scopes = explode(',',$clientInfo['scopes']);
            $this->_client->setClientID($clientInfo['id']);
            $this->_client->setClientSecret($clientInfo['secret']);
            $this->_client->setRedirectUri($clientInfo['redirect']);
            $this->_client->setScopes($scopes);

            /**
             * google_service_plus : interface for accessing google plus information
             **/
            $this->_plus = new Google_Service_Plus($googleClient);

//            $this->_calendar = new Google_Service_Calendar($googleClient);
        }
    }

    /**
     * checks if sessions is set with the access token.
     * this is where google access token is stored
     **/
    public function isLoggedIn()
    {
        return isset($_SESSION['access_token']);
    }

    /**
     * returns a request url using the google client, needed to initiate a
     * request to google for OAuth log in
     **/
    public function getAuthUrl()
    {
        return $this->_client->createAuthUrl();
    }

    /**
     * Checks for redirect code, which will be in the url
     **/
    public function checkRedirectCode()
    {

        /**
         * If code is set in the get superglobal, then authenticate using this code
         * Client function authenticates code:
         * "we have had a request back from google now we are passsing this code that has been generated
         * on googles end, back through to google to verify."
         *
         * Then we set this token in a session. and return true for logic control
         **/
        if (isset($_GET['code'])) {
            $this->_client->authenticate($_GET['code']);
            $this->setToken($this->_client->getAccessToken());

            return true;
        }
        return false;
    }

    /**
     * We set an access_token variable = $token(which will be recieved from google client in check
     * redirect
     *
     * we also want to set accesss token by the client as well
     * @param $token comes from client (google client checkredirect code), we cant generate oursevles
     **/
    public function setToken($token)
    {
        Core_Model_Session::set('access_token', $token);
        $this->_client->setAccessToken($token);
    }

    /**
     * To acquire the users email, we need to access the google clients attributes array 'payload',
     *  (example)
     *   'payload' =>
     *       array (size=9){
     *       ...
     *      'email' => string 'UserEmail@gmail.com' (length=33)
     *       ...
     **/
    public function setEmail()
    {
        return $this->_client->verifyIdToken()->getAttributes()['payload']['email'];

    }

    //returns set email
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * creates an instance of the google service plus class to retrieve all
     * of the users google profile information
     **/
    public function setGooglePlusInfo()
    {
        $this->_googleUser = $this->_plus->people->get('me');
        return $this->_googleUser;
    }

    /**
     * returns the google id, uses an instance of the google_servie plus class to retrieve it
     **/
    public function getGooglePlusId()
    {

        $googleId = $this->_googleUser->getId();
        return $googleId;
    }
    /**
     * Need to access google profile name to access users name,
     * if we ever need more profile information manage here
     **/
    public function getGooglePlusDisplayName()
    {
        $displayName = $this->_googleUser->getDisplayName();
        return $displayName;
    }

    /**
     * Checks database against google user information, will use google id to do this.
     * if located, will store user info in the session
     *
     * @param $googleId
     * @return bool
     */
    public function checkDatabaseForUser($googleId)
    {
        /*
         * check user data for google id, against the person trying to log in
         */
        if($this->_user->checkUserDataForGoogleId($googleId)) {

            return true;
        }
        return false;
    }

    /**
     * This will be called in the even the user is attempting to login, but is not located in the database
     * we will pass validation and see if the user has a blue acorn email address
     *
     * @param $email
     * @return bool
     */
    public function validateNewEmailAddress($email)
    {
        $blueAcorn =  substr($email, -14);
        if($blueAcorn == "@blueacorn.com") {
            return true;
        }
        return false;

    }
}