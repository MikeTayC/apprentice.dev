<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 2:36 PM
 */
class Incubate_Model_User
{
    private $_db;
    private $_data;
    private $_isLoggedIn;

    public function __construct(/** Maybe add a way to check for user */)
    {
        $this->_db = Core_Model_Database::getInstance();

    }

    public function create($table, $fields = array()) {
        if(!$this->_db->insert($table, $fields)) {
            throw new Exception('Problem creating a new lesson!');
        }
    }

    public function get($table, $fields = array())
    {
        $data = $this->_db->get($table, $fields)->first();
        return $data;
    }

    public function checkUserDataForGoogleId($googleId)
    {
        /*
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, store in $_data
         */
        if($this->_data = $this->_db->get('user', array('google_id', '=', $googleId))->first()) {

            return true;
        }
        return false;
    }


    /*
     * will return false if data has not been set
     * cheks user
     */
    public function checkUserDataForAdminStatus()
    {
        if($this->_data->role == 'admin') {
            return true;
        }
        return false;
    }

    public function getAllUserData()
    {
        return $this->_data;
    }

    /*
     * get all users data from tablef
     */
    public function getAllUserDataFromUserTable()
    {
        if($this->_data = $this->_db->getAll('user')->results()) {
            return $this->_data;
        }
        return null;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    /*
     * retrieves all lessons informatino from lesson table
     */
    public function getAllLessonsFromLessonTable()
    {
        if($lesson =  $this->_db->getAll('lesson')->results()) {
            return $lesson;
        }
        return null;
    }

    /*
     * returns all tag inforamation from tag table
     */
    public function getAllTagsFromTagTable()
    {
        if($tags = $this->_db->getAll('tag')->results()) {
            return $tags;
        }
        return null;
    }

    /*
     * returns a json encoded version of all tag names
     */
    public function getAllTagNames()
    {
        $tags = $this->getAllTagsFromTagTable();
        foreach ($tags as $tag) {
            $tagNameArray[] = $tag->value;
        }

        return $tagNameArray;
    }

    public function jsonEncode($value)
    {
        $jsonValue = json_encode($value);
        return $jsonValue;
    }

    public function getTagLessonMap()
    {
        if($map = $this->_db->getAll('lesson_tag_map')->results()) {
            return $map;
        }
        return null;
    }

    /*
     * searches for new tags to be added to the array
     */
    public function AddNewTagsToDb($tagArray)
    {
        $dbTags = $this->getAllTagNames();
        foreach($tagArray as $tag) {
            if(!in_array($tag, $dbTags)) {
                $this->create('tag', array(
                    'value' => $tag
                ));
            }
        }
    }

}