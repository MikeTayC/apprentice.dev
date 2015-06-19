<?php

class Page_View_Abstract extends Core_Model_Object{

    public function render(){
        foreach( $this->_data as $nodeKey => $nodeValue) {
            if($nodeKey === 'template') {
                $template = $nodeValue;
                if ($template && file_exists($template)) {
                    include $template;
                }
            }
            elseif(is_a($nodeValue, __CLASS__)){
                $nodeValue->render();
            }
        }
    }
}