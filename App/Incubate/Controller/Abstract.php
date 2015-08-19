<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/17/15
 * Time: 11:29 AM
 */
abstract class Incubate_Controller_Abstract extends Core_Controller_Abstract
{

    protected function _checkIfUserIsLoggedIn()
    {
        if(!$this->_sessionGet('logged_in')) {
            $this->_dangerFlash('You are not logged in!');
            $this->headerRedirect('incubate', 'login', 'index');
            exit;
        }
    }

    protected function _checkIfUserIsAdmin()
    {
        if(!Core_Model_Session::get('admin_status')) {
            $this->_dangerFlash('Admins Only');
            $this->headerRedirect('incubate','index','index');
            exit;
        }
    }


    protected function _flashCheck()
    {
        echo Core_Model_Session::dangerFlash('error');
        echo  Core_Model_Session::successFlash('message');
    }

    protected function _successFlash($message)
    {
        Core_Model_Session::successFlash('message', $message);
    }

    public function _dangerFlash($message)
    {
        Core_Model_Session::dangerFlash('error', $message);
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
        if(!$this->_sessionGet('admin_status') && ($this->_sessionGet('user_id') != $userId)) {
                Core_Model_Session::dangerFlash('error', 'You must be an admin to visit another profile');
                $this->headerRedirect('incubate','index','index');
                exit;
            }
        }

    protected function _getRequest(){
        return Core_Model_Request::getInstance();
    }

    protected function _sessionGet($param)
    {
        return Core_Model_Session::get($param);
    }

    protected function _sessionSet($param, $value)
    {
        Core_Model_Session::set($param, $value);
    }

    protected function _sessionDelete($param)
    {
        Core_Model_Session::delete($param);
    }

}