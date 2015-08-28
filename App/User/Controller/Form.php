<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:20 AM
 *
 * Controller handles registration form,
 **/
class User_Controller_Form extends Incubate_Controller_Abstract
{
    /** Must override parent constructor check for logged_in status */
    public function __construct(){}

    /** Form action */
    public function registerAction()
    {
        /** @var  $email  google account email is stored in session at login*/
        $email = $this->_sessionGet('email');

        /** @var  $displayName google dispaly name is stored in session at login*/
        $displayName = $this->_sessionGet('googleDisplayName');

        /** @var  $view loads view, binds info to block, renders page */
        $view = $this->loadLayout($default = false);
        $view->getContent()->setName($displayName)->setEmail($email);
        $view->render();
    }
}