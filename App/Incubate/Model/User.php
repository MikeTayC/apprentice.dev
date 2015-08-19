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

	public function loadUserByGoogleId($googleId)
	{
		$this->_data = $this->get(array('google_id', '=', $googleId));
		return $this;
	}



	public function makeUserAdmin()
    {
        $this->update($this->_data->id, 'id', array(
            'role' => 'admin'
        ));
    }

    public function getAllStudents()
    {
        $students = $this->getAllBasedOnGivenFields(array('role', '=', 'student'));

        return $students;
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
        if($this->loadUserByGoogleId($googleId)) {

			Core_Model_Session::set('user_id', $this->getId());
			Core_Model_Session::set('logged_in', true);
			if($this->getRole() == 'admin'){
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

                if($user['role'] == 'admin' || $this->checkIfUserCompletedSpecificCourse($lessonId, $user)){
                    continue;
                }
			    $this->studentInviteList[] = $user['name'];
            }
        }
    }

    public function getAllStudentsAsArray()
    {
        $studentNameArray = array();
        $userNameArray = $this->getAllNamesAsArray();
        if(isset($userNameArray)) {
            foreach($userNameArray as $user) {
                if($user['role'] = 'student') {
                    $studentNameArray[] = $user['name'];
                }
            }
            return $studentNameArray;
        }
        return null;
    }

    public function checkIfUserCompletedSpecificCourse($lessonId, $user)
    {
        if($this->_db->getMultiArgument('completed_courses', array('user_id', '=', $user['id']), array('lesson_id', '=', $lessonId))->results())
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

	public function markCourseIncomplete($lessonId)
	{
		$this->_db->deleteMultiArgument('completed_courses', array('user_id', '=', $this->getId()), array('lesson_id','=',$lessonId));
	}

    public function markCourseComplete($lessonId)
    {
        if(!$this->_db->getMultiArgument('completed_courses', array('user_id', '=', $this->getId()), array('lesson_id', '=', $lessonId))->count()) {
			$this->_db->insert('completed_courses', array('user_id' => $this->getId(), 'lesson_id' => $lessonId));
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
	public function deleteCompletedCourseMap()
	{
		$this->_db->delete('completed_courses',array('user_id', '=', $this->getId()));
	}

	public function deleteThisUser($userId)
	{
		$this->_db->delete($this->_table, array('id', '=', $userId));
	}
}