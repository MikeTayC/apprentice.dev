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
                    $tag = Bootstrap::getModel('incubate/tag');
                    $tagNames = $tag->getAll();
                    $jsonTags = $tag->jsonEncode($tagNames);
                    echo $jsonTags;
                    break;

                case 'lesson' :
                    $lesson = Bootstrap::getModel('incubate/lesson');
                    $lessonNames = $lesson->getAll();
                    $jsonLessons = $lesson->jsonEncode($lessonNames);
                    echo $jsonLessons;
                    break;

                case 'student' :
                    $user = Bootstrap::getModel('incubate/user');
                    $studentNames = $user->getAll();
                    $jsonStudents = $user->jsonEncode($studentNames);
                    echo $jsonStudents;
                    break;

            }
        }
        
    }
}