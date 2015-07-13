<?php

abstract class Page_View_Abstract extends Core_Model_Object
{

    protected $_assets = array();
    protected $_returnAssetsArray = array();
    public function render()
    {

        foreach ($this->_data as $nodeKey => $nodeValue) {
            if ($nodeKey === 'template') {
                $template = $nodeValue;
                if ($template && file_exists($template)) {
                    include $template;
                }
            }
        }
    }
    public function __toString(){
        if($this->getTemplate() && file_exists($this->getTemplate())){
                include $this->getTemplate();
        }
        return '';
    }

    public function getAssets(){
        $assets = $this->sortAssets();
        foreach($assets as $nodeKey => $nodeValue){
            if($nodeValue['type'] === 'css1'){
                 echo "<link href='" . '/assets/css1/' . $nodeValue['file'] . "' rel='stylesheet'>";
            }
            elseif($nodeValue['type'] === 'js1'){
                echo "<script src='" . '/assets/' . $nodeValue['file'] . "'></script>";
            }
            elseif($nodeValue['type'] === 'jquery'){
                echo "<script src='" . $nodeValue['file'] . "'></script>";
            }
        }
    }

    public function sortAssets(){
        for($i=0; $i<count($this->_data['assets']); $i++)
            foreach ($this->_data['assets'][$i] as $node) {
                array_push($this->_assets, $node);
        }
        return $this->_assets;
    }
}