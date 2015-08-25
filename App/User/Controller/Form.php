<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/25/15
 * Time: 11:20 AM
 */
class User_Controller_Form extends Incubate_Controller_Abstract
{
    public function registerAction($email, $displayName)
    {
        $view = $this->loadLayout($default = false);
        $view->getContent()->setName($displayName)->setEmail($email);
        $view->render();
    }
}