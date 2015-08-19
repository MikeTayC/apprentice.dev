<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/17/15
 * Time: 11:29 AM
 */
abstract class Incubate_Controller_Abstract extends Core_Controller_Abstract
{

    public function checkIfUserIsLoggedIn()
    {
        if(!Core_Model_Session::get('logged_in')) {
            Core_Model_Session::dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }
    }

    public function checkIfUserIsAdmin()
    {
        if(!Core_Model_Session::get('admin_status')) {
            Core_Model_Session::dangerflash('error', 'Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }
    }


    public function flashCheck()
    {
        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');
    }

    public function explode($list)
    {
        $newArray = explode(',', $list);
        return $newArray;
    }

    public function formatStartDateTime($date, $startTime)
    {
        $startTime = $this->formatTime($startTime);
        $startDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $startTime));
        return $startDateTime;
    }

    public function formatEndDateTime($date, $startTime, $duration)
    {
        $startTime = $this->formatTime($startTime);
        $timeDuration = '+' . $duration . 'minutes';
        $endTime = date("H:i", strtotime($timeDuration, $startTime));
        $endTime = date("g:i a", strtotime($endTime));
        $endDateTime = date('Y-m-d\TH:i:sP', strtotime($date . ' ' . $endTime));
        return $endDateTime;
    }

    private function formatTime($time)
    {
        $time = strtotime($time);
        $newTime = date("g:i a", $time);
        return $newTime;
    }

    public function appendTagsAndDescition($description, $tagsArray)
    {
        foreach($tagsArray as $tag) {
            $description .= ' #' . $tag;
        }
        return $description;
    }

    public function userProfileCheck($userId)
    {
        if(!Core_Model_Session::get('admin_status') && (Core_Model_Session::get('user_id') != $userId)) {
                Core_Model_Session::dangerFlash('error', 'You must be an admin to visit another profile');
                $this->headerRedirect('incubate','index','index');
                exit;
            }
        }

    protected function _getRequest(){
        return Core_Model_Request::getInstance();
    }

}