<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/7/15
 * Time: 2:50 PM
 */
class Incubate_Controller_Ajax extends Core_Controller_Abstract
{
    public function indexAction()
    {
        if(!empty($_GET['id'])) {
            switch($_GET['id']) {
                case 'tag' :
                    $user = Bootstrap::getModel('incubate/user');
                    $tagNames = $user->getAllTagNames();
                    $jsonTags = $user->jsonEncode($tagNames);
                    echo $jsonTags;
                    break;

                default:
                    echo 'Error loading json';
                    break;

            }
        }
        
    }
}