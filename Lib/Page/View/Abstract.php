<?php

class Page_View_Abstract extends Core_Model_Object{

    public function render(){
        $template = $this->getTemplate();
        if($template && file_exists($template)){
            include $template;
        } else {
            return 'No Template exists';
        }
    }

}