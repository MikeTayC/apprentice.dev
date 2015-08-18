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

    //stores recommended students to invite
    public $studentInviteList = array();

	public function __construct()
	{
		$this->_table = 'user';
		parent::__construct();
	}

	public function loadUser($userId)
	{
		$this->_data = $this->get(array('user_id', '=', $userId));
		return $this;
	}



	public function makeUserAdmin()
	{
		$this->update($this->_data->user_id, 'user_id', array(
			'role' => 'admin'
		));
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
        if($this->_data = $this->get(array('google_id', '=', $googleId))) {
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

        //stores all users in table
        $allUserData = $this->getAllBasedOnGivenFields(array('groups', '=', $group));

        /*
         * foreach user, check if the user has completed the lesson
         * if they have, skip to next iteration
         *
         * if they have not, check if theyre group is tagged by the lesson,
         * store their names in student invite list
         */
        if(isset($allUserData)) {

            foreach($allUserData as $user) {

                if($this->checkIfUserCompletedSpecificCourse($lessonId, $user)){
                    continue;
                }
			    $this->studentInviteList[] = $user->name;
            }
        }
    }

    public function checkIfUserCompletedSpecificCourse($lessonId, $user)
    {
        if($this->_db->getMultiArgument('completed_courses', array('user_id', '=', $user->user_id), array('lesson_id', '=', $lessonId))->results())
        {
            return true;
        }
		return false;
    }

    public function getAllUserCompletedCourseId($userId)
    {
        if($userCompletedCourseIdMap = $this->_db->get('completed_courses', array('user_id', '=', $userId))->results()) {

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

    public function markCourseComplete($userId, $lessonId)
    {
        if($this->_db->insert('completed_courses', array('user_id' => $userId, 'lesson_id' => $lessonId))){
            return true;
        }
        return false;
    }
	public function getCompletedCourseCount($userId)
	{
		if($data = $this->_db->get('completed_courses', array('user_id', '=', $userId))->count()) {
            return $data;
        }
        return null;
	}
	public function deleteCompletedCourseMap($userId)
	{
		$this->_db->delete('completed_courses',array('user_id', '=', $userId));
	}

	public function deleteThisUser($userId)
	{
		$this->_db->delete($this->_table, array('user_id', '=', $userId));
	}
}