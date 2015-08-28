<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 8/23/15
 * Time: 10:29 AM
 * Controller responsible for deleting user from records
 **/
class User_Controller_Delete extends Incubate_Controller_Admin
{
    public function idAction($userId)
    {
        /** Checks user id across database **/
        $this->_idCheck($userId, 'user');

        /** Deletes user, dispatch deletes all info related to tags and lessons */
        Bootstrap::getModel('user/model')->load($userId)->delete();

        /** flash message and redirect  */
        $this->_successFlash('User successfully removed');
        $this->_thisModuleRedirect('view');
    }
}