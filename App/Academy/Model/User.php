<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 11:41 AM
 */
class Academy_Model_User
{
    private $_data;
    private $_db;
    private $_sessionName;
    private $_cookieName;
    private $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = Core_Model_Database::getInstance();

        /*
         * to grab specific user details, use same user object
         *  define userid/username inside db objects
         * session_name is set in login()
         * if user is not defined
         */
        if(!$user) {
            if(Core_Helpers_Session::exists($this->_sessionName)) {
                $user = Core_Helpers_Session::get($this->_sessionName);

                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                }
                else {
                    //process logout
                }
            }
        }

        /*
         * if user is defined upon construction, will get a specifc user
         */
        else {
            if($this->find($user)) {
                $this->_isLoggedIn = true;
            }
        }
    }
    public function update($fields = array(), $id = null)
    {
        if(!$id && $this->isLoggedIn())
        {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields)) {
            throw new Exception('Problem updating account!');
        }
    }
    public function create($fields = array()) {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception('Problem creating a new account!');
        }
    }

    public function login($username = null, $password = null, $remember = false)
    {
        if(!$username && !$password && $this->exists()) {
            //log user in by data id
            Core_Helpers_Session::set($this->_sessionName, $this->data()->id);
            return true;
        }
        else {
            $user = $this->find($username);
            if ($user) {
                if ($this->data()->password === Core_Helpers_Hash::make($password, $this->data()->salt)) {
                    Core_Helpers_Session::set($this->_sessionName, $this->data()->id);

                    /*
                     * if remember me is checked, we want to generate a hash, check to see if it doesnt already exists
                     * and insert the hash into the database,
                     * this will be looked up everytine this user visits the page, because their cookie is stored on their comp
                     */
                    if ($remember) {
                        //generates hash
                        $hash = Core_Helpers_Hash::unique();

                        //checks inside our database(users_session) if user_id exists already, if does it will be assinged to session at somepoint, even though it should have been deleted?
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
                        if (!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Core_Helpers_Cookie::set($this->_cookieName, $hash, Core_Model_Config_Json::getModulesCookieConfig('cookie_expiry'));
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function hasPermission($key)
    {
        $connect = $this->_db->get('connect', array('id', '=', $this->data()->connect));

        if($connect->count()) {
            $permissions = json_decode($connect->first()->permissions, true);

            if($permissions[$key] == true) {
                return true;
            }
        }

        return false;

    }

    public function exists()
    {
        //checks whether data exists in data array
        return (!empty($this->_data)) ? true : false;
    }

    /*
     * finds user info, at specified field( username or id)
     */
    public function find($user = null)
    {
        if($user) {
            $field = (is_numeric($user)) ? 'id' :'username';

            $data = $this->_db->get('users', array ($field, '=', $user));

            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    /*
     * returns data set inside find()
     */
    public function data()
    {
        return $this->_data;
    }

    /*
     * checks if user is logged in, by returning _isLoggedIn,
     * set upon construction
     */
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function logout()
    {
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
        Core_Helpers_Session::delete($this->_sessionName);
        Core_Helpers_Session::delete($this->_cookieName);
        Core_Helpers_Cookie::delete($this->_cookieName);
    }
}