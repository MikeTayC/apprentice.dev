<?php

class Core_Controller_Index extends Core_Controller_Abstract
{
    public function indexAction()
    {
        $view = Bootstrap::getView('page/view');

        $paragraph = Bootstrap::getView('page/view');
        $paragraph->setText('One paragraph to ruel them all.');

        $view->setChild('first', $paragraph);
        echo $view->render();
    }
}