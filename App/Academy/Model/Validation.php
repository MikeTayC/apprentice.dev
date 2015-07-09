<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/8/15
 * Time: 4:58 PM
 */
class Academy_Model_Validation extends Core_Model_Validator
{
    public function __construct()
    {
        parent::__construct();
        $this->_validation = array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_confirmation' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        );
    }
}