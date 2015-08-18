<?php

class Incubate_Controller_View extends Core_Controller_Abstract
{
    public function lessonAction($lessonId)
    {
        $view = $this->loadLayout();

        $lesson = Bootstrap::getModel('incubate/lesson');
        $tag = Bootstrap::getModel('incubate/tag');

        if(!$lessonData = $lesson->get(array('lesson_id', '=', $lessonId))) {
            Core_Model_Session::dangerFlash('error', 'This lesson does not exist');
            $this->redirect('Incubate','Lesson', 'indexAction');
        }

        $lessonTagMap = $lesson->getTagLessonMapFromLessonId($lessonData->lesson_id);

        //for eaach tag in the map, get the specific tag names from the tag table
        $lessonTags = array();
        if($lessonTagMap) {
            foreach ($lessonTagMap as $mapValue) {
                $tagName = $tag->getTagNameByTagId($mapValue->tag_id);
                $lesson->checkForGroupTagAndAssign($mapValue->tag_id);
                $lessonTags[] = $tagName;
            }
        }
        if(!$lessonData = $lesson->get(array('lesson_id', '=', $lessonId))) {
            Core_Model_Session::dangerFlash('error', 'This lesson does not exist');
            $this->redirect('Incubate','Lesson', 'indexAction');
        }

        $lessonTagMap = $lesson->getTagLessonMapFromLessonId($lessonData->lesson_id);

        //for eaach tag in the map, get the specific tag names from the tag table
        $lessonTags = array();
        if($lessonTagMap) {
            foreach ($lessonTagMap as $mapValue) {
                $tagName = $tag->getTagNameByTagId($mapValue->tag_id);
                $lesson->checkForGroupTagAndAssign($mapValue->tag_id);
                $lessonTags[] = $tagName;
            }
        }

        $description = $lessonData->description;
        if(isset($lessonTags) && !empty($lessonTags)) {
            foreach($lessonTags as $tag) {
                $description .= ' #' . $tag;
            }
        }

        $duration = $lessonData->duration . 'Min.';

        Core_Model_Session::set('lesson_id', $lessonId);
        $view->getContent()->setName($lessonData->name);
        $view->getContent()->setDescription($description);
        $view->getContent()->setDuration($duration);



        $view->render();
    }
}