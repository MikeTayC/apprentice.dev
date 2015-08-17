<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/16/15
 * Time: 2:36 PM
 */
class Incubate_Model_User extends Core_Model_Abstract
{

    private $_isLoggedIn;

    /*
     * reference to group tags
     */
    private $AE = false;
    private $QA = false;
    private $FE = false;

	public function __construct()
	{
		$this->_table = 'user';
		parent::__construct();
	}

	public function createUser($name, $email, $group, $googleId) {
		$this->create(array(
			'name' => $name,
			'email' => $email,
			'groups' => $group,
			'google_id' => $googleId,
			'joined' => date('Y-m-d'),
			'role' => 'student'
		));
	}

	/*
	 * essentially logs the user out by ridding the session of its token
	 */
	public function logout()
	{
		unset($_SESSION['access_token']);
		Core_Model_Session::set('logged_in', false);
		Core_Model_Session::deleteAll();
	}

    public function checkUserDataForGoogleId($googleId)
    {
        /*
         * check db, for USER table, WHERE google_id = $googleid, return the first
         * set of info  found, store in $_data
         */
        if($this->_data = $this->get(array('google_id', '=', $googleId))->first()) {
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
		$user = $this->get(array('google_id', '=', $googleId));
        if(isset($user->role) && $user->role == 'admin') {
            return true;
        }
        return false;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function getUserEmail($studentName) {
        if($user = $this->get(array('name', '=', $studentName))) {
            return $user->email;
        }
        return null;
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
    public function AddStudentsIfNotTaken($group, $lessonId)
    {
        //stores recommended students to invite
        $studentInviteList = array();

        //stores all users in table
        $allUserData = $this->getAllBasedOnGivenFields(array('groups', '=', $group));

        /*
         * foreach user, check if the user has completed the lesson
         * if they have, skip to next iteration
         *
         * if they have not, check if theyre group is tagged by the lesson,
         * store their names in student invite list
         */
        foreach($allUserData as $user) {

            if($this->checkIfUserCompletedSpecificCourse($lessonId, $user)){
                continue;
            }
			$studentInviteList[] = $user->name;
        }
        return $studentInviteList;
    }

    public function checkIfUserCompletedSpecificCourse($lessonId, $user)
    {
        if($this->_db->getMultiArgument('completed_courses', array('user_id', '=', $user->user_id), array('lesson_id', '=', $lessonId)))
        {
            return true;
        }
		return false;
    }

    public function getAllUserCompletedCourseId($userId)
    {
        if($userCompletedCourseIdMap = $this->getAll('completed_courses', array('user_id', '=', $userId))) {

            foreach($userCompletedCourseIdMap as $mapValue) {
                $userCompletedCourseIdArray[] = $mapValue->lesson_id;
            }
            return $userCompletedCourseIdArray;
        }
        return array();
    }

	public function markCourseIncomplete($userId, $lessonId)
	{
		$this->_db->deleteMultiArgument('completed_courses', array('user_id', '=', $userId), array('lesson_id','=',$lessonId));
	}

	public function getCompletedCourseCount($userId)
	{
		$this->_db->getAll('completed_courses', array('user_id', '=', $userId))->count();
	}
}