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

    /*
     * reference to group tags
     */
    private $AE = false;
    private $QA = false;
    private $FE = false;

    public function __construct(/** Maybe add a way to check for user */)
    {
        $this->_db = Core_Model_Database::getInstance();

    }

    public function create($table, $fields = array()) {
        if(!$this->_db->insert($table, $fields)) {
            throw new Exception('Problem!');
        }
    }

    /*
     * returns first result found from database search
     */
    public function get($table, $fields = array())
    {
        $data = $this->_db->get($table, $fields)->first();
        return $data;
    }

    public function getAll($table, $fields =array())
    {
        $data = $this->_db->get($table, $fields)->results();
        return $data;
    }

    public function getMultiArguments($table, $fields = array(), $fields2 = array())
    {
        $data = $this->_db->getMultiArgument($table, $fields, $fields2)->first();
        return $data;
    }

    public function checkUserDataForGoogleId($googleId)
    {
        /*
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, store in $_data
         */
        if($this->_data = $this->_db->get('user', array('google_id', '=', $googleId))->first()) {
			Core_Model_Session::set('user_id', $this->_data->user_id);
			Core_Model_Session::set('logged_in', true);
			if($this->_data->role == 'admin'){
				Core_Model_Session::set('admin_status', true);
			}
			else {
				Core_Model_Session::set('admin_status', false);
			}
            return true;
        }

		/*
		 *
		 * even if they are not in the database, store google id in the session,
		 * will need to use later if the user must be added to the database.
		 */
			Core_Model_Session::set('google_id', $googleId);

        return false;
    }

    /*
     * will return false if data has not been set
     * cheks user
     */
    public function checkUserDataForAdminStatus($googleId)
    {
		$user = $this->get('user', array('google_id', '=', $googleId));
        if(isset($user->role) && $user->role == 'admin') {
            return true;
        }
        return false;
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

    public function getUserEmail($studentName) {
        if($user = $this->get('user', array('name', '=', $studentName))) {
            return $user->email;
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

    public function getTagsFromTagTableByTagId($tagId)
    {
        if($tag = $this->get('tag', array('id', '=', $tagId))) {
            return $tag->value;
        }
        return null;
    }
    /*
     * returns a json encoded version of all tag names
     */
    public function getAllTagNames()
    {
        $tags = $this->getAllTagsFromTagTable();
		if($tags) {
        	foreach ($tags as $tag) {
            	$tagNameArray[] = $tag->value;
        	}
        	return $tagNameArray;
		}
		return null;
    }

    public function getAllStudentNames()
    {
        if($users = $this->getAllUserDataFromUserTable()) {
        	foreach ($users as $user) {
            	$userNameArray[] = $user->name;
        	}
			return $userNameArray;
		}
		return null;
	}
    public function getAllLessonNames()
    {
        if($lessons = $this->getAllLessonsFromLessonTable()) {
        	foreach($lessons as $lesson) {
            	$lessonNameArray[] = $lesson->name;
        	}
        	return $lessonNameArray;
		}
		return null;
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

    public function getTagLessonMapFromLessonId($lesson_id)
    {
        if($lessonTags = $this->getAll('lesson_tag_map', array('lesson_id', '=', $lesson_id))){
            return $lessonTags;
        }
        return null;
    }

    /*
     * searches for new tags to be added to the array,
     * if it is not an rray
     */
    public function AddNewTagsToDb($tagArray)
    {
        $dbTags = $this->getAllTagNames();
        if(is_array($tagArray)) {
            foreach ($tagArray as $tag) {
                if (!in_array($tag, $dbTags)) {
                    $this->create('tag', array(
                        'value' => $tag
                    ));
                }
            }
        }
    }

    /*
     * Will add students to invite list if they group is tagged in the lesson and
     * if they have not taken the course yet. if they have taken the course
     * they will not be invited.
     *
     * function will take one argument: the lesson id of lesson being scheduled
     *
     * returns an array of students to be invited
     */
    public function addStudentsToInviteListIfTaggedAndNotTaken($lessonData)
    {

        /*
         * check if lesson specifiies group tags
         */
        $this->checkLessonTagsForGroupTag($lessonData);

        //stores recommended students to invite
        $studentInviteList = array();

        //stores all users in table
        $allUserData = $this->getAllUserDataFromUserTable();

        /*
         * foreach user, check if the user has completed the lesson
         * if they have, skip to next iteration
         *
         * if they have not, check if theyre group is tagged by the lesson,
         * store their names in student invite list
         */
        foreach($allUserData as $user) {

            if($this->checkUserCompletedCourses($lessonData->lesson_id, $user)){
                continue;
            }

            if(($this->AE && $user->groups == 'Application Engineer') || ($this->QA && $user->groups == 'Quality Assurance Analyst') || ($this->FE && $user->groups == 'Front End Developer')) {
                $studentInviteList[] = $user->name;
            }


        }
        return $studentInviteList;
    }

    /*
     * Assigns true to refferences of the specified group
     *
     * based on the tag id number which we know, from the tag table
     *
     * 14 : application engineers
     * 15 : quality assurance
     * 16 : front end devs
     */
    public function checkLessonTagsForGroupTag($lessonData)
    {
        $tagLessonMapData = $this->getAll('lesson_tag_map', array('lesson_id', '=', $lessonData->lesson_id));

        foreach($tagLessonMapData as $key => $tag) {
            switch($tag->tag_id) {
                case '1' :
                    $this->AE = true;
                    break;
                case '2':
                    $this->QA = true;
                    break;
                case '3' :
                    $this->FE = true;
                    break;
            }
        }
    }

    public function checkUserCompletedCourses($lesson_id, $user)
    {
        if($this->getMultiArguments('completed_courses', array('user_id', '=', $user->user_id), array('lesson_id', '=', $lesson_id)))
        {
            return true;
        }
    }

    public function getAllUserCompletedCourseId($userId)
    {
        if($userCompletedCourseId = $this->getAll('completed_courses', array('user_id', '=', $userId))) {

            foreach($userCompletedCourseId as $key => $value) {
                $userCompletedCourse = $this->get('lesson', array('lesson_id', '=', $value->lesson_id));
                $userCompletedCourseIdArray[] = $userCompletedCourse->lesson_id;
            }
            return $userCompletedCourseIdArray;
        }
        return array();
    }

    public function getCount($table, $fields = array())
    {
        $data = $this->_db->get($table, $fields)->count();
        return $data;
    }

    public function deleteMultiArguments($table, $where, $where2)
    {
        $data = $this->_db->deleteMultiArgument($table, $where, $where2);
        return $data;
    }

    public function update($table, $fieldToCheck, $fieldCheck, $fields)
    {
        $this->_db->update($table, $fieldToCheck, $fieldCheck, $fields);
    }
}