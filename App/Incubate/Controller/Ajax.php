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

                case 'lesson' :
                    $user = Bootstrap::getModel('incubate/user');
                    $lessonNames = $user->getAllLessonNames();
                    $jsonLessons = $user->jsonEncode($lessonNames);
                    echo $jsonLessons;
                    break;

                case 'student' :
                    $user = Bootstrap::getModel('incubate/user');
                    $studentNames = $user->getAllStudentNames();
                    $jsonStudents = $user->jsonEncode($studentNames);
                    echo $jsonStudents;
                    break;

            }
        }
        
    }
}