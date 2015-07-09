<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/9/15
 * Time: 1:13 PM
 */
class Academy_Controller_Login extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if (Core_Helpers_Input::exists()) {
            var_dump($_POST);
        }
        else {
            $this->loadLayout();
        }
    }
}