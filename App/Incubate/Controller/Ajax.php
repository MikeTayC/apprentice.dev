<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 8/7/15
 * Time: 2:50 PM
 *
 * Ajax controller, works in conjuction with the Tag-it! jquery
 * plugin
 **/
class Incubate_Controller_Ajax extends Core_Controller_Abstract
{
    /**
     * Gets called through ajax requests,
     *
     * Checks request for GET variable ['id], returns json encoded
     * information based on id,
     *
     * Options: tags, lessons, admin users, student users
     **/
    public function indexAction()
    {
        if(!empty($_GET['id'])) {
            switch($_GET['id']) {

                case 'tag' :
                    $tag = Bootstrap::getModel('tag/model');
                    $tagNames = $tag->getAllNamesAsArray();
                    $jsonTags = $tag->jsonEncode($tagNames);
                    echo $jsonTags;
                    break;

                case 'lesson' :
                    $lesson = Bootstrap::getModel('lesson/model');
                    $lessonNames = $lesson->getAllNamesAsArray();
                    $jsonLessons = $lesson->jsonEncode($lessonNames);
                    echo $jsonLessons;
                    break;

                case 'student' :
                    $user = Bootstrap::getModel('user/model');
                    $studentNames = $user->getAllUserNames('student');
                    $jsonStudents = $user->jsonEncode($studentNames);
                    echo $jsonStudents;
                    break;

                case 'admin' :
                    $user = Bootstrap::getModel('user/model');
                    $studentNames = $user->getAllUserNames('admin');
                    $jsonStudents = $user->jsonEncode($studentNames);
                    echo $jsonStudents;
                    break;

            }
        }
        
    }
}