<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/23/15
 * Time: 9:36 AM
 */
class Incubate_Controller_Authorization extends Core_Controller_Abstract
{

    public $googleClient;
    public $auth;

    public function __construct()
    {
        //check if user asked to log out, then logout
        $this->googleClient = new Google_Client();

        $this->auth = new Core_Helpers_GoogleAuth($this->googleClient);

        //check redirect code,if its set, get access token
        if($this->auth->checkRedirectCode()) {
            header('Location: http://apprentice.dev/incubate/index/index');
        }

        //if it is, sets it on client
        $this->auth->checkAccessTokenSet();
    }
}