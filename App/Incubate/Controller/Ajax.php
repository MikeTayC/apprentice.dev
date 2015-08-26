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
                    $studentNames = $user->getAllNamesAsArray();
                    $jsonStudents = $user->jsonEncode($studentNames);
                    echo $jsonStudents;
                    break;

            }
        }
        
    }
}